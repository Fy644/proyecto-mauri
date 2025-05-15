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

    $consulta = $conn->query("SELECT * FROM `carros`");

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
                header("Location: inventory.php");
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
        header("Location: inventory.php");
        exit();
    }

    // Fetch user data if logged in
    $user_data = [];
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT * FROM users WHERE id = ?";
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
        <title>Todos los Coches - Agencia Elmas Capitos</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f8f9fa; /* Consistent light gray background */
            }
            .card {
                background-color: #ffffff; /* White for card background */
                border: none;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            .card-img-top {
                object-fit: cover;
                width: 100%;
                height: 200px;
                border-top-left-radius: 8px;
                border-top-right-radius: 8px;
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
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">Agencia Elmas Capitos</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="inventory.php">Inventario</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="new_appointment.php">Prueba de coche</a>
                        </li>
                    </ul>
                </div>
                <img src="<?php echo isset($_SESSION['user_id']) && !empty($user_data['profile_picture']) ? htmlspecialchars($user_data['profile_picture']) : 'Untitled.svg'; ?>" 
                     alt="User Login" class="user-login-icon" onclick="toggleUserLogin()">
                <div class="user-login-form" id="userLoginForm">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="text-center mb-3">
                            <img src="<?php echo htmlspecialchars($user_data['profile_picture'] ?? 'Untitled.svg'); ?>" 
                                 alt="Profile Picture" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <p class="text-center">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                        <a href="user_settings.php" class="btn btn-primary mb-2">User Settings</a>
                        <form method="post" action="">
                            <button type="submit" name="logout" class="btn btn-danger">Log Out</button>
                        </form>
                    <?php else: ?>
                        <div class="text-center mb-3">
                            <img src="Untitled.svg" 
                                 alt="Default Profile Picture" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                        <?php if ($login_error): ?>
                            <div class="alert alert-danger"><?php echo $login_error; ?></div>
                        <?php endif; ?>
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Usuario</label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary">Iniciar Sesión</button>
                            <a href="register.php" class="btn btn-secondary">Registrarse</a>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
        <div class="container mt-4">
            <h1 class="text-center">Todos los Coches</h1>
            <div class="car-section">
                <div class="row">
                    <?php
                        while ($result = $consulta->fetch_object()) {
                            $imageSrc = "images/" . $result->img_name . ".png";
                            $shortDescription = strlen($result->description) > 40 ? substr($result->description, 0, 40) . '...' : $result->description;
                            $typeCapitalized = ucfirst($result->type); // Capitalize the first letter
                            echo "<div class='col-md-4 mb-4'>";
                            echo "<div class='card'>";
                            echo "<img src='" . $imageSrc . "' class='card-img-top' alt='" . $result->name . "'>";
                            echo "<div class='card-body'>";
                            echo "<h5 class='card-title'>" . $result->name . " (" . $result->year . ")</h5>";
                            echo "<p class='card-text'>" . $shortDescription . "</p>";
                            echo "<p class='card-text'><strong>Precio:</strong> $" . $result->price . "</p>";
                            echo "<p class='card-text'><strong>Tipo:</strong> " . $typeCapitalized . "</p>";
                            echo "<p class='card-text'><strong>Usado:</strong> " . ($result->used ? "Sí" : "No") . "</p>";
                            echo "<a href='view_car.php?id=" . $result->id . "' class='btn btn-primary'>Ver Detalles</a>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    ?>
                </div>
            </div>
        </div>
        <a href="admin/login.php" class="admin-login">Admin Login</a>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
    </body>
</html>
