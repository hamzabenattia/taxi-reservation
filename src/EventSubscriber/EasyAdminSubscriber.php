<?php

// src/EventSubscriber/EasyAdminSubscriber.php
namespace App\EventSubscriber;

use App\Entity\Facture;
use App\Repository\ReservationRepository;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Service\PdfGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $pdfGenerator;
    private $params;
    private $twig;
    private $manager;




    public function __construct(PdfGenerator $pdfGenerator ,  ParameterBagInterface $params, Environment $twig , private ReservationRepository $repo ,  EntityManagerInterface $manager)
    {
        $this->pdfGenerator = $pdfGenerator;
        $this->params = $params;
        $this->twig = $twig;
        $this->manager = $manager;

    }

    public static function getSubscribedEvents()
    {
        return [
            AfterEntityPersistedEvent::class => ['generateFacturePdf'],
        ];
    }

    public function generateFacturePdf(AfterEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();

    

        if (!($entity instanceof Facture)) {
            return;
        }


        $reservation = $this->repo->findOneBy(['id' => $entity->getReservation()]);

        if ($reservation){
            $reservation->setPrice($entity->getPriceTTC());
           $this->manager->flush();
        }
        


        // Render HTML template for the invoice
        $htmlTemplate = $this->twig->render('pdf/invoice_template.html.twig', [
            'invoice' => $entity,
            'reservation' => $reservation,

        ]);


        // Generate PDF for the new Facture
        $pdfContent = $this->pdfGenerator->generateFacturePdf($htmlTemplate);
        $pdfDirectory = $this->params->get('kernel.project_dir') . '/public/pdf/';
        $pdfFilename = 'facture_' . $entity->getId() . '.pdf';

    
        // Save PDF file to the server

        $filesystem = new Filesystem();
        $filesystem->mkdir($pdfDirectory);

        file_put_contents($pdfDirectory . $pdfFilename, $pdfContent);
    }
}
