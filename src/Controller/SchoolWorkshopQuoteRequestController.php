<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use App\Form\QuoteRequestType;
use App\Repository\SchoolWorkshopRepository;

class SchoolWorkshopQuoteRequestController extends AbstractController
{
    #[Route('/dans-les-ecoles/{slug}/demande-de-devis', name: 'app_school_workshop_quote_request')]
    public function index($slug, Request $request, ToastrFactory $toastr, MailerInterface $mailer, SchoolWorkshopRepository $schoolWorkshopRepository): Response
    {
        $quoteRequest = [];
        $schoolWorkshop = $schoolWorkshopRepository->findOneBy(['slug' => $slug]);

        $form = $this->createForm(QuoteRequestType::class, $quoteRequest);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $quoteRequest = $form->getData();

            $email = (new TemplatedEmail())
                ->from('no-reply@atelierozmoz.be')
                ->to(new Address('info@atelierozmoz.be'))
                ->subject('[atelierozmoz.be]: Formulaire de contact - Dans les écoles')
                ->htmlTemplate('mails/quoteRequest.html.twig')
                ->context([
                    'quoteRequest' => $quoteRequest
                ])
            ;

            try {
                $mailer->send($email);

                $toastr
                    ->success('<strong>Demande reçue! <br>Nous y répondrons dans les meilleurs délais.</strong>')
                    ->timeOut(5000)
                    ->progressBar()
                    ->closeButton()
                    ->positionClass('toast-top-center')
                    ->flash()
                ;

                return $this->redirectToRoute('app_home');

            } catch (TransportExceptionInterface $e) {
                echo $e->getMessage();

            }
        }

        return $this->render('quote_request/school_workshop_index.html.twig', [
            'schoolWorkshop' => $schoolWorkshop,
            'form' => $form->createView(),
        ]);
    }
}
