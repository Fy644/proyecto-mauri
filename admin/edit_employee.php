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

    // Fetch all employees for the dropdown menu
    $employees = $conn->query("SELECT id, name FROM employees WHERE deleted = 0");

    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['employee_id'])) {
        $employee_id = intval($_GET['employee_id']);
        $employee = $conn->query("SELECT * FROM employees WHERE id = $employee_id")->fetch_assoc();

        if (!$employee) {
            die("Error: Empleado no encontrado.");
        }
    }

    require_once '../includes/validation.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = [];
        
        if (isset($_POST['delete'])) {
            $employee_id = intval($_POST['employee_id']);
            $sql = "UPDATE employees SET deleted = 1 WHERE id = $employee_id";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Empleado eliminado exitosamente.";
                unset($employee); // Remove the employee data
            } else {
                $error_message = "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
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
            
            // Validate password if provided
            $password = !empty($_POST['password']) ? $_POST['password'] : null;
            if ($password && strlen($password) < 8) {
                $errors[] = "La contraseña debe tener al menos 8 caracteres.";
            }
            
            if (empty($errors)) {
                // Sanitize inputs for database
                $name = $conn->real_escape_string($name);
                $phone = $conn->real_escape_string($phone);
                $rfc = $conn->real_escape_string($rfc);
                $password = $password ? password_hash($password, PASSWORD_BCRYPT) : null;
                
                $sql = "UPDATE employees SET 
                        name = '$name', 
                        level = '$level',
                        phone = '$phone',
                        rfc = '$rfc'" . ($password ? ", password = '$password'" : "") . "
                        WHERE id = " . intval($_POST['employee_id']);

                if ($conn->query($sql) === TRUE) {
                    $success_message = "Empleado actualizado exitosamente.";
                    $employee = $conn->query("SELECT * FROM employees WHERE id = " . intval($_POST['employee_id']))->fetch_assoc();
                } else {
                    $error_message = "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                $error_message = implode("<br>", $errors);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Empleado</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <div class="content">
            <div class="container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="text-center">Editar Empleado</h1>
                    <a href="admin_panel.php" class="btn btn-secondary">Regresar</a>
                </div>
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <!-- Dropdown to select an employee -->
                <form method="get" action="">
                    <div class="mb-3">
                        <label for="employee_id" class="form-label">Selecciona un Empleado para Editar</label>
                        <select name="employee_id" class="form-select" onchange="this.form.submit()" required>
                            <option value="">-- Selecciona un Empleado --</option>
                            <?php while ($row = $employees->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>" <?php echo (isset($employee_id) && $employee_id == $row['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($row['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </form>

                <?php if (isset($employee)): ?>
                    <!-- Form to edit the selected employee -->
                    <form method="post" action="">
                        <input type="hidden" name="employee_id" value="<?php echo $employee['id']; ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Empleado</label>
                            <input type="text" class="form-control" name="name" maxlength="32" value="<?php echo htmlspecialchars($employee['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="level" class="form-label">Nivel</label>
                            <input type="number" class="form-control" name="level" min="0" max="4" value="<?php echo htmlspecialchars($employee['level']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" name="phone" pattern="\d{10}" title="El número de teléfono debe tener exactamente 10 dígitos" maxlength="10" value="<?php echo htmlspecialchars($employee['phone']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="rfc" class="form-label">RFC</label>
                            <input type="text" class="form-control" name="rfc" maxlength="13" title="El RFC debe tener un máximo de 13 caracteres" value="<?php echo htmlspecialchars($employee['rfc']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña (opcional)</label>
                            <input type="password" class="form-control" name="password" minlength="8">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Actualizar Empleado</button>
                            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este empleado?');">Eliminar Empleado</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
