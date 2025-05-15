<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "agencia";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Error: " . $conn->connect_error);
        exit();
    }

    if (!isset($_GET['id'])) {
        die("Error: No se proporcionó el ID del coche.");
    }

    $car_id = intval($_GET['id']);
    $car = $conn->query("SELECT * FROM carros WHERE id = $car_id AND deleted = 0")->fetch_assoc();

    if (!$car) {
        die("Error: Coche no encontrado.");
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($car['name']); ?> - Detalles del Coche</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color:rgb(211, 208, 208); /* Light gray background */
            }
            .car-image {
                max-width: 100%;
                height: auto;
                border-radius: 8px;
            }
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
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
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
                        <li class="nav-item">
                            <a class="nav-link" href="admin/login.php">Admin</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6">
                    <img src="images/<?php echo htmlspecialchars($car['img_name']); ?>.png" alt="<?php echo htmlspecialchars($car['name']); ?>" class="car-image">
                </div>
                <div class="col-md-6">
                    <h1><?php echo htmlspecialchars($car['name']); ?> (<?php echo $car['year']; ?>)</h1>
                    <p><strong>Precio:</strong> $<?php echo number_format($car['price']); ?></p>
                    <p><strong>Tipo:</strong> <?php echo ucfirst($car['type']); ?></p>
                    <p><strong>Usado:</strong> <?php echo $car['used'] ? "Sí" : "No"; ?></p>
                    <p><strong>Descripción:</strong> <?php echo htmlspecialchars($car['description']); ?></p>
                    <a href="buy_car.php?id=<?php echo $car['id']; ?>" class="btn btn-success">Comprar Este Coche</a>
                    <a href="javascript:history.back()" class="btn btn-secondary">Regresar</a>
                </div>
            </div>
        </div>
        <img src="Untitled.svg" alt="User Login" class="user-login-icon" onclick="toggleUserLogin()">
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
