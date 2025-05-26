<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
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

$user_id = $_SESSION['user_id'];
$success = null;
$error = null;

// Fetch user data for navbar
$user_data = [];
$sql = "SELECT profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
}

// Fetch cars purchased by the user for dropdown
$cars = [];
$purchased_query = $conn->prepare("
    SELECT sales.id AS sale_id, carros.name, carros.year
    FROM sales
    INNER JOIN carros ON sales.id_car = carros.id
    WHERE sales.client_id = ? AND sales.deleted = 0
");
$purchased_query->bind_param("i", $user_id);
$purchased_query->execute();
$purchased_result = $purchased_query->get_result();
while ($row = $purchased_result->fetch_assoc()) {
    $cars[] = $row;
}

// Fetch employees with level >= 4 for dropdown
$employees = [];
$emp_query = $conn->query("SELECT id, name FROM employees WHERE deleted = 0 AND level >= 4");
while ($row = $emp_query->fetch_assoc()) {
    $employees[] = $row;
}

// Handle logout before any other POST logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['logout'])) {
    $car_id = isset($_POST['car_id']) ? intval($_POST['car_id']) : null;
    $problem = isset($_POST['problem']) ? trim($_POST['problem']) : null;
    $selected_employee_id = isset($_POST['employee_id']) && $_POST['employee_id'] !== '' ? intval($_POST['employee_id']) : null;

    if ($car_id && $problem) {
        if ($selected_employee_id) {
            $employee_id = $selected_employee_id;
        } else {
            $emp_res = $conn->query("SELECT id FROM employees WHERE deleted = 0 AND level >= 4 ORDER BY RAND() LIMIT 1");
            $emp = $emp_res->fetch_assoc();
            $employee_id = $emp ? $emp['id'] : 1;
        }

        $date_request = date('Y-m-d');
        $problem_escaped = $conn->real_escape_string($problem);

        // Insert all columns, including date_finish and date_pickup, as NULL
        $sql = "INSERT INTO service (id_employee, date_request, date_finish, date_pickup, id_user, id_car, problem, deleted) 
                VALUES ($employee_id, '$date_request', NULL, NULL, $user_id, $car_id, '$problem_escaped', 0)";

        // Debug: Show the SQL and error if any
        if ($conn->query($sql) === TRUE) {
            $success = "¡Solicitud de servicio enviada exitosamente!";
        } else {
            $error = "Error al enviar la solicitud: " . htmlspecialchars($conn->error) . "<br>SQL: $sql";
        }
    } else {
        $error = "Por favor, completa todos los campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Solicitar Servicio</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f8f9fa;
            }
            .card {
                background-color: #ffffff;
                border: none;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            .navbar {
                background-color: #343a40;
            }
            .navbar-brand, .nav-link {
                color: #ffffff !important;
            }
            .btn-primary {
                background-color: #007bff;
                border: none;
            }
            .btn-primary:hover {
                background-color: #0056b3;
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
            <h2>Solicitar Servicio para tu Coche</h2>
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label for="car_id" class="form-label">Selecciona tu coche</label>
                    <select class="form-select" name="car_id" required>
                        <option value="">-- Selecciona --</option>
                        <?php foreach ($cars as $car): ?>
                            <option value="<?php echo $car['sale_id']; ?>">
                                <?php echo htmlspecialchars($car['name'] . " (" . $car['year'] . ")"); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="employee_id" class="form-label">Selecciona empleado (opcional)</label>
                    <select class="form-select" name="employee_id">
                        <option value="">-- Asignar aleatoriamente --</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?php echo $emp['id']; ?>">
                                <?php echo htmlspecialchars($emp['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="form-text">Si no seleccionas un empleado, se asignará uno aleatoriamente.</div>
                </div>
                <div class="mb-3">
                    <label for="problem" class="form-label">Describe el problema</label>
                    <textarea class="form-control" name="problem" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
                <a href="index.php" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
        <a href="login.php" class="admin-login">Admin Login</a>
        <script>
            function toggleUserLogin() {
                const form = document.getElementById('userLoginForm');
                form.style.display = form.style.display === 'block' ? 'none' : 'block';
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
