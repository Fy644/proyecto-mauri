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

    // Fetch available cars for the dropdown
    $cars = $conn->query("SELECT id, name FROM carros WHERE deleted = 0");

    // Fetch available employees for the dropdown
    $employees = $conn->query("SELECT id, name FROM employees WHERE deleted = 0");

    $login_error = null;
    $show_login_popup = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
        $user_username = $_POST['username'];
        $user_password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($user_password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: new_appointment.php");
                exit();
            } else {
                $login_error = "Contraseña incorrecta.";
                $show_login_popup = true;
            }
        } else {
            $login_error = "Usuario no encontrado.";
            $show_login_popup = true;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
        session_destroy();
        header("Location: new_appointment.php");
        exit();
    }

    // Fetch user data if logged in
    $user_data = [];
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT profile_picture FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_appointment'])) {
        $datetime = date('Y-m-d H:00:00', strtotime($_POST['datetime'])); // Round to the nearest hour
        $client_name = substr($_POST['client_name'], 0, 50); // Limit to 50 characters
        $phone = substr($_POST['phone'], 0, 20); // Limit to 20 digits
        $id_car = $_POST['id_car'];
        $id_employee = $_POST['id_employee'];

        $hour = (int)date('H', strtotime($datetime));
        if (strtotime($datetime) > time() && $hour >= 10 && $hour <= 16) { // Ensure the date is in the future and time is between 10 AM and 4 PM
            $sql = "INSERT INTO citas (datetime, client_name, phone, id_car, id_employee, deleted) 
                    VALUES ('$datetime', '$client_name', '$phone', '$id_car', '$id_employee', 0)";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Cita creada con éxito.";
            } else {
                $error_message = "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $error_message = "La fecha de la cita debe ser en el futuro, y la hora debe estar entre las 10 AM y las 4 PM.";
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Crear Cita</title>
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
            .user-login-icon {
                width: 32px;
                height: 32px;
                cursor: pointer;
            }
            .user-login-form {
                display: none;
                position: fixed;
                top: 50px;
                right: 20px;
                background: white;
                border: 1px solid #ddd;
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                z-index: 1000;
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
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="inventory.php">Inventario</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="new_appointment.php">Prueba de coche</a>
                        </li>
                    </ul>
                </div>
                <img src="<?php echo isset($_SESSION['user_id']) && !empty($user_data['profile_picture']) ? htmlspecialchars($user_data['profile_picture']) : 'Untitled.svg'; ?>" 
                     alt="User Login" class="user-login-icon" onclick="toggleUserLogin()">
                <div class="user-login-form" id="userLoginForm">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="text-center mb-3">
                            <img src="<?php echo htmlspecialchars($user_data['profile_picture'] ?? 'Untitled.svg'); ?>" 
                                 alt="Profile Picture" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <p class="text-center">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                        <a href="user_settings.php" class="btn btn-primary mb-2">User Settings</a>
                        <form method="post" action="">
                            <button type="submit" name="logout" class="btn btn-danger">Log Out</button>
                        </form>
                    <?php else: ?>
                        <div class="text-center mb-3">
                            <img src="Untitled.svg" 
                                 alt="Default Profile Picture" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <?php if ($login_error): ?>
                            <div class="alert alert-danger"><?php echo $login_error; ?></div>
                        <?php endif; ?>
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuario</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary">Iniciar Sesión</button>
                            <a href="register.php" class="btn btn-secondary">Registrarse</a>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <div class="container mt-5">
            <h1 class="text-center">Crear Cita</h1>
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <input type="hidden" name="create_appointment" value="1">
                <div class="mb-3">
                    <label for="datetime" class="form-label">Fecha y Hora de la Cita</label>
                    <input type="datetime-local" class="form-control" name="datetime" id="datetime" min="<?php echo date('Y-m-d\TH:00'); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="client_name" class="form-label">Tu Nombre</label>
                    <input type="text" class="form-control" name="client_name" maxlength="50" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="phone" pattern="\d{1,20}" title="El número de teléfono debe tener hasta 20 dígitos" required>
                </div>
                <div class="mb-3">
                    <label for="id_car" class="form-label">Selecciona un Coche</label>
                    <select name="id_car" class="form-select" required>
                        <option value="">-- Selecciona un Coche --</option>
                        <?php while ($row = $cars->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="id_employee" class="form-label">Selecciona un Empleado</label>
                    <select name="id_employee" class="form-select" required>
                        <option value="">-- Selecciona un Empleado --</option>
                        <?php while ($row = $employees->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Crear Cita</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
                <a href="admin/login.php" class="admin-login">Admin Login</a>

        <script>
            function toggleUserLogin() {
                const form = document.getElementById('userLoginForm');
                form.style.display = form.style.display === 'block' ? 'none' : 'block';
            }

            // Automatically show login popup if login error exists
            <?php if ($show_login_popup): ?>
                document.addEventListener('DOMContentLoaded', function () {
                    toggleUserLogin();
                });
            <?php endif; ?>
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
