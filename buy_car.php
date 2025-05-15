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

    if (!isset($_GET['id'])) {
        die("Error: No se proporcionó el ID del coche.");
    }

    $car_id = intval($_GET['id']);
    $car = $conn->query("SELECT * FROM carros WHERE id = $car_id AND deleted = 0")->fetch_assoc();

    if (!$car) {
        die("Error: Coche no encontrado.");
    }

    // Fetch available employees for the dropdown
    $employees = $conn->query("SELECT id, name FROM employees WHERE deleted = 0");

    $login_error = null;
    $show_login_popup = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
        $user_username = $_POST['username'];
        $user_password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Error: " . $conn->error);
        }
        $stmt->bind_param("s", $user_username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($user_password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: buy_car.php?id=$car_id");
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
        header("Location: buy_car.php?id=$car_id");
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

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buy'])) {
        $client_name = $conn->real_escape_string(preg_replace("/[^a-zA-Z' ]/", '', substr($_POST['client_name'], 0, 50))); // Escape input to handle apostrophes
        $credit_score = intval($_POST['credit_score']);
        $id_employee = intval($_POST['id_employee']);
        $monthly = isset($_POST['monthly']) ? 1 : 0;
        $months = $monthly ? intval($_POST['months']) : 0;
        $card_number = $_POST['card_number'];
        $expiration_month = intval(ltrim($_POST['expiration_month'], '0')); // Remove leading zeros
        $expiration_year = intval($_POST['expiration_year']);
        $pin = intval($_POST['pin']); // Ensure PIN is numeric

        // Calculate down payment, interest, and monthly rate
        $price = $car['price'];
        $down_payment = $credit_score >= 700 ? $price * 0.1 : ($credit_score >= 600 ? $price * 0.2 : $price * 0.3);
        $interest_rate = $credit_score >= 700 ? 0.05 : ($credit_score >= 600 ? 0.1 : 0.15);
        $monthly_rate = $monthly ? (($price - $down_payment) * (1 + $interest_rate)) / $months : 0;

        $sql = "INSERT INTO sales (id_car, client, employee_id, price, percent, down, monthly, months, card_number, expiration_month, expiration_year, pin, deleted) 
                VALUES ('$car_id', '$client_name', '$id_employee', '$price', '$interest_rate', '$down_payment', '$monthly_rate', '$months', '$card_number', '$expiration_month', '$expiration_year', '$pin', 0)";

        if ($conn->query($sql) === TRUE) {
            // Fetch employee name
            $employee_name = $conn->query("SELECT name FROM employees WHERE id = $id_employee")->fetch_assoc()['name'];

            // Store purchase details in session
            $_SESSION['purchase_details'] = [
                'client_name' => $client_name,
                'car_name' => $car['name'],
                'car_year' => $car['year'],
                'price' => $price,
                'down_payment' => $down_payment,
                'monthly' => $monthly,
                'monthly_rate' => $monthly_rate,
                'months' => $months,
                'employee_name' => $employee_name
            ];

            // Redirect to receipt page
            header("Location: recipt.php");
            exit();
        } else {
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Comprar <?php echo htmlspecialchars($car['name']); ?></title>
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
            .btn-success {
                background-color: #007bff; /* Blue for buttons */
                border: none;
            }
            .btn-success:hover {
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
            .expiration-date {
                display: flex;
                gap: 10px;
                align-items: center;
            }
            .expiration-date input {
                width: 100px; /* Wider input boxes */
                text-align: center;
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
            <h1 class="text-center">Comprar <?php echo htmlspecialchars($car['name']); ?></h1>
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="post" action="" id="buyCarForm">
                <div class="mb-3">
                    <label for="client_name" class="form-label">Tu Nombre</label>
                    <input type="text" class="form-control" name="client_name" maxlength="50" pattern="[a-zA-Z' ]+" title="Solo se permiten letras, apóstrofes y espacios" required>
                </div>
                <div class="mb-3">
                    <label for="credit_score" class="form-label">Puntaje de Crédito</label>
                    <input type="number" class="form-control" name="credit_score" min="300" max="850" required>
                </div>
                <div class="mb-3">
                    <label for="id_employee" class="form-label">Empleado que te atendió</label>
                    <select name="id_employee" class="form-select" required>
                        <option value="">-- Selecciona un Empleado --</option>
                        <?php while ($row = $employees->fetch_assoc()): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="monthly" class="form-label">¿Deseas pagos mensuales?</label>
                    <input type="checkbox" name="monthly" id="monthly" onchange="toggleMonthlyOptions()">
                </div>
                <div id="monthly-options" style="display: none;">
                    <div class="mb-3">
                        <label for="months" class="form-label">Número de Meses</label>
                        <input type="number" class="form-control" name="months" min="1">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="card_number" class="form-label">Número de Tarjeta</label>
                    <input type="number" class="form-control" name="card_number" id="card_number" required>
                </div>
                <div class="mb-3 expiration-date">
                    <label for="expiration_month" class="form-label">Expiración:</label>
                    <input type="text" class="form-control" name="expiration_month" maxlength="2" pattern="0?[1-9]|1[0-2]" title="Mes (1-12)" placeholder="MM" required>
                    <input type="number" class="form-control" name="expiration_year" min="<?php echo date('Y'); ?>" placeholder="YYYY" required>
                </div>
                <div class="mb-3">
                    <label for="pin" class="form-label">PIN de la Tarjeta</label>
                    <input type="password" class="form-control" name="pin" maxlength="4" pattern="\d{4}" title="El PIN debe tener 4 dígitos" required>
                </div>
                <button type="submit" class="btn btn-success">Comprar Ahora</button>
                <a href="view_car.php?id=<?php echo $car['id']; ?>" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
        <a href="admin/login.php" class="admin-login">Admin Login</a>
        <script>
            function toggleMonthlyOptions() {
                const monthlyOptions = document.getElementById('monthly-options');
                const monthlyCheckbox = document.getElementById('monthly');
                monthlyOptions.style.display = monthlyCheckbox.checked ? 'block' : 'none';
            }

            // Ensure card number input has exactly 16 digits before submitting
            document.getElementById('buyCarForm').addEventListener('submit', function (event) {
                const cardNumberInput = document.getElementById('card_number');
                if (cardNumberInput.value.length !== 16) {
                    alert("El número de tarjeta debe tener exactamente 16 dígitos.");
                    event.preventDefault();
                }
            });

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
