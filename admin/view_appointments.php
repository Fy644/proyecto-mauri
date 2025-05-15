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

    $appointments = $conn->query("
        SELECT citas.id, citas.datetime, citas.client_name, citas.phone, carros.name AS car_name, employees.name AS employee_name 
        FROM citas 
        INNER JOIN carros ON citas.id_car = carros.id 
        INNER JOIN employees ON citas.id_employee = employees.id 
        WHERE citas.datetime > NOW() AND citas.deleted = 0 
        ORDER BY citas.datetime ASC 
        LIMIT 5
    ");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>View Appointments</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <div class="content">
            <div class="container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="text-center">Upcoming Appointments</h1>
                    <a href="admin_panel.php" class="btn btn-secondary">Back</a>
                </div>
                <?php if ($appointments->num_rows > 0): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date & Time</th>
                                <th>Client Name</th>
                                <th>Phone</th>
                                <th>Car</th>
                                <th>Employee</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($appointment = $appointments->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $appointment['id']; ?></td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($appointment['datetime'])); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['client_name']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['car_name']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['employee_name']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">No upcoming appointments found.</div>
                <?php endif; ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
