<?php

namespace App\Controller;

use App\Entity\EventRegistration;
use App\Repository\EventRegistrationRepository;
use App\Repository\EventRepository;
use App\Repository\NewsSubscriberRepository;
use App\Repository\TestimonialRepository;
use App\Repository\UserRepository;
use App\Repository\WorkshopLocationRepository;
use App\Repository\WorkshopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin_home')]
    public function index(UserRepository $userRepository, EventRepository $eventRepository, WorkshopRepository $workshopRepository, EventRegistrationRepository $eventRegistrationRepository,
                            WorkshopLocationRepository $workshopLocationRepository, TestimonialRepository $testimonialRepository, NewsSubscriberRepository $newsSubscriberRepository): Response
    {

        return $this->render('admin/home/index.html.twig', [
            'active_menu' => 'dashboard',
            'users' => $userRepository->findAll(),
            'events' => $eventRepository->findAll(),
            'workshops' => $workshopRepository->findAll(),
            'registrations' => $eventRegistrationRepository->findAll(),
            'locations' => $workshopLocationRepository->findAll(),
            'testimonials' => $testimonialRepository->findAll(),
            'news_subscribers' => $newsSubscriberRepository->findAll()
        ]);
    }
}
