<?php

// src/Service/PdfGenerator.php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use TCPDF;

class PdfGenerator
{

    private $params;

    public function __construct(ParameterBagInterface $params) {
        $this->params = $params;
    }




    public function generateFacturePdf($htmlTemplate,$entity)
    {
        $pdf = new TCPDF();
        $pdf->AddPage();
        // Write facture data to PDF
        $pdf->writeHTML($htmlTemplate);
        // Generate PDF file content
        $pdfContent = $pdf->Output('', 'S');
        
        $pdfDirectory = $this->params->get('kernel.project_dir') . '/public/facture/';
        $pdfFilename = 'facture_' . $entity->getId() . '.pdf';

    
        // Save PDF file to the server

        $filesystem = new Filesystem();
        $filesystem->mkdir($pdfDirectory);

        file_put_contents($pdfDirectory . $pdfFilename, $pdfContent);

        return $pdfFilename;

    }
}
