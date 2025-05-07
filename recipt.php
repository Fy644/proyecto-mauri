<?php
    session_start();

    if (!isset($_SESSION['purchase_details'])) {
        die("Error: No se encontraron detalles de la compra.");
    }

    $details = $_SESSION['purchase_details'];
    unset($_SESSION['purchase_details']); // Clear session data after use
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recibo de Compra</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .receipt-container {
                max-width: 800px;
                margin: 50px auto;
                padding: 30px;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                background-color: #f8f9fa;
                font-family: Arial, sans-serif;
            }
            .receipt-header {
                text-align: center;
                margin-bottom: 20px;
            }
            .receipt-header h1 {
                font-size: 2rem;
                margin-bottom: 5px;
            }
            .receipt-header p {
                font-size: 1rem;
                color: #6c757d;
            }
            .receipt-details {
                margin-bottom: 20px;
            }
            .receipt-details p {
                margin: 0;
                font-size: 1rem;
            }
            .receipt-footer {
                text-align: center;
                margin-top: 20px;
                font-size: 0.9rem;
                color: #6c757d;
            }
            .business-info {
                margin-bottom: 20px;
                font-size: 0.9rem;
                color: #6c757d;
            }
            .business-info p {
                margin: 0;
            }
        </style>
    </head>
    <body>
        <div class="receipt-container">
            <div class="receipt-header">
                <h1>Recibo de Compra</h1>
                <p>Agencia Elmas Capitos</p>
                <p>RFC: ABC123456789</p>
                <p>Dirección: Calle Falsa 123, Ciudad, País</p>
                <p>Teléfono: +52 449 123 4567</p>
                <p>Correo Electrónico: contacto@elmascapitos.com</p>
            </div>
            <div class="business-info">
                <p><strong>Fecha de Emisión:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                <p><strong>Folio:</strong> <?php echo uniqid('REC-'); ?></p>
            </div>
            <div class="receipt-details">
                <p><strong>Cliente:</strong> <?php echo htmlspecialchars($details['client_name']); ?></p>
                <p><strong>Carro:</strong> <?php echo htmlspecialchars($details['car_name']); ?> (<?php echo $details['car_year']; ?>)</p>
                <p><strong>Precio:</strong> $<?php echo number_format($details['price'], 2); ?></p>
                <p><strong>Pago Inicial:</strong> $<?php echo number_format($details['down_payment'], 2); ?></p>
                <p><strong>Pagos Mensuales:</strong> <?php echo $details['monthly'] ? "$" . number_format($details['monthly_rate'], 2) . " por " . $details['months'] . " meses" : "No aplica"; ?></p>
                <p><strong>Empleado:</strong> <?php echo htmlspecialchars($details['employee_name']); ?></p>
            </div>
            <div class="receipt-footer">
                <p>Gracias por su compra. ¡Disfrute su nuevo carro!</p>
                <p>Este recibo es válido como comprobante de compra.</p>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
