<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Contact;
use App\Entity\Reservation;
use App\Form\ContactType;
use App\Form\ReservationFormType;
use App\Repository\DriverRepository;
use App\Service\EmailSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class HomeController extends AbstractController
{

    private $emailSender;

    public function __construct(EmailSender $emailSender)
    {
        $this->emailSender = $emailSender;
    }


    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]

    public function index(Request $request, DriverRepository $driverRepo): Response
    {

        if ($driverRepo->findAll() == null) {
           return $this->redirectToRoute('app_driver_register'); 
        }


        $reservation = new Reservation();

        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);



        $contact = new Contact();
        $contact_form = $this->createForm(ContactType::class, $contact,[
            'action' => $this->generateUrl('app_contact')
        ]);



        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'contactForm' => $contact_form->createView()
        ]);
    }





    #[Route('/formsubmit', name: 'app_form', methods: ['GET', 'POST'])]

    public function formSubmit(Request $request, EntityManagerInterface $manager, #[CurrentUser] Client $client, DriverRepository $driverRepo): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if (!$this->getUser()) {
                return $this->redirectToRoute('app_login');
            }
            $manager->persist($form->getData());
            if ($this->getUser()) {
                $form->getData()->setClient($this->getUser());
            }

            $manager->flush();
            $this->addFlash('success', 'Votre réservation a bien été enregistrée');





            $this->emailSender->sendEmail(
              'noreply@taxi.fr',
              $driverRepo->findAll()[0]->getEmail(),
                'Nouvelle réservation de taxi',
                'emails/reservationconfirmation.html.twig',
                [
                    'date' => new \DateTime(),
                    'clientName' => $reservation->getClient()->getFirstName() . ' ' . $reservation->getClient()->getLastName(),
                    'clientPhone' => $reservation->getClient()->getPhoneNumber(),
                    'addressDep' => $reservation->getDepAddress(),
                    'addressArr' => $reservation->getDestination(),
                    'nbPassengers' => $reservation->getNbPassengers(),
                    'dateDep' => $reservation->getReservationDatetime(),
                    'id' => $reservation->getId()
                ]
            );


            return $this->redirectToRoute('app_reservation');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
