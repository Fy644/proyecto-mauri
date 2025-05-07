<?php
    session_start();
    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: login.php");
        exit();
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "agencia";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Error: " . $conn->connect_error);
        exit();
    }

    // Fetch the 5 most recent sales
    $sales = $conn->query("
        SELECT sales.id, sales.client, sales.price, sales.down, sales.monthly, sales.months, sales.percent, 
               carros.name AS car_name, employees.name AS employee_name 
        FROM sales 
        INNER JOIN carros ON sales.id_car = carros.id 
        INNER JOIN employees ON sales.employee_id = employees.id 
        WHERE sales.deleted = 0
        ORDER BY sales.id DESC
        LIMIT 5
    ");

    // Fetch data for Highcharts
    $car_revenue = $conn->query("
        SELECT carros.id AS car_id, carros.name AS car_name, SUM(sales.price) AS total_revenue 
        FROM sales 
        INNER JOIN carros ON sales.id_car = carros.id 
        WHERE sales.deleted = 0 
        GROUP BY carros.id, carros.name 
        ORDER BY total_revenue DESC
    ");

    $total_sales = $conn->query("
        SELECT carros.id AS car_id, carros.name AS car_name, COUNT(sales.id) AS total_sold 
        FROM sales 
        INNER JOIN carros ON sales.id_car = carros.id 
        WHERE sales.deleted = 0 
        GROUP BY carros.id, carros.name 
        ORDER BY total_sold DESC
    ");

    // Fetch data for Employee Sales chart
    $employee_sales = $conn->query("
        SELECT employees.name AS employee_name, COUNT(sales.id) AS cars_sold 
        FROM sales 
        INNER JOIN employees ON sales.employee_id = employees.id 
        WHERE sales.deleted = 0 
        GROUP BY employees.name 
        ORDER BY cars_sold DESC
    ");
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Ventas</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.highcharts.com/highcharts.js"></script>
    </head>
    <body>
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="text-center">Ventas</h1>
                <a href="admin_panel.php" class="btn btn-secondary">Regresar</a>
            </div>
            <h2>Ventas Recientes</h2>
            <?php if ($sales->num_rows > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Coche</th>
                            <th>Empleado</th>
                            <th>Precio</th>
                            <th>Pago Inicial</th>
                            <th>Pago Mensual</th>
                            <th>Meses</th>
                            <th>Tasa de Interés</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($sale = $sales->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $sale['id']; ?></td>
                                <td><?php echo htmlspecialchars($sale['client']); ?></td>
                                <td><?php echo htmlspecialchars($sale['car_name']); ?></td>
                                <td><?php echo htmlspecialchars($sale['employee_name']); ?></td>
                                <td>$<?php echo number_format($sale['price'], 2); ?></td>
                                <td>
                                    <?php 
                                        echo $sale['down'] > 0 ? "$" . number_format($sale['down'], 2) : "N/A"; 
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                        if ($sale['months'] > 0) {
                                            echo "$" . number_format($sale['price'] / $sale['months'], 2);
                                        } else {
                                            echo "N/A";
                                        }
                                    ?>
                                </td>
                                <td><?php echo $sale['months'] > 0 ? $sale['months'] : "N/A"; ?></td>
                                <td><?php echo $sale['percent'] * 100; ?>%</td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info">No se encontraron ventas.</div>
            <?php endif; ?>

            <h2>Métricas</h2>
            <div id="car-revenue" class="mb-4"></div>
            <div id="total-sales" class="mb-4"></div>
            <div id="employee-sales" class="mb-4"></div>
        </div>

        <script>
            // Prepare data for Revenue by Car chart
            const carRevenueData = [
                <?php while ($car = $car_revenue->fetch_assoc()): ?>
                    {
                        name: '<?php echo $car['car_name']; ?>',
                        y: parseFloat(<?php echo $car['total_revenue']; ?>),
                        car_id: <?php echo $car['car_id']; ?>
                    },
                <?php endwhile; ?>
            ];

            // Highcharts: Revenue by Car
            Highcharts.chart('car-revenue', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Ingresos por Coche'
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: 'Ingresos Totales ($)'
                    }
                },
                tooltip: {
                    pointFormat: 'Ingresos: <b>${point.y:.2f}</b><br>ID del Coche: <b>{point.car_id}</b>'
                },
                series: [{
                    name: 'Ingresos',
                    data: carRevenueData,
                    keys: ['name', 'y', 'car_id']
                }]
            });

            // Prepare data for Total Cars Sold chart
            const totalSalesData = [
                <?php while ($car = $total_sales->fetch_assoc()): ?>
                    {
                        name: '<?php echo $car['car_name']; ?>',
                        y: parseFloat(<?php echo $car['total_sold']; ?>),
                        car_id: <?php echo $car['car_id']; ?>
                    },
                <?php endwhile; ?>
            ];

            // Highcharts: Total Cars Sold
            Highcharts.chart('total-sales', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Total de Coches Vendidos'
                },
                xAxis: {
                    type: 'category'
                },
                yAxis: {
                    title: {
                        text: 'Número de Coches Vendidos'
                    }
                },
                tooltip: {
                    pointFormat: 'Coches Vendidos: <b>{point.y}</b><br>ID del Coche: <b>{point.car_id}</b>'
                },
                series: [{
                    name: 'Coches Vendidos',
                    data: totalSalesData,
                    keys: ['name', 'y', 'car_id']
                }]
            });

            // Prepare data for Employee Sales chart
            const employeeSalesData = [
                <?php while ($employee = $employee_sales->fetch_assoc()): ?>
                    {
                        name: '<?php echo $employee['employee_name']; ?>',
                        y: parseFloat(<?php echo $employee['cars_sold']; ?>)
                    },
                <?php endwhile; ?>
            ];

            // Highcharts: Employee Sales
            Highcharts.chart('employee-sales', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Coches Vendidos por Empleados'
                },
                tooltip: {
                    pointFormat: '<b>{point.name}</b>: {point.y} coches vendidos'
                },
                series: [{
                    name: 'Coches Vendidos',
                    colorByPoint: true,
                    data: employeeSalesData
                }]
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
