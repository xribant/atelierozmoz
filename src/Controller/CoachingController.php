<?php

namespace App\Controller;

use App\Entity\Coaching;
use App\Form\CoachingType;
use App\Repository\CoachingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/coaching')]
class CoachingController extends AbstractController
{
    #[Route('/', name: 'app_coaching_index', methods: ['GET'])]
    public function index(CoachingRepository $coachingRepository): Response
    {
        return $this->render('admin/coaching/index.html.twig', [
            'coachings' => $coachingRepository->findAll(),
            'active_menu' => 'coaching'
        ]);
    }

    #[Route('/new', name: 'app_coaching_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CoachingRepository $coachingRepository): Response
    {
        $coaching = new Coaching();
        $form = $this->createForm(CoachingType::class, $coaching);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coachingRepository->save($coaching, true);

            return $this->redirectToRoute('app_coaching_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/coaching/new.html.twig', [
            'coaching' => $coaching,
            'form' => $form,
            'active_menu' => 'coaching'
        ]);
    }

    #[Route('/{uid}/edit', name: 'app_coaching_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Coaching $coaching, CoachingRepository $coachingRepository): Response
    {
        $form = $this->createForm(CoachingType::class, $coaching);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coachingRepository->save($coaching, true);

            return $this->redirectToRoute('app_coaching_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/coaching/edit.html.twig', [
            'coaching' => $coaching,
            'form' => $form,
            'active_menu' => 'coaching'
        ]);
    }

    #[Route('/{uid}', name: 'app_coaching_delete', methods: ['POST'])]
    public function delete(Request $request, Coaching $coaching, CoachingRepository $coachingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coaching->getUid(), $request->request->get('_token'))) {
            $coachingRepository->remove($coaching, true);
        }

        return $this->redirectToRoute('app_coaching_index', [], Response::HTTP_SEE_OTHER);
    }
}
