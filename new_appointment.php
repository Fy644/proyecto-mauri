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
        $client_name = trim($_POST['client_name']);
        $phone = trim($_POST['phone']);
        $id_car = intval($_POST['id_car']);
        $id_employee = intval($_POST['id_employee']);

        // Validate client name (max 50 chars, only letters, spaces and apostrophes)
        if (!preg_match('/^[a-zA-Z\' ]{1,50}$/', $client_name)) {
            $error_message = "El nombre debe contener solo letras, espacios y apóstrofes, y tener máximo 50 caracteres.";
        }
        // Validate phone (exactly 10 digits)
        elseif (!preg_match('/^\d{10}$/', $phone)) {
            $error_message = "El número de teléfono debe tener exactamente 10 dígitos.";
        }
        // Validate car and employee IDs
        elseif ($id_car <= 0 || $id_employee <= 0) {
            $error_message = "Por favor selecciona un coche y un empleado válidos.";
        }
        else {
            $hour = (int)date('H', strtotime($datetime));
            if (strtotime($datetime) > time() && $hour >= 10 && $hour <= 16) { // Ensure the date is in the future and time is between 10 AM and 4 PM
                // Check if the car exists and is not deleted
                $car_check = $conn->prepare("SELECT id FROM carros WHERE id = ? AND deleted = 0");
                $car_check->bind_param("i", $id_car);
                $car_check->execute();
                if ($car_check->get_result()->num_rows === 0) {
                    $error_message = "El coche seleccionado no está disponible.";
                } else {
                    // Check if the employee exists and is not deleted
                    $emp_check = $conn->prepare("SELECT id FROM employees WHERE id = ? AND deleted = 0");
                    $emp_check->bind_param("i", $id_employee);
                    $emp_check->execute();
                    if ($emp_check->get_result()->num_rows === 0) {
                        $error_message = "El empleado seleccionado no está disponible.";
                    } else {
                        // Check if there's already an appointment at this time
                        $time_check = $conn->prepare("SELECT id FROM citas WHERE datetime = ? AND deleted = 0");
                        $time_check->bind_param("s", $datetime);
                        $time_check->execute();
                        if ($time_check->get_result()->num_rows > 0) {
                            $error_message = "Ya existe una cita programada para esta fecha y hora.";
                        } else {
                            // Insert the appointment
                            $stmt = $conn->prepare("INSERT INTO citas (datetime, client_name, phone, id_car, id_employee, deleted) VALUES (?, ?, ?, ?, ?, 0)");
                            $stmt->bind_param("sssii", $datetime, $client_name, $phone, $id_car, $id_employee);
                            if ($stmt->execute()) {
                                // Show the registered time in the success message
                                $success_message = "Cita creada con éxito. Hora registrada: " . date('d/m/Y H:i', strtotime($datetime));
                            } else {
                                $error_message = "Error al crear la cita: " . htmlspecialchars($conn->error);
                            }
                            $stmt->close();
                        }
                        $time_check->close();
                    }
                    $emp_check->close();
                }
                $car_check->close();
            } else {
                $error_message = "La fecha de la cita debe ser en el futuro, y la hora debe estar entre las 10 AM y las 4 PM.";
            }
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
        <?php include 'user_navbar.php'; ?>
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
                    <input type="datetime-local" class="form-control" name="datetime" id="datetime"
                        min="<?php
                            // Round down to the nearest hour for min attribute
                            $now = time();
                            $rounded = strtotime(date('Y-m-d H:00:00', $now));
                            echo date('Y-m-d\TH:00', $rounded);
                        ?>"
                        value="<?php
                            // Set default value rounded down to the nearest hour
                            $now = time();
                            $rounded = strtotime(date('Y-m-d H:00:00', $now));
                            echo date('Y-m-d\TH:00', $rounded);
                        ?>"
                        required>
                </div>
                <div class="mb-3">
                    <label for="client_name" class="form-label">Tu Nombre</label>
                    <input type="text" class="form-control" name="client_name" maxlength="50" pattern="[a-zA-Z' ]+" title="Solo se permiten letras, apóstrofes y espacios (máximo 50 caracteres)"
                        value="<?php
                            if (isset($_SESSION['user_id'])) {
                                $uid = intval($_SESSION['user_id']);
                                $res = $conn->query("SELECT fullname FROM users WHERE id = $uid");
                                if ($res && $row = $res->fetch_assoc()) {
                                    echo htmlspecialchars($row['fullname']);
                                }
                            }
                        ?>" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" name="phone" pattern="\d{10}" title="El número de teléfono debe tener exactamente 10 dígitos" required>
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
        <a href="login.php" class="admin-login">Admin Login</a>
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
