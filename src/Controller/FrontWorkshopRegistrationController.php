<?php

namespace App\Controller;

use App\Entity\EventRegistration;
use App\Form\FrontRegistrationType;
use App\Repository\EventRepository;
use App\Repository\WorkshopRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontWorkshopRegistrationController extends AbstractController
{
    #[Route('/atelier/{workshop_slug}/{event_slug}/inscription', name: 'app_front_workshop_registration')]
    public function index(Request $request, WorkshopRepository $workshopRepository, EventRepository $eventRepository, $event_slug): Response
    {
        $event = $eventRepository->findOneBy(['slug' => $event_slug]);

        $eventRegistration = new EventRegistration();

        $form = $this->createForm(FrontRegistrationType::class, $eventRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            return $this->redirectToRoute('app_agenda', [], Response::HTTP_SEE_OTHER);
        } 

        return $this->render('front_workshop_registration/index.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }
}
