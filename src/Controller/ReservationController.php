<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Reservation;
use App\Form\ReservationFormType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(ReservationRepository $repo , PaginatorInterface $paginator, Request $request , #[CurrentUser] Client $client): Response
    {

        $reservations = $paginator->paginate(
            $repo->findByClient($client), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservations
        ]);
    }


    #[Route('/reservation/{id}', name: 'edit_reservation')]
    public function edit(EntityManagerInterface $manager , Reservation $reservation, Request $request , #[CurrentUser] Client $client): Response
    {

        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'Votre réservation a été mis à jour avec succès');


        }

        return $this->render('reservation/edit.html.twig', [
            'form' => $form,
            'reservation' => $reservation
        ]);
    }


    
    #[Route('/reservation/delete/{id}', name: 'delete_reservation')]
    public function delete(EntityManagerInterface $manager , Reservation $reservation, #[CurrentUser] Client $client): Response
    {

        $manager->remove($reservation);
        $manager->flush();
        $this->addFlash(
           'success',
           'Votre réservation a bien été supprimée.'
        );

        return $this->redirectToRoute('app_reservation');

    }


    #[Route('/reservation/accepte/{id}', name: 'accepte_reservation')]
    public function accepte(Reservation $reservation, EntityManagerInterface $manager): Response
    {

        $reservation->setStatus(Reservation::STATUS_CONFIRMED);
        $manager->flush();

        return $this->render('reservation/reservationaccepter.html.twig', [
            'id' => $reservation->getId()
        ]); 

    }

    #[Route('/reservation/refuse/{id}', name: 'refuse_reservation')]
    public function refuse(Reservation $reservation, EntityManagerInterface $manager): Response
    {

        $reservation->setStatus(Reservation::STATUS_CANCELLED);
        $manager->flush();

        $this->addFlash(
           'success',
           'Réservation bien refusée.'
        );

        return $this->redirectToRoute('app_reservation');

    }



}
