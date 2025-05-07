<?php
    session_start();

    if (!isset($_SESSION['purchase_details'])) {
        die("Error: No se encontraron detalles de la compra.");
    }

    $details = $_SESSION['purchase_details'];
    unset($_SESSION['purchase_details']); // Clear session data after use

    require('fpdf/fpdf.php'); // Ensure the FPDF library is installed in the `fpdf` folder

    class PDF extends FPDF {
        function Header() {
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, 'Recibo de Compra - Agencia Elmas Capitos', 0, 1, 'C');
            $this->Ln(5);
        }

        function Footer() {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Gracias por su compra. Este recibo es válido como comprobante de compra.', 0, 0, 'C');
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    // Business Information
    $pdf->Cell(0, 10, 'Agencia Elmas Capitos', 0, 1, 'C');
    $pdf->Cell(0, 10, 'RFC: ABC123456789', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Direccion: Calle Falsa 123, Ciudad, Pais', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Telefono: +52 449 123 4567', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Correo Electronico: contacto@elmascapitos.com', 0, 1, 'C');
    $pdf->Ln(10);

    // Receipt Information
    $pdf->Cell(0, 10, 'Fecha de Emision: ' . date('Y-m-d H:i:s'), 0, 1);
    $pdf->Cell(0, 10, 'Folio: ' . uniqid('REC-'), 0, 1);
    $pdf->Ln(5);

    // Purchase Details
    $pdf->Cell(0, 10, 'Detalles de la Compra:', 0, 1);
    $pdf->Cell(0, 10, 'Cliente: ' . $details['client_name'], 0, 1);
    $pdf->Cell(0, 10, 'Carro: ' . $details['car_name'] . ' (' . $details['car_year'] . ')', 0, 1);
    $pdf->Cell(0, 10, 'Precio: $' . number_format($details['price'], 2), 0, 1);
    $pdf->Cell(0, 10, 'Pago Inicial: $' . number_format($details['down_payment'], 2), 0, 1);
    $pdf->Cell(0, 10, 'Pagos Mensuales: ' . ($details['monthly'] ? "$" . number_format($details['monthly_rate'], 2) . " por " . $details['months'] . " meses" : "No aplica"), 0, 1);
    $pdf->Cell(0, 10, 'Empleado: ' . $details['employee_name'], 0, 1);

    // Output the PDF to the browser for viewing and downloading
    $pdf->Output('I', 'Recibo_Compra.pdf'); // 'I' opens in the browser, 'D' forces download
    exit();
?>
