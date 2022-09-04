<?php

namespace App\Controller;

use App\Entity\EventRegistration;
use App\Form\FrontRegistrationType;
use App\Repository\EventRegistrationRepository;
use App\Repository\EventRepository;
use App\Repository\WorkshopRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Flasher\Toastr\Prime\ToastrFactory;

class FrontWorkshopRegistrationController extends AbstractController
{
    #[Route('/atelier/{workshop_slug}/{event_slug}/inscription', name: 'app_front_workshop_registration')]
    public function index(Request $request, EventRepository $eventRepository,
     EventRegistrationRepository $eventRegistrationRepository, ToastrFactory $toastr, $event_slug): Response
    {
        $event = $eventRepository->findOneBy(['slug' => $event_slug]);

        $eventRegistration = new EventRegistration();
        $eventRegistration->setEvent($event);

        $form = $this->createForm(FrontRegistrationType::class, $eventRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $eventRegistrationRepository->add($eventRegistration, true);

            $toastr
                    ->success('<strong>Inscription enregistrée! <br>Une confirmation va vous être envoyée par e-mail.</strong>')
                    ->timeOut(5000)
                    ->progressBar()
                    ->closeButton()
                    ->positionClass('toast-top-center')
                    ->flash()
            ;

            return $this->redirectToRoute('app_agenda', [], Response::HTTP_SEE_OTHER);
        } 

        return $this->render('front_workshop_registration/index.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }
}
