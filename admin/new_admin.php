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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = substr($_POST['username'], 0, 50); // Limit username to 50 characters
        $password = substr($_POST['password'], 0, 255); // Limit password to 255 characters
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO admins (username, password) VALUES ('$username', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Nuevo administrador agregado exitosamente.";
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
        <title>Agregar Nuevo Administrador</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="text-center">Agregar Nuevo Administrador</h1>
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
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" class="form-control" name="username" maxlength="50" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" maxlength="255" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Agregar Administrador</button>
                </div>
            </form>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
