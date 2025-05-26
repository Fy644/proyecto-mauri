<?php
    session_start();

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "agencia";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Error: " . $conn->connect_error);
        exit();
    }

    $admin_error_message = null;
    $employee_error_message = null;

    // Handle admin login
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['admin_login'])) {
        $admin_username = $_POST['username'];
        $admin_password = $_POST['password'];

        $sql = "SELECT * FROM admins WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $admin_username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            if (password_verify($admin_password, $admin['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: ./admin/admin_panel.php");
                exit();
            } else {
                $admin_error_message = "Usuario o contraseña inválidos.";
            }
        } else {
            $admin_error_message = "Usuario o contraseña inválidos.";
        }
    }

    // Handle employee login
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['employee_login'])) {
        $employee_id = intval($_POST['employee_id']);
        $employee_password = $_POST['password'];

        $sql = "SELECT * FROM employees WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $employee = $result->fetch_assoc();
            if (password_verify($employee_password, $employee['password'])) {
                $_SESSION['employee_logged_in'] = true;
                $_SESSION['employee_id'] = $employee['id'];
                $_SESSION['employee_name'] = $employee['name'];
                header("Location: ./employee/employee_panel.php");
                exit();
            } else {
                $employee_error_message = "Contraseña incorrecta.";
            }
        } else {
            $employee_error_message = "Empleado no encontrado.";
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inicio de Sesión</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f8f9fa; /* Light gray background */
            }
            .navbar {
                background-color: #343a40; /* Dark gray for navbar */
            }
            .navbar-brand, .nav-link {
                color: #ffffff !important; /* White text for navbar links */
            }
            .form-container {
                max-width: 800px; /* Wider form container */
                margin: 0 auto;
                margin-top: 50px;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                background-color: #ffffff; /* White background for form */
            }
            .btn-primary {
                background-color: #007bff; /* Blue for primary buttons */
                border: none;
            }
            .btn-primary:hover {
                background-color: #0056b3; /* Darker blue on hover */
            }
            .btn-secondary {
                background-color: #6c757d; /* Gray for secondary buttons */
                border: none;
            }
            .btn-secondary:hover {
                background-color: #5a6268; /* Darker gray on hover */
            }
            .form-title {
                font-size: 1.5rem;
                margin-bottom: 20px;
                text-align: center;
            }
            .admin-login {
                position: fixed;
                bottom: 10px;
                left: 10px;
                font-size: 0.9rem;
                color: #6c757d;
                text-decoration: none;
            }
            .admin-login:hover {
                color: #343a40;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Agencia Elmas Capitos</a>
            </div>
        </nav>
        <div class="container">
            <div class="form-container">
                <h1 class="text-center">Inicio de Sesión</h1>
                <div class="row">
                    <!-- Admin Login Form -->
                    <div class="col-md-6">
                        <h2 class="form-title">Administrador</h2>
                        <?php if ($admin_error_message): ?>
                            <div class="alert alert-danger"><?php echo $admin_error_message; ?></div>
                        <?php endif; ?>
                        <form method="post" action="" id="admin_login_form">
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuario</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <button type="submit" name="admin_login" class="btn btn-primary w-100">Iniciar Sesión</button>
                        </form>
                    </div>

                    <!-- Employee Login Form -->
                    <div class="col-md-6">
                        <h2 class="form-title">Empleado</h2>
                        <?php if ($employee_error_message): ?>
                            <div class="alert alert-danger"><?php echo $employee_error_message; ?></div>
                        <?php endif; ?>
                        <form method="post" action="" id="employee_login_form">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">ID de Empleado</label>
                                <input type="number" class="form-control" name="employee_id" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <button type="submit" name="employee_login" class="btn btn-primary w-100">Iniciar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
