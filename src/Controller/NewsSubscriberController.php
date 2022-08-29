<?php

namespace App\Controller;

use App\Entity\NewsSubscriber;
use App\Form\NewsSubscriberType;
use App\Repository\NewsSubscriberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/newsletter/inscrits')]
class NewsSubscriberController extends AbstractController
{
    #[Route('/', name: 'app_news_subscriber_index', methods: ['GET'])]
    public function index(NewsSubscriberRepository $newsSubscriberRepository): Response
    {
        return $this->render('admin/news_subscriber/index.html.twig', [
            'newsSubscribers' => $newsSubscriberRepository->findAll(),
            'active_menu' => 'newsletter_subscribers'
        ]);
    }

    #[Route('/new', name: 'app_news_subscriber_new', methods: ['GET', 'POST'])]
    public function new(Request $request, NewsSubscriberRepository $newsSubscriberRepository): Response
    {
        $newsSubscriber = new NewsSubscriber();
        $form = $this->createForm(NewsSubscriberType::class, $newsSubscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsSubscriberRepository->add($newsSubscriber, true);

            return $this->redirectToRoute('app_news_subscriber_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/news_subscriber/new.html.twig', [
            'news_subscriber' => $newsSubscriber,
            'form' => $form,
            'active_menu' => 'newsletter_subscribers'
        ]);
    }

    #[Route('/{uid}/edit', name: 'app_news_subscriber_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NewsSubscriber $newsSubscriber, NewsSubscriberRepository $newsSubscriberRepository): Response
    {
        $form = $this->createForm(NewsSubscriberType::class, $newsSubscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsSubscriberRepository->add($newsSubscriber, true);

            return $this->redirectToRoute('app_news_subscriber_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/news_subscriber/edit.html.twig', [
            'news_subscriber' => $newsSubscriber,
            'form' => $form,
            'active_menu' => 'newsletter_subscribers'
        ]);
    }

    #[Route('/{uid}', name: 'app_news_subscriber_delete', methods: ['POST'])]
    public function delete(Request $request, NewsSubscriber $newsSubscriber, NewsSubscriberRepository $newsSubscriberRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$newsSubscriber->getUid(), $request->request->get('_token'))) {
            $newsSubscriberRepository->remove($newsSubscriber, true);
        }

        return $this->redirectToRoute('app_news_subscriber_index', [], Response::HTTP_SEE_OTHER);
    }
}
