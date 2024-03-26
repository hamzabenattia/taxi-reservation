<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Reservation;
use App\Form\ReservationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home' , methods: ['GET', 'POST'])]

    public function index(Request $request): Response
    {
        $reservation = new Reservation();       
            
        $form = $this->createForm(ReservationFormType::class, $reservation);
        $form->handleRequest($request);



        return $this->render('home/index.html.twig', [ 
            'form' => $form->createView()
        ]);
    }





    #[Route('/formsubmit', name: 'app_form' , methods: ['GET', 'POST'])]

    public function formSubmit(Request $request , EntityManagerInterface $manager, #[CurrentUser] Client $client): Response
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
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [ 
            'form' => $form->createView(

            )  
        ]);
    }


    



}
