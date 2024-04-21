<?php

namespace App\Controller\Admin;

use App\Entity\Driver;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DriverCrudController extends AbstractCrudController
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }


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
            Field::new( 'password', 'Password' )
            ->setFormType( PasswordType::class )
            

        ];
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        $entityInstance->setPassword(
            $this->passwordHasher->hashPassword(
            $entityInstance,
            $entityInstance->getPassword()
        )
    );

    $entityManager->persist($entityInstance);
    $entityManager->flush();
    $this->addFlash('success', 'Votre profile a été mis à jour avec succès');
    $url = $this->generateUrl('app_dashboard');
    $response = new RedirectResponse($url);
    $response->send();
    }
    
    
}
