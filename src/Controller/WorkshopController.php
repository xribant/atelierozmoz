<?php

namespace App\Controller;

use App\Entity\Workshop;
use App\Form\WorkshopType;
use App\Repository\WorkshopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/workshop')]
class WorkshopController extends AbstractController
{
    #[Route('/', name: 'app_workshop_index', methods: ['GET'])]
    public function index(WorkshopRepository $workshopRepository): Response
    {
        return $this->render('admin/workshop/index.html.twig', [
            'workshops' => $workshopRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_workshop_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WorkshopRepository $workshopRepository): Response
    {
        $workshop = new Workshop();
        $form = $this->createForm(WorkshopType::class, $workshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workshop->setUid(uniqid());
            $workshopRepository->add($workshop, true);

            return $this->redirectToRoute('app_workshop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/workshop/new.html.twig', [
            'workshop' => $workshop,
            'form' => $form,
        ]);
    }

    #[Route('/{uid}/edit', name: 'app_workshop_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Workshop $workshop, WorkshopRepository $workshopRepository): Response
    {
        $form = $this->createForm(WorkshopType::class, $workshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $workshopRepository->add($workshop, true);

            return $this->redirectToRoute('app_workshop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/workshop/edit.html.twig', [
            'workshop' => $workshop,
            'form' => $form,
        ]);
    }

    #[Route('/{uid}', name: 'app_workshop_delete', methods: ['POST'])]
    public function delete(Request $request, Workshop $workshop, WorkshopRepository $workshopRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$workshop->getUid(), $request->request->get('_token'))) {
            $workshopRepository->remove($workshop, true);
        }

        return $this->redirectToRoute('app_workshop_index', [], Response::HTTP_SEE_OTHER);
    }
}
