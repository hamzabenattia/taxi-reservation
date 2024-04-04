<?php

namespace App\Controller;

use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;

class  ContactController extends AbstractController
{


    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    #[Route(path: '/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $manager,)
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $contactData = $form->getData();
            $manager->persist($contactData);
            $manager->flush();
    }
    $this->addFlash(
        'info',
        'Votre message a bien été envoyé, nous vous répondrons dans les plus brefs délais.'
    );
    return $this->redirectToRoute('app_home');
 
}





}