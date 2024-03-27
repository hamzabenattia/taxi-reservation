<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Reservation;
use App\Form\ReservationFormType;
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
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]

    public function index(Request $request): Response
    {
        $reservation = new Reservation();

        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);



        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }





    #[Route('/formsubmit', name: 'app_form', methods: ['GET', 'POST'])]

    public function formSubmit(Request $request, EntityManagerInterface $manager, #[CurrentUser] Client $client, MailerInterface $mailer): Response
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


            $email = (new TemplatedEmail())
                ->from(new Address('pentiminax.bot@gmail.com', 'Pentiminax'))
                ->to(new Address('hamza@gmail.com'))
                ->subject('Nouvelle réservation de taxi')

                // path of the Twig template to render
                ->htmlTemplate('emails/reservationconfirmation.html.twig')

                // change locale used in the template, e.g. to match user's locale
                ->locale('fr')

                // pass variables (name => value) to the template
                ->context([
                    'date' => new \DateTime(),
                    'clientName' => $reservation->getClient()->getFirstName() . ' ' . $reservation->getClient()->getLastName(),
                    'clientPhone' => $reservation->getClient()->getPhoneNumber(),
                    'addressDep' => $reservation->getDepAddress(),
                    'addressArr' => $reservation->getDestination(),
                    'nbPassengers' => $reservation->getNbPassengers(),
                    'dateDep' => $reservation->getReservationDatetime(),
                    'id' => $reservation->getId()

                ]);

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {

                dd($e->getMessage());
                // some error prevented the email sending; display an
                // error message or try to resend the message
            }
            return $this->redirectToRoute('app_reservation');
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
