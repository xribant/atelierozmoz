<?php

namespace App\Controller;

use App\Entity\Testimonial;
use App\Form\TestimonialType;
use App\Repository\TestimonialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/testimonial')]
class TestimonialController extends AbstractController
{
    #[Route('/', name: 'app_testimonial_index', methods: ['GET'])]
    public function index(TestimonialRepository $testimonialRepository): Response
    {
        return $this->render('admin/testimonial/index.html.twig', [
            'testimonials' => $testimonialRepository->findAll(),
            'active_menu' => 'testimonial'
        ]);
    }

    #[Route('/new', name: 'app_testimonial_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TestimonialRepository $testimonialRepository): Response
    {
        $testimonial = new Testimonial();
        $form = $this->createForm(TestimonialType::class, $testimonial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $testimonialRepository->add($testimonial, true);

            return $this->redirectToRoute('app_testimonial_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/testimonial/new.html.twig', [
            'testimonial' => $testimonial,
            'form' => $form,
            'active_menu' => 'testimonial'
        ]);
    }

    #[Route('/{uid}/edit', name: 'app_testimonial_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Testimonial $testimonial, TestimonialRepository $testimonialRepository): Response
    {
        $form = $this->createForm(TestimonialType::class, $testimonial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $testimonialRepository->add($testimonial, true);

            return $this->redirectToRoute('app_testimonial_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/testimonial/edit.html.twig', [
            'testimonial' => $testimonial,
            'form' => $form,
            'active_menu' => 'testimonial'
        ]);
    }

    #[Route('/{uid}', name: 'app_testimonial_delete', methods: ['POST'])]
    public function delete(Request $request, Testimonial $testimonial, TestimonialRepository $testimonialRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$testimonial->getUid(), $request->request->get('_token'))) {
            $testimonialRepository->remove($testimonial, true);
        }

        return $this->redirectToRoute('app_testimonial_index', [], Response::HTTP_SEE_OTHER);
    }
}
