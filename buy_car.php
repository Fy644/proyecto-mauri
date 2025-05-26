<?php
    session_start();

    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

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
    $client_id = 0; // Default client_id to 0
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT id, fullname, profile_picture FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
            $client_id = $user_data['id']; // Set client_id to the logged-in user's ID
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['buy'])) {
        // Capture submitted form data
        $client_name = $conn->real_escape_string($_POST['client_name']);
        $id_employee = intval($_POST['id_employee']);
        $id_car = intval($_POST['id_car']);
        $price = floatval($_POST['price']);
        $monthly = isset($_POST['monthly']) ? intval($_POST['monthly']) : 0; // Default to 0 if not set
        $months = isset($_POST['months']) && $_POST['months'] !== '' ? intval($_POST['months']) : 0; // Default to 0 if not set
        $card_number = $conn->real_escape_string($_POST['card_number']);
        $expiration_month = intval($_POST['expiration_month']);
        $expiration_year = intval($_POST['expiration_year']);
        $pin = intval($_POST['pin']);

        // Calculate down payment and monthly rate
        $down_payment = $price * 0.1; // Fixed 10% down payment
        $monthly_rate = $monthly ? ($price - $down_payment) / $months : 0;

        // Debugging: Log the SQL query
        $sql = "INSERT INTO sales (id_car, client, employee_id, client_id, price, down, monthly, months, card_number, expiration_month, expiration_year, pin, datetimePurchase, deleted) 
                VALUES ('$id_car', '$client_name', '$id_employee', '$client_id', '$price', '$down_payment', '$monthly', '$months', '$card_number', '$expiration_month', '$expiration_year', '$pin', NOW(), 0)";
        error_log("SQL Query: $sql");

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
            $error_message = "Error al guardar la compra: " . $conn->error;
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
        <?php include 'user_navbar.php'; ?>
        <div class="container mt-5">
            <h1 class="text-center">Comprar <?php echo htmlspecialchars($car['name']); ?></h1>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="client_name" class="form-label">Tu Nombre</label>
                    <input type="text" class="form-control" name="client_name" maxlength="50" pattern="[a-zA-Z' ]+" title="Solo se permiten letras, apóstrofes y espacios" value="<?php echo isset($user_data['fullname']) ? htmlspecialchars($user_data['fullname']) : ''; ?>" required>
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
                    <input type="number" class="form-control" name="expiration_month" min="1" max="12" placeholder="MM" required>
                    <input type="number" class="form-control" name="expiration_year" min="<?php echo date('Y'); ?>" placeholder="YYYY" required max="<?php echo date('Y') + 10; ?>">
                </div>
                <div class="mb-3">
                    <label for="pin" class="form-label">PIN de la Tarjeta</label>
                    <input type="password" class="form-control" name="pin" maxlength="4" pattern="\d{4}" title="El PIN debe tener 4 dígitos" required>
                </div>
                <input type="hidden" name="id_car" value="<?php echo $car['id']; ?>">
                <input type="hidden" name="price" value="<?php echo $car['price']; ?>">
                <button type="submit" name="buy" class="btn btn-success">Comprar Ahora</button>
                <a href="view_car.php?id=<?php echo $car['id']; ?>" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
        <script>
            function toggleMonthlyOptions() {
                const monthlyOptions = document.getElementById('monthly-options');
                const monthlyCheckbox = document.getElementById('monthly');
                monthlyOptions.style.display = monthlyCheckbox.checked ? 'block' : 'none';
                // Set or unset the monthly attribute
                if (monthlyCheckbox.checked) {
                    monthlyCheckbox.value = 1;
                } else {
                    monthlyCheckbox.value = 0;
                }
            }

            // Ensure monthly is set to 1 if months is filled, even if checkbox is not checked
            document.addEventListener('DOMContentLoaded', function () {
                const monthsInput = document.querySelector('input[name="months"]');
                const monthlyCheckbox = document.getElementById('monthly');
                if (monthsInput) {
                    monthsInput.addEventListener('input', function () {
                        if (monthsInput.value && parseInt(monthsInput.value) > 0) {
                            monthlyCheckbox.checked = true;
                            monthlyCheckbox.value = 1;
                            document.getElementById('monthly-options').style.display = 'block';
                        } else {
                            monthlyCheckbox.checked = false;
                            monthlyCheckbox.value = 0;
                            document.getElementById('monthly-options').style.display = 'none';
                        }
                    });
                }
            });
        </script>
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
