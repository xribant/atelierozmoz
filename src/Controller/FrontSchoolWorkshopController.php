<?php

namespace App\Controller;

use App\Repository\SchoolWorkshopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontSchoolWorkshopController extends AbstractController
{
    #[Route('/dans-les-ecoles', name: 'app_school_workshop')]
    public function index(SchoolWorkshopRepository $schoolWorkshopRepository): Response
    {
        return $this->render('front_school_workshop/index.html.twig', [
            'schoolWorkshops' => $schoolWorkshopRepository->findAll()
        ]);
    }
}
