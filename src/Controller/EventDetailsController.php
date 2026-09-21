<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventDetailsController extends AbstractController
{
    #[Route('/atelier/{workshop_slug}/{event_slug}/infos', name: 'app_event_details')]
    public function index(EventRepository $eventRepository, $workshop_slug, $event_slug): Response
    {
        $event = $eventRepository->findOneBy(['slug' => $event_slug]);

        return $this->render('event_details/index.html.twig', [
            'event' => $event,
        ]);
    }
}
