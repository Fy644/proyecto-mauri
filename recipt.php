<?php
    session_start();

    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "agencia";
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Error: " . $conn->connect_error);
        exit();
    }

    if (!isset($_GET['id'])) {
        header("Location: index.php");
        exit();
    }

    $sale_id = intval($_GET['id']);
    $sale = $conn->query("SELECT * FROM sales WHERE id = $sale_id")->fetch_assoc();
    if (!$sale) {
        die("Venta no encontrada.");
    }

    // Get car info
    $car = $conn->query("SELECT name, year FROM carros WHERE id = {$sale['id_car']}")->fetch_assoc();
    // Get employee info
    $employee = $conn->query("SELECT name FROM employees WHERE id = {$sale['employee_id']}")->fetch_assoc();

    $details = [
        'client_name'   => $sale['client'],
        'car_name'      => $car ? $car['name'] : '',
        'car_year'      => $car ? $car['year'] : '',
        'price'         => $sale['price'],
        'down_payment'  => $sale['down'],
        'monthly'       => $sale['monthly'],
        'monthly_rate'  => $sale['monthly'] ? ($sale['months'] > 0 ? ($sale['price'] - $sale['down']) / $sale['months'] : 0) : 0,
        'months'        => $sale['months'],
        'employee_name' => $employee ? $employee['name'] : '',
        'datetimePurchase' => $sale['datetimePurchase'],
        'waranty'          => $sale['waranty'],
    ];

    require('fpdf/fpdf.php'); // Ensure the FPDF library is installed and available

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
    $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Recibo de Compra'), 0, 1, 'C');
    $pdf->Ln(5);

    // Customer Information
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Información del Cliente:'), 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Nombre: ') . iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $details['client_name']), 0, 1);
    $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Fecha de Compra: ') . date('d/m/Y', strtotime($details['datetimePurchase'])), 0, 1);
    $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Fecha de Entrega: ') . date('d/m/Y', strtotime($details['datetimePurchase'] . ' +1 week')), 0, 1);
    $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Garantía válida hasta: ') . date('d/m/Y', strtotime($details['waranty'])), 0, 1);
    $pdf->Ln(5);

    // Car Information
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Detalles del Coche:'), 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Coche: ') . iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $details['car_name']) . ' (' . $details['car_year'] . ')', 0, 1);
    $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Precio: $') . number_format($details['price'], 2), 0, 1);
    $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Pago Inicial: $') . number_format($details['down_payment'], 2), 0, 1);

    if ($details['monthly']) {
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Pago Mensual: $') . number_format($details['monthly_rate'], 2), 0, 1);
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Meses: ') . $details['months'], 0, 1);
    } else {
        $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Pago Mensual: N/A'), 0, 1);
    }

    $pdf->Ln(5);

    // Employee Information
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Atendido por:'), 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Empleado: ') . iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $details['employee_name']), 0, 1);

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
