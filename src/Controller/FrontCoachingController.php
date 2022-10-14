<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontCoachingController extends AbstractController
{
    #[Route('/front/coaching', name: 'app_front_coaching')]
    public function index(): Response
    {
        return $this->render('front_coaching/index.html.twig', [
            'controller_name' => 'FrontCoachingController',
        ]);
    }
}
