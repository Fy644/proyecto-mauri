<?php
    session_start();
    session_destroy();
    header("Location: ./login.php");
    exit();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cerrar Sesión</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <div class="content">
            <div class="container mt-4">
                <h1 class="text-center">Cerrando Sesión...</h1>
            </div>
        </div>
    </body>
</html>
