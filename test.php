<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Test FPDF
require __DIR__.'/fpdf/fpdf.php';
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'FPDF WORKS!',0,1);
$pdf->Output();

// Test QR Code
require __DIR__.'/phpqrcode/qrlib.php';
QRcode::png('TEST', 'testqr.png');
echo "<br>QR Code generated!";
unlink('testqr.png');
?>