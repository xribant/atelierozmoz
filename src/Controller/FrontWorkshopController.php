<?php

namespace App\Controller;

use App\Entity\Workshop;
use App\Repository\WorkshopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontWorkshopController extends AbstractController
{
    #[Route('/atelier/{slug}', name: 'app_front_workshop_details')]
    public function index(WorkshopRepository $workshopRepository, $slug): Response
    {
        $workshop = $workshopRepository->findOneBy(['slug' => $slug]);

        return $this->render('front_workshop/index.html.twig', [
            'workshop' => $workshop,
        ]);
    }
}
