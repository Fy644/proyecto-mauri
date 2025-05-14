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

    $login_error = null;
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
                header("Location: index.php");
                exit();
            } else {
                $login_error = "Contraseña incorrecta.";
            }
        } else {
            $login_error = "Usuario no encontrado.";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
        $user_username = $_POST['username'];
        $user_password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $user_email = $_POST['email'];
        $user_fullname = $_POST['fullname'];

        // Handle profile picture upload
        $targetDir = "userpfp/";
        $fileInfo = pathinfo($_FILES["profile_picture"]["name"]);
        $profile_picture_name = uniqid(); // Generate a unique filename
        $fileExtension = strtolower($fileInfo['extension']);
        $targetFile = $targetDir . $profile_picture_name . ".png";

        if (!is_writable($targetDir)) {
            $error_message = "The profile picture folder is not writable. Please check folder permissions.";
        } else {
            if ($_FILES["profile_picture"]["error"] === UPLOAD_ERR_OK) {
                if ($fileExtension === "png") {
                    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
                        $sql = "INSERT INTO users (username, password, email, fullname, profile_picture) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sssss", $user_username, $user_password, $user_email, $user_fullname, $targetFile);

                        if ($stmt->execute()) {
                            header("Location: index.php");
                            exit();
                        } else {
                            $error_message = "Error al registrar el usuario.";
                        }
                    } else {
                        $error_message = "Error al mover el archivo subido. Verifique los permisos de la carpeta.";
                    }
                } else {
                    $error_message = "Solo se permiten archivos PNG para la foto de perfil.";
                }
            } else {
                $error_message = "Error al subir el archivo: " . $_FILES["profile_picture"]["error"];
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro de Usuario</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .user-login-form {
                display: none;
                position: absolute;
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
                bottom: 20px;
                right: 20px;
                cursor: pointer;
                opacity: 0;
            }
            .admin-login:hover {
                opacity: 1;
            }
        </style>
    </head>
    <body>
        <div class="container mt-5">
            <h1 class="text-center">Registro de Usuario</h1>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="fullname" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" name="fullname" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Foto de Perfil (solo PNG)</label>
                    <input type="file" class="form-control" name="profile_picture" accept="image/png" required>
                </div>
                <button type="submit" name="register" class="btn btn-primary">Registrarse</button>
            </form>
            <hr>
        <div class="user-login-form" id="userLoginForm">
            <form method="post" action="user_login.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Usuario</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                <a href="register.php" class="btn btn-secondary">Registrarse</a>
            </form>
        </div>
        <img src="Untitled.svg" alt="Admin Login" class="admin-login" onclick="window.location.href='admin/login.php'">
        <script>
            function toggleUserLogin() {
                const form = document.getElementById('userLoginForm');
                form.style.display = form.style.display === 'block' ? 'none' : 'block';
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
