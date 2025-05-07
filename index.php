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

    $consulta = $conn->query("SELECT * FROM `carros` WHERE `featured` = 1");
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agencia Elmas Capitos</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .card-img-top {
                object-fit: cover; /* Ensure the image fills the area */
                width: 100%; /* Full width of the card */
                height: 200px; /* Fixed height for uniformity */
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
        <div class="container mt-4">
            <h1 class="text-center">Coches Destacados</h1>
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
                        echo "<a href='view_car.php?id=" . $result->id . "' class='btn btn-primary'>Ver Detalles</a>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-QF1FfH2Y5h2m5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5v5h5