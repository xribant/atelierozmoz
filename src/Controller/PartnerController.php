<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartnerController extends AbstractController
{
    #[Route('/partenaires', name: 'app_partner')]
    public function index(): Response
    {
        return $this->render('partner/index.html.twig');
    }
}
