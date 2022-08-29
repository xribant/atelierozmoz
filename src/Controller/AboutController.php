<?php

namespace App\Controller;

use App\Repository\WorkshopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    #[Route('/about', name: 'app_about')]
    public function index(WorkshopRepository $workshopRepository): Response
    {
        $workshops = $workshopRepository->findAll();

        return $this->render('about/index.html.twig', [
            'workshops' => $workshops
        ]);
    }
}
