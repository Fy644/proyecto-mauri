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
            .car-image {
                max-width: 100%;
                height: auto;
                border-radius: 8px;
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
