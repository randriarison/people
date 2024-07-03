<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Manager;
use App\Entity\Person;
use App\Entity\Team;
use App\Entity\TeamManagerInterface;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier, private readonly Security $security)
    {
    }

    #[Route('/user/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%avatarDirectory%')]
        string              $avatarDirectory,
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $currentUser = $this->getUser();
            $role = $form->get('role')->getData();
            if (!empty($role)) {
                $user->setRoles([$role]);
                switch ($role) {
                    case 'ROLE_MANAGER':
                        $person = new Manager();
                        break;
                    case 'ROLE_USER':
                        $person = new Person();
                        if (
                            $currentUser instanceof User
                            && $this->security->isGranted('ROLE_MANAGER')
                            && $currentUser->getTeamManager()
                        ) {
                            $person->setManager($currentUser->getTeamManager());
                        }
                        break;
                    default:
                        $person = null;
                }
                if ($person) {
                    if (
                        $currentUser instanceof User
                        && $currentUser->getTeamManager()
                    ) {
                        $person->setCompany($currentUser->getTeamManager()->getCompany());
                    } elseif ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
                        $company = $form->get('company')->getData();
                        if ($company instanceof Company) {
                            $person->setCompany($company);
                        }
                    }
                    $person->setUser($user);
                    $team = $form->get('team')->getData();
                    if ($team instanceof Team) {
                        $person->addTeam($team);
                    }
                    $entityManager->persist($person);
                }
            }
            $this->setUserAvatarFromForm($user, $form, $slugger, $avatarDirectory);

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('no-reply@metaways.fr', 'Skeels team'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            $this->addFlash('info', 'Un email vous a été envoyé.');

            return $this->redirectToRoute('app_evaluation');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }

    private function setUserAvatarFromForm(
        User                $user,
        FormInterface       $form,
        SluggerInterface    $slugger,
        #[Autowire('%avatarDirectory%')]
        string              $avatarDirectory,
        bool                $deleteCurrent = false
    ): void
    {
        $avatarFile = $form->get('avatarFile')->getData();

        if ($avatarFile) {
            $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $avatarFile->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $avatarFile->move($avatarDirectory, $newFilename);
                if ($deleteCurrent) {
                    $file = $avatarDirectory . '/' . $user->getAvatar();
                    if (file_exists($file)) {
                        unlink($file);
                    }

                }
            } catch (FileException $e) {
                $this->addFlash('danger', 'an error occurs while uploading the file');
            }

            $user->setAvatar($newFilename);
        }
    }
}
