<?php
    session_start();

    if (!isset($_SESSION['purchase_details'])) {
        die("Error: No se encontraron detalles de la compra.");
    }

    $details = $_SESSION['purchase_details'];
    unset($_SESSION['purchase_details']); // Clear session data after use

    require 'fpdf/fpdf.php'; // Include FPDF library

    class PDF extends FPDF {
        function Header() {
            // Check if the logo file exists
            $logoPath = 'logo.png';
            if (file_exists($logoPath)) {
                $this->Image($logoPath, 10, 6, 30); // Adjust the path and size of the logo
            } else {
                $this->SetFont('Arial', 'I', 10);
                $this->Cell(0, 10, 'Logo no disponible', 0, 1, 'C');
            }
            $this->SetFont('Arial', 'B', 14);
            $this->Cell(0, 10, 'Agencia Elmas Capitos', 0, 1, 'C');
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 10, 'Direccion: Calle Principal #123, Ciudad, Pais', 0, 1, 'C');
            $this->Cell(0, 10, 'Telefono: +123 456 7890 | Email: contacto@elmascapitos.com', 0, 1, 'C');
            $this->Ln(10);
        }

        function Footer() {
            $this->SetY(-30);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Gracias por su compra. ¡Disfrute su nuevo carro!', 0, 1, 'C');
            $this->Cell(0, 10, 'Este recibo es generado automaticamente y sirve como comprobante oficial.', 0, 0, 'C');
        }

        function ReceiptDetails($details) {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'Detalles del Recibo', 0, 1, 'L');
            $this->Ln(5);

            $this->SetFont('Arial', '', 12);
            $this->Cell(50, 10, 'Cliente:', 0, 0, 'L');
            $this->Cell(0, 10, utf8_decode($details['client_name']), 0, 1, 'L');

            $this->Cell(50, 10, 'Carro:', 0, 0, 'L');
            $this->Cell(0, 10, utf8_decode($details['car_name']) . ' (' . $details['car_year'] . ')', 0, 1, 'L');

            $this->Cell(50, 10, 'Precio:', 0, 0, 'L');
            $this->Cell(0, 10, '$' . number_format($details['price'], 2), 0, 1, 'L');

            $this->Cell(50, 10, 'Pago Inicial:', 0, 0, 'L');
            $this->Cell(0, 10, '$' . number_format($details['down_payment'], 2), 0, 1, 'L');

            $this->Cell(50, 10, 'Pagos Mensuales:', 0, 0, 'L');
            $this->Cell(0, 10, $details['monthly'] ? "$" . number_format($details['monthly_rate'], 2) . " por " . $details['months'] . " meses" : "No aplica", 0, 1, 'L');

            $this->Cell(50, 10, 'Empleado:', 0, 0, 'L');
            $this->Cell(0, 10, utf8_decode($details['employee_name']), 0, 1, 'L');

            $this->Cell(50, 10, 'Fecha:', 0, 0, 'L');
            $this->Cell(0, 10, date('Y-m-d H:i:s'), 0, 1, 'L');

            $this->Ln(10);
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'Politicas de la Empresa', 0, 1, 'L');
            $this->SetFont('Arial', '', 10);
            $this->MultiCell(0, 10, utf8_decode("1. Todas las ventas son finales y no reembolsables.\n2. Garantía limitada de 1 año en todos los vehículos.\n3. Para cualquier consulta, comuníquese con nuestro servicio al cliente."));
        }
    }

    $pdf = new PDF();
    $pdf->AddPage();
    $pdf->ReceiptDetails($details);
    $pdf->Output('I', 'recibo_de_compra.pdf'); // Output the PDF to the browser
    exit();
?>
