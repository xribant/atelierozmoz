<?php

namespace App\Controller;

use App\Entity\SchoolWorkshop;
use App\Form\SchoolWorkshopType;
use App\Repository\SchoolWorkshopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/school-workshop')]
class SchoolWorkshopController extends AbstractController
{
    #[Route('/', name: 'app_school_workshop_index', methods: ['GET'])]
    public function index(SchoolWorkshopRepository $schoolWorkshopRepository): Response
    {
        return $this->render('admin/school_workshop/index.html.twig', [
            'schoolWorkshops' => $schoolWorkshopRepository->findAll(),
            'active_menu' => 'school_workshop'
        ]);
    }

    #[Route('/new', name: 'app_school_workshop_new', methods: ['GET', 'POST'])]
    public function new(Request $request, SchoolWorkshopRepository $schoolWorkshopRepository): Response
    {
        $schoolWorkshop = new SchoolWorkshop();
        $form = $this->createForm(SchoolWorkshopType::class, $schoolWorkshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $schoolWorkshopRepository->save($schoolWorkshop, true);

            return $this->redirectToRoute('app_school_workshop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/school_workshop/new.html.twig', [
            'schoolWorkshop' => $schoolWorkshop,
            'active_menu' => 'school_workshop',
            'form' => $form,
        ]);
    }


    #[Route('/{uid}/edit', name: 'app_school_workshop_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SchoolWorkshop $schoolWorkshop, SchoolWorkshopRepository $schoolWorkshopRepository): Response
    {
        $form = $this->createForm(SchoolWorkshopType::class, $schoolWorkshop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $schoolWorkshopRepository->save($schoolWorkshop, true);

            return $this->redirectToRoute('app_school_workshop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/school_workshop/edit.html.twig', [
            'schoolWorkshop' => $schoolWorkshop,
            'active_menu' => 'school_workshop',
            'form' => $form,
        ]);
    }

    #[Route('/{uid}', name: 'app_school_workshop_delete', methods: ['POST'])]
    public function delete(Request $request, SchoolWorkshop $schoolWorkshop, SchoolWorkshopRepository $schoolWorkshopRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$schoolWorkshop->getUid(), $request->request->get('_token'))) {
            $schoolWorkshopRepository->remove($schoolWorkshop, true);
        }

        return $this->redirectToRoute('app_school_workshop_index', [], Response::HTTP_SEE_OTHER);
    }
}
