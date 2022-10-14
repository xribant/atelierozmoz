<?php

namespace App\Controller;

use App\Repository\CoachingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontCoachingController extends AbstractController
{
    #[Route('/coaching-et-accompagnement', name: 'app_coaching')]
    public function index(CoachingRepository $coachingRepository): Response
    {
        $coachings = $coachingRepository->findAll();

        return $this->render('front_coaching/index.html.twig', [
            'coachings' => $coachings
        ]);
    }
}
