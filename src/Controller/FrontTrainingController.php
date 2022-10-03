<?php

namespace App\Controller;

use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontTrainingController extends AbstractController
{
    #[Route('/formations', name: 'app_training')]
    public function index(TrainingRepository $trainingRepository): Response
    {
        return $this->render('front_training/index.html.twig', [
            'trainings' => $trainingRepository->findAll()
        ]);
    }
}
