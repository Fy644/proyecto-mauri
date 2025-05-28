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
        
        // Validate username (varchar(32))
        $username = trim($_POST['username']);
        if ($error = validateVarchar($username, 'nombre de usuario', 32)) {
            $errors[] = $error;
        }
        
        // Validate password
        $password = $_POST['password'];
        if (strlen($password) < 8) {
            $errors[] = "La contraseña debe tener al menos 8 caracteres.";
        }
        
        if (empty($errors)) {
            // Sanitize inputs for database
            $username = $conn->real_escape_string($username);
            $password = password_hash($password, PASSWORD_BCRYPT);
            
            $sql = "INSERT INTO admins (username, password) VALUES ('$username', '$password')";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Administrador creado exitosamente.";
                // Clear form
                $_POST = array();
            } else {
                $error_message = "Error: " . $sql . "<br>" . $conn->error;
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
        <title>Agregar Nuevo Administrador</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <div class="content">
            <div class="container mt-4">
                <h1 class="text-center">Agregar Nuevo Administrador</h1>
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Nombre de Usuario</label>
                        <input type="text" class="form-control" name="username" maxlength="32" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" minlength="8" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Agregar Administrador</button>
                    </div>
                </form>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
