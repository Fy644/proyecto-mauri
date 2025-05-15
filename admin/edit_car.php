<?php
    session_start();
    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: login.php");
        exit();
    }

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "agencia";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Error: " . $conn->connect_error);
        exit();
    }

    // Fetch all cars for the dropdown menu
    $cars = $conn->query("SELECT id, name FROM carros WHERE deleted = 0");

    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['car_id'])) {
        $car_id = intval($_GET['car_id']);
        $car = $conn->query("SELECT * FROM carros WHERE id = $car_id")->fetch_assoc();

        if (!$car) {
            die("Error: Coche no encontrado.");
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['delete'])) {
            $car_id = intval($_POST['car_id']);
            $sql = "UPDATE carros SET deleted = 1 WHERE id = $car_id";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Coche eliminado exitosamente.";
                unset($car); // Remove the car data
            } else {
                $error_message = "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $car_id = intval($_POST['car_id']);
            $name = substr($_POST['name'], 0, 32); // Limit to 32 characters
            $price = intval($_POST['price']);
            $type = substr($_POST['type'], 0, 16); // Limit to 16 characters
            $featured = isset($_POST['featured']) ? 1 : 0;
            $description = substr($_POST['description'], 0, 65535); // Limit to TEXT size
            $year = intval($_POST['year']);
            $used = isset($_POST['used']) ? 1 : 0;

            // Validate inputs
            if (empty($name) || $price <= 0 || empty($type) || empty($description) || $year <= 0) {
                $error_message = "Todos los campos son obligatorios y deben ser válidos.";
            } else {
                // Retain the original image name if no new file is uploaded
                $img_name = $car['img_name']; // Default to the current image name
                if (!empty($_FILES['image']['name'])) {
                    $targetDir = "../images/";
                    $fileInfo = pathinfo($_FILES["image"]["name"]);
                    $img_name = substr($fileInfo['filename'], 0, 32); // Limit to 32 characters
                    $fileExtension = strtolower($fileInfo['extension']);
                    $targetFile = $targetDir . $img_name . ".png";

                    if ($fileExtension !== "png") {
                        $error_message = "Solo se permiten archivos PNG.";
                    } else {
                        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                            $error_message = "Error al mover el archivo subido. Verifica los permisos de la carpeta.";
                        }
                    }
                }

                $sql = "UPDATE carros SET 
                        name = '$name', 
                        price = '$price', 
                        type = '$type', 
                        featured = '$featured', 
                        description = '$description', 
                        year = '$year', 
                        used = '$used', 
                        img_name = '$img_name' 
                        WHERE id = $car_id";

                if ($conn->query($sql) === TRUE) {
                    $success_message = "Coche actualizado exitosamente.";
                    $car = $conn->query("SELECT * FROM carros WHERE id = $car_id")->fetch_assoc(); // Refresh car data
                } else {
                    $error_message = "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Coche</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <div class="content">
            <div class="container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="text-center">Editar Coche</h1>
                    <a href="admin_panel.php" class="btn btn-secondary">Regresar</a>
                </div>
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <!-- Dropdown to select a car -->
                <form method="get" action="">
                    <div class="mb-3">
                        <label for="car_id" class="form-label">Selecciona un Coche para Editar</label>
                        <select name="car_id" class="form-select" onchange="this.form.submit()" required>
                            <option value="">-- Selecciona un Coche --</option>
                            <?php while ($row = $cars->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>" <?php echo (isset($car_id) && $car_id == $row['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($row['name']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </form>

                <?php if (isset($car)): ?>
                    <!-- Form to edit the selected car -->
                    <form method="post" action="" enctype="multipart/form-data">
                        <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Coche</label>
                            <input type="text" class="form-control" name="name" maxlength="32" value="<?php echo htmlspecialchars($car['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Precio</label>
                            <input type="number" class="form-control" name="price" value="<?php echo htmlspecialchars($car['price']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Tipo</label>
                            <select name="type" class="form-select" required>
                                <option value="sport" <?php echo $car['type'] == 'sport' ? 'selected' : ''; ?>>Deportivo</option>
                                <option value="sedan" <?php echo $car['type'] == 'sedan' ? 'selected' : ''; ?>>Sedán</option>
                                <option value="suv" <?php echo $car['type'] == 'suv' ? 'selected' : ''; ?>>SUV</option>
                                <option value="truck" <?php echo $car['type'] == 'truck' ? 'selected' : ''; ?>>Camioneta</option>
                                <option value="van" <?php echo $car['type'] == 'van' ? 'selected' : ''; ?>>Van</option>
                                <option value="hatchback" <?php echo $car['type'] == 'hatchback' ? 'selected' : ''; ?>>Hatchback</option>
                                <option value="coupe" <?php echo $car['type'] == 'coupe' ? 'selected' : ''; ?>>Coupé</option>
                            </select>
                        </div>
                        <div class="mb-3 d-flex align-items-center gap-3">
                            <div>
                                <label for="featured" class="form-check-label">Destacado</label>
                                <input type="checkbox" class="form-check-input" name="featured" <?php echo $car['featured'] ? 'checked' : ''; ?>>
                            </div>
                            <div>
                                <label for="used" class="form-check-label">Usado</label>
                                <input type="checkbox" class="form-check-input" name="used" <?php echo $car['used'] ? 'checked' : ''; ?>>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" name="description" maxlength="65535" rows="3" required><?php echo htmlspecialchars($car['description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="year" class="form-label">Año</label>
                            <input type="number" class="form-control" name="year" value="<?php echo htmlspecialchars($car['year']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Imagen Actual</label><br>
                            <img src="../images/<?php echo htmlspecialchars($car['img_name']); ?>.png" alt="Imagen del Coche" style="width: 200px; height: auto; margin-bottom: 10px;"><br>
                            <label for="image" class="form-label">Reemplazar Imagen (Solo PNG)</label>
                            <input type="file" class="form-control" name="image" accept="image/png">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Actualizar Coche</button>
                            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este coche?');">Eliminar Coche</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-wEmeIV1mKuiNp12z93r+8mW9ckKnQe7f4pANCzW5yJlHCu6pC3e6pniU9FjF9ajs" crossorigin="anonymous"></script>
    </body>
</html>
