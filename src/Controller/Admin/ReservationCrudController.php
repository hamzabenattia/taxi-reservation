<?php

namespace App\Controller\Admin;

use App\Entity\Facture;
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use App\Service\EmailSender;
use App\Service\PdfGenerator;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Twig\Environment;

class ReservationCrudController extends AbstractCrudController
{
    private $pdfGenerator;
    private $params;
    private $twig;
    private $manager;




    public function __construct(PdfGenerator $pdfGenerator , private EmailSender $emailSender, ParameterBagInterface $params, Environment $twig , private ReservationRepository $repo ,  EntityManagerInterface $manager)
    {
        $this->pdfGenerator = $pdfGenerator;
        $this->params = $params;
        $this->twig = $twig;
        $this->manager = $manager;

    }

    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }


    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInPlural('Reservations')
            ->setEntityLabelInSingular('Reservation');
    }


    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $facture = new Facture();
        $facture->setReservation($entityInstance);
        $facture->setClient($entityInstance->getClient());
        $facture->setPriceHT($entityInstance->getPrice());
        $entityInstance->setFacture($facture);
        // $facture->setTVA(20);
        $facture->setPriceTTC($entityInstance->getPrice() * 1.2);
        $entityManager->persist($facture);
        $entityManager->flush();


        if ($entityInstance->getStatus() == Reservation::STATUS_CONFIRMED) {
            $this->emailSender->sendEmail(
                $this->params->get('emailAddress'),
                $entityInstance->getClient()->getEmail(),
                'Votre réservation a été acceptée',
                'emails/client/reservationAccecpter.html.twig',
                [
                   'reservation' => $entityInstance
                ]
            );
        } elseif ($entityInstance->getStatus() == Reservation::STATUS_CANCELLED) {
            $this->emailSender->sendEmail(   $this->params->get('emailAddress'),
             $entityInstance->getClient()->getEmail(), 
             'Votre réservation a été refusée',
              'emails/client/reservationRefuser.html.twig',
               ['reservation' => $entityInstance]
            );
        }







        // Render HTML template for the invoice
        $htmlTemplate = $this->twig->render('pdf/invoice_template.html.twig', [
            'invoice' => $facture,
            'reservation' => $entityInstance,

        ]);


        // Generate PDF for the new Facture
       $factureName = $this->pdfGenerator->generateFacturePdf($htmlTemplate,$facture);


       $facture ->setName($factureName);
       $entityManager->persist($facture);
       $entityManager->flush();


       

    }
    


    public function configureActions(Actions $actions): Actions
    {

        $viewInvoice = Action::new('Facture')
        ->displayIf(fn ($entity) => $entity->getStatus() === Reservation::STATUS_CONFIRMED)
        ->linkToUrl(fn ($entity) => 'facture/'.$entity->getFacture()->getName());
            


        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->add(Crud::PAGE_INDEX, $viewInvoice);


          
    }



    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('destination')->onlyOnDetail()->setLabel('Destination'),
            TextField::new('destination')->onlyOnIndex()->setLabel('Destination'),
            TextField::new('depAddress')->onlyOnIndex()->setLabel('Addresse de départ'),
            TextField::new('depAddress')->onlyOnDetail()->setLabel('Addresse de départ'),
            DateTimeField::new('reservation_datetime')->onlyOnIndex()->setLabel('Date et heure de réservation'),
            DateTimeField::new('reservation_datetime')->onlyOnDetail()->setLabel('Date et heure de réservation'),
            NumberField::new('nbPassengers')->onlyOnIndex()->setLabel('Nombre de passagers'),
            NumberField::new('nbPassengers')->onlyOnDetail()->setLabel('Nombre de passagers'),

            MoneyField::new('price')->setCurrency('EUR')->setLabel('Prix'),
            ChoiceField::new('status')->setChoices([
                'Confirmé' => Reservation::STATUS_CONFIRMED,
                'Annulé' => Reservation::STATUS_CANCELLED,
                'En attente' => Reservation::STATUS_PENDING,

            ]),
            TextField::new('client.phoneNumber')->onlyOnDetail()->setLabel('Numéro de téléphone de client'),
            TextField::new('client.email')->onlyOnDetail()->setLabel('Email de client'),
            TextField::new('client.firstName')->onlyOnDetail()->setLabel('Prénom de client'),
            TextField::new('client.lastName ')->onlyOnDetail()->setLabel('Nom de client'),

            DateField::new('createdAt')->hideOnForm()->setLabel('Date de création'),


        ];
    }
}
