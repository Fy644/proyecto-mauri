<?php
    session_start();

    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if (!isset($_SESSION['purchase_details'])) {
        header("Location: index.php");
        exit();
    }

    require('fpdf/fpdf.php'); // Ensure the FPDF library is installed and available

    $details = $_SESSION['purchase_details'];

    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial', 'B', 16);
            $this->Cell(0, 10, 'Agencia Elmas Capitos', 0, 1, 'C');
            $this->SetFont('Arial', '', 12);
            $this->Cell(0, 10, 'Direccion: Av. Principal #123, Ciudad, Pais', 0, 1, 'C');
            $this->Cell(0, 10, 'Telefono: +52 449 123 4567 | Email: contacto@elmascapitos.com', 0, 1, 'C');
            $this->Ln(10);
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    // Add purchase details
    $pdf->Cell(0, 10, 'Recibo de Compra', 0, 1, 'C');
    $pdf->Ln(5);

    // Customer Information
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Informacion del Cliente:', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Nombre: ' . $details['client_name'], 0, 1);
    $pdf->Cell(0, 10, 'Fecha de Compra: ' . date('d/m/Y'), 0, 1);
    $pickup_date = date('d/m/Y', strtotime('+1 week'));
    $pdf->Cell(0, 10, 'Fecha de Entrega: ' . $pickup_date, 0, 1);
    $pdf->Ln(5);

    // Car Information
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Detalles del Coche:', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Coche: ' . $details['car_name'] . ' (' . $details['car_year'] . ')', 0, 1);
    $pdf->Cell(0, 10, 'Precio: $' . number_format($details['price'], 2), 0, 1);
    $pdf->Cell(0, 10, 'Pago Inicial: $' . number_format($details['down_payment'], 2), 0, 1);

    if ($details['monthly']) {
        $pdf->Cell(0, 10, 'Pago Mensual: $' . number_format($details['monthly_rate'], 2), 0, 1);
        $pdf->Cell(0, 10, 'Meses: ' . $details['months'], 0, 1);
    } else {
        $pdf->Cell(0, 10, 'Pago Mensual: N/A', 0, 1);
    }

    $pdf->Ln(5);

    // Employee Information
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, 'Atendido por:', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Empleado: ' . $details['employee_name'], 0, 1);

    // Output the PDF directly to the browser
    $pdf->Output('I', 'Recibo_Compra.pdf'); // 'I' displays the PDF in the browser
    exit();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recibo de Compra</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-4 text-center">
            <a href="index.php" class="btn btn-primary">Volver al Inicio</a>
        </div>
    </body>
</html>
