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

    require_once '../includes/validation.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = [];
        
        // Validate name (varchar(32))
        $name = trim($_POST['name']);
        if ($error = validateVarchar($name, 'nombre del empleado', 32)) {
            $errors[] = $error;
        }
        
        // Validate level (int)
        $level = $_POST['level'];
        if ($error = validateInt($level, 'nivel')) {
            $errors[] = $error;
        }
        if ($level < 0 || $level > 4) {
            $errors[] = "El nivel debe estar entre 0 y 4.";
        }
        
        // Validate phone (bigint(20))
        $phone = $_POST['phone'];
        if ($error = validatePhone($phone)) {
            $errors[] = $error;
        }
        
        // Validate RFC (varchar(13))
        $rfc = trim($_POST['rfc']);
        if ($error = validateRFC($rfc)) {
            $errors[] = $error;
        }
        
        // Validate password
        $password = $_POST['password'];
        if (strlen($password) < 8) {
            $errors[] = "La contraseña debe tener al menos 8 caracteres.";
        }
        
        if (empty($errors)) {
            // Sanitize inputs for database
            $name = $conn->real_escape_string($name);
            $phone = $conn->real_escape_string($phone);
            $rfc = $conn->real_escape_string($rfc);
            $password = password_hash($password, PASSWORD_BCRYPT);
            
            $sql = "INSERT INTO employees (name, level, phone, rfc, password, deleted) VALUES (?, ?, ?, ?, ?, 0)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sisss", $name, $level, $phone, $rfc, $password);
            
            if ($stmt->execute()) {
                $success_message = "Nuevo empleado agregado exitosamente.";
            } else {
                $error_message = "Error: " . $conn->error;
            }
        } else {
            $error_message = implode("<br>", $errors);
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agregar Nuevo Empleado</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <div class="content">
            <div class="container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="text-center">Agregar Nuevo Empleado</h1>
                    <a href="admin_panel.php" class="btn btn-secondary">Regresar</a>
                </div>
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Empleado</label>
                        <input type="text" class="form-control" name="name" maxlength="32" required>
                    </div>
                    <div class="mb-3">
                        <label for="level" class="form-label">Nivel</label>
                        <input type="number" class="form-control" name="level" min="0" max="4" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="phone" pattern="\d{10}" title="El número de teléfono debe tener exactamente 10 dígitos" maxlength="10" required>
                    </div>
                    <div class="mb-3">
                        <label for="rfc" class="form-label">RFC</label>
                        <input type="text" class="form-control" name="rfc" maxlength="13" title="El RFC debe tener un máximo de 13 caracteres" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" minlength="8" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Agregar Empleado</button>
                    </div>
                </form>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
