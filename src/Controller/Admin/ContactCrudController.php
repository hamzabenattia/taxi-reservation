<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name')->setLabel('Nom du client'),
            TextField::new('email'),
            TextareaField::new('message'),
            DateTimeField::new('createdAt')->setLabel('Date d\'envoi'),
        ];
    }



    public function configureActions(Actions $actions): Actions
    {

        // $viewInvoice = Action::new('Facture')
        // ->displayIf(fn ($entity) => $entity->getStatus() === Reservation::STATUS_CONFIRMED)
        // ->linkToUrl(fn ($entity)=> $this->params->get('kernel.project_dir') . '/public/pdf/' . 'facture_' . $entity-> . '.pdf');
            


        return $actions
            // ...
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX,Action::EDIT)
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
            // ->add(Crud::PAGE_INDEX, $viewInvoice);


          
    }
    
}
