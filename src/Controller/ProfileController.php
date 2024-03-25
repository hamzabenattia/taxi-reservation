<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Attribute\CurrentUser;


#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/client/edit', name: 'app_profile'),IsGranted('ROLE_USER')]
    public function index(EntityManagerInterface $manager, Request $request,  #[CurrentUser] Client $user): Response
    {

        $form = $this->createForm(ClientFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form->createView()
        ]);
    }


    
}
