<?php

$currentFile = basename(__FILE__);
include __DIR__ . '/header.php';



$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(40, 10, 'Hello World!');
$pdf->Output();



include __DIR__ . '/footer.php';
