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

    $consulta = $conn->query("SELECT * FROM `carros` WHERE `featured` = 1");

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
        header("Location: index.php");
        exit();
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
        <title>Agencia Elmas Capitos</title>
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
        <?php include 'user_navbar.php'; ?>
        <div class="container mt-4">
            <h1 class="text-center">Coches Destacados</h1>
            <div class="row">
                <?php
                    while ($result = $consulta->fetch_object()) {
                        $imageSrc = "images/" . $result->img_name . ".png";
                        $shortDescription = strlen($result->description) > 40 ? substr($result->description, 0, 40) . '...' : $result->description;
                        $typeCapitalized = ucfirst($result->type);
                        echo "<div class='col-md-4 mb-4'>";
                        echo "<div class='card'>";
                        echo "<img src='" . $imageSrc . "' class='card-img-top' alt='" . $result->name . "'>";
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>" . $result->name . " (" . $result->year . ")</h5>";
                        echo "<p class='card-text'>" . $shortDescription . "</p>";
                        echo "<p class='card-text'><strong>Precio:</strong> $" . $result->price . "</p>";
                        echo "<p class='card-text'><strong>Tipo:</strong> " . $typeCapitalized . "</p>";
                        echo "<a href='view_car.php?id=" . $result->id . "' class='btn btn-primary'>Ver Detalles</a>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                ?>
            </div>
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