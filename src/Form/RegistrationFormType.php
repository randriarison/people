<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Team;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private Security $security
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add(
                'avatarFile',
                FileType::class,
                [
                    'mapped' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'please upload a valid image',
                        ])
                    ],
                ])
            ->add('phoneNumber', TelType::class)
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Merci d'accepter les coditions générailes de Skeels.",
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ])
                ],
            ]);
            $paramRoles = $this->parameterBag->get('security.role_hierarchy.roles');
            $currentUser = $this->security->getUser();
            $userRoleHierarchy = 0;
            if($currentUser instanceof User && !empty($currentUser->getRoles())) {
                $userRoles = $currentUser->getRoles();
                $userRoleHierarchy = constant('App\Entity\User::' . $userRoles[0]);
            }
            $roles = [];
            if (is_array($paramRoles)) {
                foreach ($paramRoles as $key => $paramRole) {
                    if (constant('App\Entity\User::' . $key) < $userRoleHierarchy  && is_array($paramRole)) {
                        $roles[str_replace('_', ' ', $key)] = $key;
                    }

                }
            }

            $builder->add('role', ChoiceType::class, [
                'mapped' => false,
                'choices' => $roles
            ])
        ;
        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            $builder->add('company', EntityType::class, [
                'mapped' => false,
                'class' => Company::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez un client',
                'required' => true
            ]);
        }
        if ($this->security->isGranted('ROLE_MANAGER')) {
            $builder->add('team', EntityType::class, [
                'class' => Team::class,
                'mapped' => false,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez une équipe',
                'required' => true,
                'query_builder' => function (EntityRepository $er) use ($currentUser) : QueryBuilder  {
                    $qb = $er->createQueryBuilder('team');
                    if ($this->security->isGranted('ROLE_MANAGER')) {
                        $qb
                            ->join('team.manager', 'manager')
                            ->join('manager.user', 'user')
                            ->andWhere('user.id = :id')
                            ->setParameter('id', $currentUser->getId())
                        ;
                    }

                    return $qb
                        ->orderBy('user.firstname', 'ASC');
                },
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
