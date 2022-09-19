<?php

namespace App\Controller;

use App\Entity\EventRegistration;
use App\Entity\NewsSubscriber;
use App\Form\FrontRegistrationType;
use App\Repository\EventRegistrationRepository;
use App\Repository\EventRepository;
use App\Repository\NewsSubscriberRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class FrontWorkshopRegistrationController extends AbstractController
{
    #[Route('/atelier/{workshop_slug}/{event_slug}/inscription', name: 'app_front_workshop_registration')]
    public function index(Request $request, EventRepository $eventRepository,
     EventRegistrationRepository $eventRegistrationRepository, ToastrFactory $toastr, $event_slug, MailerInterface $mailer, NewsSubscriberRepository $newsSubscriberRepository): Response
    {
        $event = $eventRepository->findOneBy(['slug' => $event_slug]);

        $eventRegistration = new EventRegistration();
        $eventRegistration->setEvent($event);

        $form = $this->createForm(FrontRegistrationType::class, $eventRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $eventRegistrationRepository->add($eventRegistration, true);

            if(($form->get("newsletter")->getData()) && (!$newsSubscriberRepository->findOneBy(['email' => $eventRegistration->getEmail()]))) {
                $newsletterMember = new NewsSubscriber;
                $newsletterMember->setEmail($eventRegistration->getEmail());
                $newsSubscriberRepository->add($newsletterMember, true);
            }
            
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

                return $this->redirectToRoute('app_agenda');
            } 

            return $this->redirectToRoute('app_agenda', [], Response::HTTP_SEE_OTHER);
        
        } 

        return $this->render('front_workshop_registration/index.html.twig', [
            'event' => $event,
            'form' => $form->createView()
        ]);
    }
}
