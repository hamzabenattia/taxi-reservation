<?php

namespace App\Controller\Admin;

use App\Entity\Facture;
use App\Repository\ReservationRepository;
use App\Service\PdfGenerator;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class FactureCrudController extends AbstractCrudController
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

  


    
    public static function getEntityFqcn(): string
    {
        return Facture::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_NEW, Action::INDEX)
            ->add(Crud::PAGE_EDIT, Action::INDEX);

        }





    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('client'),
            TextField::new('reservation'),
            MoneyField::new('priceHT')->setCurrency('EUR'),
            MoneyField::new('priceTTC')->setCurrency('EUR'),
        ];
    }
    
}
