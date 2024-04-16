<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use App\Service\Decorator\Component\PersonItroductionComponent;
use App\Service\Decorator\Decorator\EmployedIntroductionDecorator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class PersonController extends AbstractController
{
    #[Route('/', name: 'app_person_index', methods: ['GET'])]
    public function index(PersonRepository $personRepository): Response
    {
        $people = $personRepository->findAll();
        
        $decoratedPeople = [];
        foreach($people as $i => $person) {
            $component = new PersonItroductionComponent();
            $decoratedPeople[$i] = new EmployedIntroductionDecorator($component);
        }

        return $this->render('person/index.html.twig', [
            'people' => $people,
            'decorated' => $decoratedPeople
        ]);
    }

    #[Route('/person/new', name: 'app_person_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($person);
            $entityManager->flush();

            return $this->redirectToRoute('app_person_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('person/new.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/person/{id}', name: 'app_person_show', methods: ['GET'])]
    public function show(Person $person): Response
    {
        return $this->render('person/show.html.twig', [
            'person' => $person,
        ]);
    }

    #[Route('/person/{id}/edit', name: 'app_person_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Person $person, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_person_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('person/edit.html.twig', [
            'person' => $person,
            'form' => $form,
        ]);
    }

    #[Route('/person/{id}', name: 'app_person_delete', methods: ['POST'])]
    public function delete(Request $request, Person $person, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$person->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($person);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_person_index', [], Response::HTTP_SEE_OTHER);
    }
}
