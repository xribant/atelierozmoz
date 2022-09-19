<?php

namespace App\Controller;

use App\Entity\WorkshopLocation;
use App\Form\WorkshopLocationType;
use App\Repository\WorkshopLocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/workshop/location')]
class WorkshopLocationController extends AbstractController
{
    #[Route('/', name: 'app_workshop_location_index', methods: ['GET'])]
    public function index(WorkshopLocationRepository $workshopLocationRepository): Response
    {
        return $this->render('admin/workshop_location/index.html.twig', [
            'locations' => $workshopLocationRepository->findAll(),
            'active_menu' => 'localisations'
        ]);
    }

    #[Route('/new', name: 'app_workshop_location_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WorkshopLocationRepository $workshopLocationRepository): Response
    {
        $workshopLocation = new WorkshopLocation();
        $form = $this->createForm(WorkshopLocationType::class, $workshopLocation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workshopLocationRepository->add($workshopLocation, true);

            return $this->redirectToRoute('app_workshop_location_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/workshop_location/new.html.twig', [
            'workshop_location' => $workshopLocation,
            'form' => $form,
            'active_menu' => 'localisations'
        ]);
    }

    #[Route('/{uid}/edit', name: 'app_workshop_location_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WorkshopLocation $workshopLocation, WorkshopLocationRepository $workshopLocationRepository): Response
    {
        $form = $this->createForm(WorkshopLocationType::class, $workshopLocation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workshopLocationRepository->add($workshopLocation, true);

            return $this->redirectToRoute('app_workshop_location_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/workshop_location/edit.html.twig', [
            'workshop_location' => $workshopLocation,
            'form' => $form,
            'active_menu' => 'localisations'
        ]);
    }

    #[Route('/{uid}', name: 'app_workshop_location_delete', methods: ['POST'])]
    public function delete(Request $request, WorkshopLocation $workshopLocation, WorkshopLocationRepository $workshopLocationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$workshopLocation->getUid(), $request->request->get('_token'))) {
            $workshopLocationRepository->remove($workshopLocation, true);
        }

        return $this->redirectToRoute('app_workshop_location_index', [], Response::HTTP_SEE_OTHER);
    }
}
