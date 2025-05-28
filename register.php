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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $email = trim($_POST['email']);
        $fullname = trim($_POST['fullname']);

        // Validate username (max 50 chars, alphanumeric and underscores)
        if (!preg_match('/^[a-zA-Z0-9_]{1,50}$/', $username)) {
            $error = "El nombre de usuario debe contener solo letras, números y guiones bajos, y tener máximo 50 caracteres.";
        }
        // Validate password
        elseif (strlen($password) < 8) {
            $error = "La contraseña debe tener al menos 8 caracteres.";
        }
        // Validate password confirmation
        elseif ($password !== $confirm_password) {
            $error = "Las contraseñas no coinciden.";
        }
        // Validate email (max 255 chars, valid email format)
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
            $error = "Por favor ingresa un email válido.";
        }
        // Validate fullname (max 255 chars, only letters, spaces and apostrophes)
        elseif (!preg_match('/^[a-zA-Z\' ]{1,255}$/', $fullname)) {
            $error = "El nombre completo debe contener solo letras, espacios y apóstrofes, y tener máximo 255 caracteres.";
        }
        else {
            // Check if username already exists
            $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $check_stmt->bind_param("s", $username);
            $check_stmt->execute();
            if ($check_stmt->get_result()->num_rows > 0) {
                $error = "Este nombre de usuario ya está en uso.";
            } else {
                // Hash password and insert user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, password, email, fullname) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $hashed_password, $email, $fullname);
                if ($stmt->execute()) {
                    $success = "¡Registro exitoso! Ahora puedes iniciar sesión.";
                } else {
                    $error = "Error al registrar usuario: " . htmlspecialchars($conn->error);
                }
                $stmt->close();
            }
            $check_stmt->close();
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
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php elseif (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="username" class="form-label">Nombre de Usuario</label>
                    <input type="text" class="form-control" name="username" maxlength="50" pattern="[a-zA-Z0-9_]+" title="Solo se permiten letras, números y guiones bajos (máximo 50 caracteres)" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" name="password" minlength="8" required>
                    <div class="form-text">La contraseña debe tener al menos 8 caracteres.</div>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                    <input type="password" class="form-control" name="confirm_password" minlength="8" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" maxlength="255" required>
                </div>
                <div class="mb-3">
                    <label for="fullname" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" name="fullname" maxlength="255" pattern="[a-zA-Z' ]+" title="Solo se permiten letras, espacios y apóstrofes (máximo 255 caracteres)" required>
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
