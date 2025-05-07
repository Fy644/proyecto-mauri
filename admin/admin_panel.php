<?php
    session_start();
    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panel de Administración</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .admin-container {
                display: flex;
                flex-wrap: wrap; /* Allow wrapping */
                gap: 15px; /* Add spacing between items */
                justify-content: center; /* Center items horizontally */
                max-width: 800px; /* Increase the container width */
                margin: 50px auto;
            }
            .admin-box {
                flex: 0 0 calc(50% - 15px); /* Two items per row */
                padding: 30px; /* Increase padding for better spacing */
                border: 1px solid #ddd;
                border-radius: 8px;
                background-color: #f8f9fa;
                text-align: center;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .admin-box a {
                text-decoration: none;
                font-size: 1.4rem; /* Increase font size for better readability */
                color: #007bff;
            }
            .admin-box a:hover {
                text-decoration: underline;
                color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="container mt-5">
            <h1 class="text-center">¡Bienvenido, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h1>
            <div class="admin-container">
                <div class="admin-box">
                    <a href="new_car.php">Agregar Nuevo Coche</a>
                </div>
                <div class="admin-box">
                    <a href="edit_car.php">Editar Coche</a>
                </div>
                <div class="admin-box">
                    <a href="new_admin.php">Agregar Nuevo Administrador</a>
                </div>
                <div class="admin-box">
                    <a href="edit_admin.php">Editar Administrador</a>
                </div>
                <div class="admin-box">
                    <a href="new_employee.php">Agregar Nuevo Empleado</a>
                </div>
                <div class="admin-box">
                    <a href="edit_employee.php">Editar Empleado</a>
                </div>
                <div class="admin-box">
                    <a href="view_appointments.php">Ver Citas</a>
                </div>
                <div class="admin-box">
                    <a href="sales.php">Ver Ventas</a>
                </div>
                <div class="admin-box">
                    <a href="logout.php">Cerrar Sesión</a>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
