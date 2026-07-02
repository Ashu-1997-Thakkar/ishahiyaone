<?php
require("fpdf/fpdf.php");

// --- Dummy Data (abhi ke liye) ---
$invoice_no = "INV-1001";
$date = date("d-m-Y");
$customer_name = "Rahul Sharma";
$amount = "1500";
$payment_status = "Paid";

// --- Create PDF ---
$pdf = new FPDF();
$pdf->AddPage();

// Title
$pdf->SetFont("Arial","B",16);
$pdf->Cell(0,10,"Payment Invoice",0,1,"C");
$pdf->Ln(10);

// Invoice Details
$pdf->SetFont("Arial","",12);
$pdf->Cell(50,10,"Invoice No:",1,0);
$pdf->Cell(0,10,$invoice_no,1,1);

$pdf->Cell(50,10,"Date:",1,0);
$pdf->Cell(0,10,$date,1,1);

$pdf->Cell(50,10,"Customer Name:",1,0);
$pdf->Cell(0,10,$customer_name,1,1);

$pdf->Cell(50,10,"Amount:",1,0);
$pdf->Cell(0,10,"Rs. ".$amount,1,1);

$pdf->Cell(50,10,"Payment Status:",1,0);
$pdf->Cell(0,10,$payment_status,1,1);

$pdf->Ln(20);
$pdf->Cell(0,10,"Thank you for your payment!",0,1,"C");

// Output PDF
$pdf->Output();
?>
