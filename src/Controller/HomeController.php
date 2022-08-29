<?php

namespace App\Controller;

use App\Entity\NewsSubscriber;
use App\Form\NewsSubscriberType;
use App\Repository\NewsSubscriberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Flasher\Toastr\Prime\ToastrFactory;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(Request $request, NewsSubscriberRepository $newsSubscriberRepository, ToastrFactory $toastr): Response
    {
        $subscriber = new NewsSubscriber;

        $form = $this->createForm(NewsSubscriberType::class, $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newsSubscriberRepository->add($subscriber, true);

            $toastr
                    ->success('<strong>Inscription confirmée. Merci</strong>')
                    ->timeOut(5000)
                    ->progressBar()
                    ->closeButton()
                    ->positionClass('toast-top-center')
                    ->flash()
                ;

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
