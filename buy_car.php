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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

            // Redirect to receipt page in a new tab
            echo "<script>
                    const receiptWindow = window.open('recipt.php', '_blank');
                    if (receiptWindow) {
                        receiptWindow.focus();
                    }
                    window.location.href = 'inventory.php';
                  </script>";
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
            .expiration-date {
                display: flex;
                gap: 10px;
            }
            .expiration-date input {
                width: 48%;
            }
        </style>
    </head>
    <body>
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
                    <div>
                        <label for="expiration_month" class="form-label">Mes de Expiración</label>
                        <input type="text" class="form-control" name="expiration_month" maxlength="2" pattern="0?[1-9]|1[0-2]" title="Ingresa un mes válido (1-12)" required>
                    </div>
                    <div>
                        <label for="expiration_year" class="form-label">Año de Expiración</label>
                        <input type="number" class="form-control" name="expiration_year" min="<?php echo date('Y'); ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="pin" class="form-label">PIN de la Tarjeta</label>
                    <input type="password" class="form-control" name="pin" maxlength="4" pattern="\d{4}" title="El PIN debe tener 4 dígitos" required>
                </div>
                <button type="submit" class="btn btn-success">Comprar Ahora</button>
                <a href="view_car.php?id=<?php echo $car['id']; ?>" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
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
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
