<?php
session_start();
if (!isset($_SESSION['employee_logged_in'])) {
    header("Location: ../login.php");
    exit();
}
// Set employee_level in session if not already set (for navbar logic)
if (!isset($_SESSION['employee_level'])) {
    // Fetch from DB if needed
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "agencia";
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Error: " . $conn->connect_error);
    }
    $emp_id = $_SESSION['employee_id'];
    $result = $conn->query("SELECT level FROM employees WHERE id = $emp_id");
    if ($row = $result->fetch_assoc()) {
        $_SESSION['employee_level'] = $row['level'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include './employee_navbar.php'; ?>
    <div class="container mt-4">
        <h1 class="text-center">Bienvenido, <?php echo htmlspecialchars($_SESSION['employee_name']); ?>!</h1>
        <p class="text-center">Este es tu panel de empleado.</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>