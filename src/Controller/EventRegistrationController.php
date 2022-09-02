<?php

namespace App\Controller;

use App\Entity\EventRegistration;
use App\Form\EventRegistrationType;
use App\Repository\EventRegistrationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ateliers/inscription')]
class EventRegistrationController extends AbstractController
{
    #[Route('/', name: 'app_event_registration_index', methods: ['GET'])]
    public function index(EventRegistrationRepository $eventRegistrationRepository): Response
    {
        return $this->render('admin/event_registration/index.html.twig', [
            'registrations' => $eventRegistrationRepository->findAll(),
            'active_menu' => 'registration'
        ]);
    }

    #[Route('/new', name: 'app_event_registration_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRegistrationRepository $eventRegistrationRepository): Response
    {
        $eventRegistration = new EventRegistration();
        $form = $this->createForm(EventRegistrationType::class, $eventRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRegistrationRepository->add($eventRegistration, true);

            return $this->redirectToRoute('app_event_registration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/event_registration/new.html.twig', [
            'registrations' => $eventRegistration,
            'active_menu' => 'registration',
            'form' => $form,
        ]);
    }

    #[Route('/{uid}/edit', name: 'app_event_registration_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EventRegistration $eventRegistration, EventRegistrationRepository $eventRegistrationRepository): Response
    {
        $form = $this->createForm(EventRegistrationType::class, $eventRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRegistrationRepository->add($eventRegistration, true);

            return $this->redirectToRoute('app_event_registration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/event_registration/edit.html.twig', [
            'registrations' => $eventRegistration,
            'active_menu' => 'registration',
            'form' => $form,
        ]);
    }

    #[Route('/{uid}', name: 'app_event_registration_delete', methods: ['POST'])]
    public function delete(Request $request, EventRegistration $eventRegistration, EventRegistrationRepository $eventRegistrationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$eventRegistration->getUid(), $request->request->get('_token'))) {
            $eventRegistrationRepository->remove($eventRegistration, true);
        }

        return $this->redirectToRoute('app_event_registration_index', [], Response::HTTP_SEE_OTHER);
    }
}
