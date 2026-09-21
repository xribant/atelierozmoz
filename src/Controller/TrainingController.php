<?php

namespace App\Controller;

use App\Entity\Training;
use App\Form\TrainingType;
use App\Repository\TrainingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/training')]
class TrainingController extends AbstractController
{
    #[Route('/', name: 'app_training_index', methods: ['GET'])]
    public function index(TrainingRepository $trainingRepository): Response
    {
        return $this->render('admin/training/index.html.twig', [
            'trainings' => $trainingRepository->findAll(),
            'active_menu' => 'formations'
        ]);
    }

    #[Route('/new', name: 'app_training_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TrainingRepository $trainingRepository): Response
    {
        $training = new Training();
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trainingRepository->save($training, true);

            return $this->redirectToRoute('app_training_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/training/new.html.twig', [
            'training' => $training,
            'active_menu' => 'formations',
            'form' => $form,
        ]);
    }

   
    #[Route('/{uid}/edit', name: 'app_training_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Training $training, TrainingRepository $trainingRepository): Response
    {
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trainingRepository->save($training, true);

            return $this->redirectToRoute('app_training_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/training/edit.html.twig', [
            'training' => $training,
            'active_menu' => 'formations',
            'form' => $form,
        ]);
    }

    #[Route('/{uid}', name: 'app_training_delete', methods: ['POST'])]
    public function delete(Request $request, Training $training, TrainingRepository $trainingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$training->getUid(), $request->request->get('_token'))) {
            $trainingRepository->remove($training, true);
        }

        return $this->redirectToRoute('app_training_index', [], Response::HTTP_SEE_OTHER);
    }
}
