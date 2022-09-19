<?php

namespace App\Controller;

use App\Entity\EventRegistration;
use App\Form\EventRegistrationType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\EventRegistrationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Component\Mailer\MailerInterface;

#[Route('/ateliers/inscription')]
class EventRegistrationController extends AbstractController
{
    #[Route('/', name: 'app_event_registration_index', methods: ['GET'])]
    public function index(EventRegistrationRepository $eventRegistrationRepository): Response
    {
        return $this->render('admin/event_registration/index.html.twig', [
            'registrations' => $eventRegistrationRepository->findAll(),
            'active_menu' => 'registration'
        ]);
    }

    #[Route('/new', name: 'app_event_registration_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EventRegistrationRepository $eventRegistrationRepository, MailerInterface $mailer, ToastrFactory $toastr): Response
    {
        $eventRegistration = new EventRegistration();
        $form = $this->createForm(EventRegistrationType::class, $eventRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRegistrationRepository->add($eventRegistration, true);

            $email = (new TemplatedEmail())
                ->from('admin@atelierozmoz.be')
                ->to($eventRegistration->getEmail())
                // ->to(new Address($eventRegistration->getEmail()))
                ->subject('Atelier Ozmoz: Demande d\'inscription')
                ->htmlTemplate('mails/registrationConfirmation.html.twig')
                ->context([
                    'registration' => $eventRegistration,
                ])
            ;

            try {

                $mailer->send($email);

                $toastr
                    ->success('<strong>Inscription enregistrée! <br>Un e-mail de confirmation vous a été envoyé.</strong>')
                    ->timeOut(5000)
                    ->progressBar()
                    ->closeButton()
                    ->positionClass('toast-top-left')
                    ->flash()
                ;

            } catch (TransportExceptionInterface $e) {
                $toastr
                    ->warning("Inscription invalide. L'adresse e-mail introduite à l'inscription n'existe pas !!!")
                    ->timeOut(10000)
                    ->progressBar()
                    ->closeButton()
                    ->positionClass('toast-top-left')
                    ->flash()
                ;

                return $this->redirectToRoute('app_event_registration_index');
            }

            return $this->redirectToRoute('app_event_registration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/event_registration/new.html.twig', [
            'registrations' => $eventRegistration,
            'active_menu' => 'registration',
            'form' => $form,
        ]);
    }

    #[Route('/{uid}/edit', name: 'app_event_registration_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EventRegistration $eventRegistration, EventRegistrationRepository $eventRegistrationRepository): Response
    {
        $form = $this->createForm(EventRegistrationType::class, $eventRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventRegistrationRepository->add($eventRegistration, true);

            return $this->redirectToRoute('app_event_registration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/event_registration/edit.html.twig', [
            'registrations' => $eventRegistration,
            'active_menu' => 'registration',
            'form' => $form,
        ]);
    }

    #[Route('/{uid}', name: 'app_event_registration_delete', methods: ['POST'])]
    public function delete(Request $request, EventRegistration $eventRegistration, EventRegistrationRepository $eventRegistrationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$eventRegistration->getUid(), $request->request->get('_token'))) {
            $eventRegistrationRepository->remove($eventRegistration, true);
        }

        return $this->redirectToRoute('app_event_registration_index', [], Response::HTTP_SEE_OTHER);
    }
}
