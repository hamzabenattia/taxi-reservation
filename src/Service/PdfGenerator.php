<?php

// src/Service/PdfGenerator.php
namespace App\Service;

use TCPDF;

class PdfGenerator
{
    public function generateFacturePdf($htmlTemplate)
    {
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->Cell(0, 10, 'Taxi Reservation Invoice', 0, 1, 'C');
        $pdf->Ln(10);
        // Write facture data to PDF
        $pdf->writeHTML($htmlTemplate);
        // Generate PDF file content
        $pdfContent = $pdf->Output('', 'S');
        
        return $pdfContent;
    }
}
