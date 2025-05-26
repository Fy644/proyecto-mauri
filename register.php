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
                header("Location: index.php");
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
        header("Location: register.php");
        exit();
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
                            // Automatically log in the user after registration
                            $_SESSION['user_id'] = $conn->insert_id;
                            $_SESSION['username'] = $user_username;
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
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Registro de Usuario</title>
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
            .btn-primary {
                background-color: #007bff; /* Blue for primary buttons */
                border: none;
            }
            .btn-primary:hover {
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
        </style>
    </head>
    <body>
        <?php include 'user_navbar.php'; ?>
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
        </div>
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
