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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

#[Route('/admin/ateliers/inscriptions')]
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
                    ->positionClass('toast-top-center')
                    ->flash()
                ;

            } catch (TransportExceptionInterface $e) {
                $toastr
                    ->warning("Inscription invalide. L'adresse e-mail introduite à l'inscription n'existe pas !!!")
                    ->timeOut(10000)
                    ->progressBar()
                    ->closeButton()
                    ->positionClass('toast-top-center')
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

    #[Route('/export', name: 'app_event_registration_export', methods: ['GET'])]
    public function export(EventRegistrationRepository $eventRegistrationRepository): Response
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle("Inscriptions aux ateliers");

        $sheet->setCellValue('A1', 'Nom');
        $sheet->setCellValue('B1', 'Prénom');
        $sheet->setCellValue('C1', 'E-Mail');
        $sheet->setCellValue('D1', 'Atelier');
        $sheet->setCellValue('E1', 'Adresse');
        $sheet->setCellValue('F1', 'Code Postal');
        $sheet->setCellValue('G1', 'Localité');
        $sheet->setCellValue('H1', 'Date inscription');

        $registrations = $eventRegistrationRepository->findAll();

        $row = 2;
        foreach ($registrations as $registration) {
            $sheet->setCellValue('A'.$row, $registration->getLastName());
            $sheet->setCellValue('B'.$row, $registration->getFirstName());
            $sheet->setCellValue('C'.$row, $registration->getEmail());
            $sheet->setCellValue('D'.$row, $registration->getEvent()->getWorkshop()->getTitle());
            $sheet->setCellValue('E'.$row, $registration->getAddress());
            $sheet->setCellValue('F'.$row, $registration->getPostalCode());
            $sheet->setCellValue('G'.$row, $registration->getCity());
            $sheet->setCellValue('H'.$row, $registration->getCreatedAt()->format('d-m-Y H:i:s'));
            $row++;
        }


        $writer = new Xlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = 'liste_inscriptions_'.date("d_m_Y").'.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
 
        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);
 
        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

        return $this->redirectToRoute('app_event_registration_index', [], Response::HTTP_SEE_OTHER);
    }
}
