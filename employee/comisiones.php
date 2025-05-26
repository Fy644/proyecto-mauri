<?php
session_start();
if (!isset($_SESSION['employee_logged_in'])) {
    header("Location: ../login.php");
    exit();
}

$employee_id = $_SESSION['employee_id'];

$servername = "localhost";
$username = "root";
$password = "";
$database = "agencia";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error: " . $conn->connect_error);
}

// Fetch all sales for this employee, grouped by year and month
$sql = "
    SELECT 
        sales.id,
        sales.price,
        sales.datetimePurchase,
        carros.name AS car_name,
        YEAR(sales.datetimePurchase) AS year,
        MONTH(sales.datetimePurchase) AS month
    FROM sales
    INNER JOIN carros ON sales.id_car = carros.id
    WHERE sales.employee_id = ? AND sales.deleted = 0
    ORDER BY sales.datetimePurchase DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

$commissions = [];
while ($row = $result->fetch_assoc()) {
    $year = $row['year'];
    $month = $row['month'];
    $commission = $row['price'] * 0.005;
    $commissions[$year][$month][] = [
        'car_name' => $row['car_name'],
        'price' => $row['price'],
        'commission' => $commission,
        'date' => $row['datetimePurchase']
    ];
}
$stmt->close();

function month_name($m) {
    $months = [
        1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril",
        5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto",
        9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
    ];
    return $months[intval($m)] ?? $m;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comisiones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include './employee_navbar.php'; ?>
    <div class="content">
        <h1 class="text-center">Comisiones de <?php echo htmlspecialchars($_SESSION['employee_name']); ?></h1>
        <?php if (empty($commissions)): ?>
            <div class="alert alert-info text-center">No hay ventas registradas para calcular comisiones.</div>
        <?php else: ?>
            <div class="accordion" id="accordionComisiones">
            <?php $yearIdx = 0; ?>
            <?php foreach ($commissions as $year => $months): ?>
                <?php
                    // Calculate total commission for the year
                    $year_total = 0;
                    foreach ($months as $sales) {
                        foreach ($sales as $sale) {
                            $year_total += $sale['commission'];
                        }
                    }
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading<?php echo $year; ?>">
                        <button class="accordion-button <?php echo $yearIdx > 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $year; ?>" aria-expanded="<?php echo $yearIdx === 0 ? 'true' : 'false'; ?>" aria-controls="collapse<?php echo $year; ?>">
                            <?php echo $year; ?> &mdash; Total: $<?php echo number_format($year_total, 2); ?>
                        </button>
                    </h2>
                    <div id="collapse<?php echo $year; ?>" class="accordion-collapse collapse <?php echo $yearIdx === 0 ? 'show' : ''; ?>" aria-labelledby="heading<?php echo $year; ?>" data-bs-parent="#accordionComisiones">
                        <div class="accordion-body">
                            <?php foreach ($months as $month => $sales): ?>
                                <div class="card mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <?php echo month_name($month) . " $year"; ?>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Coche</th>
                                                    <th>Precio de Venta</th>
                                                    <th>Comisión (0.5%)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $sum = 0; ?>
                                                <?php foreach ($sales as $sale): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($sale['date']))); ?></td>
                                                        <td><?php echo htmlspecialchars($sale['car_name']); ?></td>
                                                        <td>$<?php echo number_format($sale['price'], 2); ?></td>
                                                        <td>$<?php echo number_format($sale['commission'], 2); ?></td>
                                                    </tr>
                                                    <?php $sum += $sale['commission']; ?>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer text-end fw-bold">
                                        Total Comisiones <?php echo month_name($month) . " $year"; ?>: $<?php echo number_format($sum, 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php $yearIdx++; ?>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
