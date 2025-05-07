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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $admin_username = $_POST['username'];
        $admin_password = $_POST['password'];

        $sql = "SELECT * FROM admins WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $admin_username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            if (password_verify($admin_password, $admin['password'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: admin_panel.php");
                exit();
            } else {
                $error_message = "Usuario o contraseña inválidos.";
            }
        } else {
            $error_message = "Usuario o contraseña inválidos.";
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inicio de Sesión - Administrador</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .form-container {
                max-width: 400px;
                margin: 0 auto;
                margin-top: 100px;
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .form-label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            .form-control {
                width: 100%;
            }
            #password-container {
                display: flex;
                align-items: center;
                gap: 5px;
            }
            #password {
                flex: 1;
            }
            .button-container {
                display: flex;
                justify-content: space-between;
                gap: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="form-container">
                <h1 class="text-center">Inicio de Sesión</h1>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div id="password-container">
                            <input type="password" class="form-control" name="password" id="password" required>
                            <button type="button" id="show-password-btn" class="btn btn-outline-secondary btn-sm" 
                                onmousedown="document.getElementById('password').type='text'" 
                                onmouseup="document.getElementById('password').type='password'" 
                                onmouseleave="document.getElementById('password').type='password'">
                                Mostrar
                            </button>
                        </div>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="btn btn-primary w-50">Iniciar Sesión</button>
                        <a href="../index.php" class="btn btn-secondary w-50">Regresar</a>
                    </div>
                </form>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
