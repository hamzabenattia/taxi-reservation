<?php

namespace App\Controller\Admin;

use App\Entity\Driver;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DriverCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Driver::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setPageTitle(Crud::PAGE_EDIT, 'Modifier mon profile');
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstName')->setLabel('Prénom'),
            TextField::new('lastName')->setLabel('Nom'),
            TextField::new('address')->setLabel('Addresse Postal'),
            TextField::new('phoneNumber')->setLabel('Numéro de téléphone'),
            EmailField::new('email'),
            TextField::new('companyName'),
            TextEditorField::new('description'),
        ];
    }
    
}
