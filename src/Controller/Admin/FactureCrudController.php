<?php

namespace App\Controller\Admin;

use App\Entity\Facture;
use App\Service\PdfGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FactureCrudController extends AbstractCrudController
{

    private $pdfGenerator;

    public function __construct(PdfGenerator $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }


    
    public static function getEntityFqcn(): string
    {
        return Facture::class;
    }

    public function afterEntityPersisted(Facture $entity)
    {
            // Example data for PDF generation (replace with actual data)
            $factureData = '<h1>Facture Content</h1>';
            
            // Generate PDF for the new Facture
            $pdfContent = $this->pdfGenerator->generateFacturePdf($factureData);
            
            // Save PDF file to the server
            $pdfFilename = 'facture_' . $entity->getId() . '.pdf';
            file_put_contents($this->getParameter('kernel.project_dir') . '/public/pdf/' . $pdfFilename, $pdfContent);
        }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('client'),
            TextField::new('reservation'),
            NumberField::new('priceHT')->setDecimalSeparator(','),
            NumberField::new('priceTTC')->setDecimalSeparator(','),
        ];
    }
    
}
