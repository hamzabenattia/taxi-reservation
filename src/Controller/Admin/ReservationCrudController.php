<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
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

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    
    public function configureCrud(Crud $crud): Crud
    {
        return $crud ->setEntityLabelInPlural('Reservations')
                    ->setEntityLabelInSingular('Reservation')
                    ;

    }


    public function createEntity(string $entityFqcn)
    {
        $product = new Reservation();
        $product->getClient($this->getUser());

        return $product;
    }


    public function configureActions(Actions $actions): Actions
{
    return $actions
        // ...
        ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ->add(Crud::PAGE_EDIT, Action::EDIT)

    ;
}


    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('destination')->onlyOnDetail()->setLabel('Destination'),
            TextField::new('destination')->onlyOnIndex()->setLabel('Destination'),
            TextField::new('depAddress')->onlyOnIndex()->setLabel('Addresse de départ'),
            TextField::new('depAddress')->onlyOnDetail()->setLabel('Addresse de départ'),
            DateTimeField::new('reservation_datetime')->onlyOnIndex(),
            DateTimeField::new('reservation_datetime')->onlyOnDetail(),
            NumberField::new('nbPassengers')->onlyOnIndex(),
            NumberField::new('nbPassengers')->onlyOnDetail(),

            MoneyField::new('price')->setCurrency('EUR')->onlyWhenUpdating(),
            ChoiceField::new('status')->setChoices([
                'Pending' => Reservation::STATUS_PENDING,
                'Confirmed' => Reservation::STATUS_CONFIRMED,
                'Cancelled' => Reservation::STATUS_CANCELLED,
            ]),
            TextField::new('client.phoneNumber')->onlyOnDetail()->setLabel('Phone Number'),
            TextField::new('client.email')->onlyOnDetail(),
            TextField::new('client.firstName')->onlyOnDetail(),
            TextField::new('client.lastName ')->onlyOnDetail(),


            
            DateField::new('createdAt')->hideOnForm(),

        ];
    }

}
