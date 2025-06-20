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

    require_once '../includes/validation.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = [];
        
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
            // Validate name (varchar(32))
            $name = trim($_POST['name']);
            if ($error = validateVarchar($name, 'nombre del auto', 32)) {
                $errors[] = $error;
            }
            
            // Validate price (int)
            $price = $_POST['price'];
            if ($error = validateInt($price, 'precio')) {
                $errors[] = $error;
            }
            if ($price <= 0) {
                $errors[] = "El precio debe ser mayor a 0.";
            }
            
            // Validate type (varchar(16))
            $type = trim($_POST['type']);
            if ($error = validateVarchar($type, 'tipo', 16)) {
                $errors[] = $error;
            }
            
            // Validate featured (tinyint)
            $featured = isset($_POST['featured']) ? 1 : 0;
            
            // Validate description (text)
            $description = trim($_POST['description']);
            if ($error = validateText($description, 'descripción')) {
                $errors[] = $error;
            }
            
            // Validate year (int)
            $year = $_POST['year'];
            if ($error = validateInt($year, 'año')) {
                $errors[] = $error;
            }
            $current_year = date('Y');
            if ($year < 1900 || $year > $current_year + 1) {
                $errors[] = "El año debe estar entre 1900 y " . ($current_year + 1);
            }
            
            // Validate used (tinyint)
            $used = isset($_POST['used']) ? 1 : 0;

            // Handle image upload if provided
            // Always get the current image name from the hidden field
            $img_name = isset($_POST['img_name']) ? $_POST['img_name'] : '';
            if (!empty($_FILES['image']['name'])) {
                if ($_FILES['image']['type'] !== 'image/png') {
                    $errors[] = "La imagen debe ser en formato PNG.";
                } else {
                    $original_filename = $_FILES['image']['name'];
                    $target_dir = '/opt/lampp/htdocs/proyecto-mauri/images';
                    $target_path = $target_dir . '/' . $original_filename;
                    // Debug info
                    if (!is_dir($target_dir)) {
                        $errors[] = "El directorio de destino no existe: $target_dir";
                    } elseif (!is_writable($target_dir)) {
                        $errors[] = "Directorio destino no tiene permisos de escritura: $target_dir";
                    }
                    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                        $errors[] = "Error en la subida del archivo. Código de error: " . $_FILES['image']['error'];
                    }
                    // Try to move the uploaded file
                    if (empty($errors)) {
                        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
                            $errors[] = "Error al subir la imagen. Ruta destino: $target_path";
                        } else {
                            // Store only the filename without extension in the DB
                            $img_name = pathinfo($original_filename, PATHINFO_FILENAME);
                        }
                    }
                }
            }
            
            if (empty($errors)) {
                // Sanitize inputs for database
                $name = $conn->real_escape_string($name);
                $type = $conn->real_escape_string($type);
                $description = $conn->real_escape_string($description);
                $img_name = $conn->real_escape_string($img_name);
                
                $sql = "UPDATE carros SET 
                        name = '$name', 
                        price = '$price',
                        type = '$type',
                        featured = '$featured',
                        description = '$description',
                        img_name = '$img_name',
                        year = '$year',
                        used = '$used'
                        WHERE id = " . intval($_POST['car_id']);

                if ($conn->query($sql) === TRUE) {
                    $success_message = "Coche actualizado exitosamente.";
                    $car = $conn->query("SELECT * FROM carros WHERE id = " . intval($_POST['car_id']))->fetch_assoc();
                } else {
                    $error_message = "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                $error_message = implode("<br>", $errors);
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
                        <input type="hidden" name="img_name" value="<?php echo htmlspecialchars($car['img_name']); ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Auto</label>
                            <input type="text" class="form-control" name="name" maxlength="32" value="<?php echo htmlspecialchars($car['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Precio</label>
                            <input type="number" class="form-control" name="price" min="1" value="<?php echo htmlspecialchars($car['price']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="type" class="form-label">Tipo</label>
                            <input type="text" class="form-control" name="type" maxlength="16" value="<?php echo htmlspecialchars($car['type']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="featured" id="featured" <?php echo $car['featured'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="featured">Destacado</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control" name="description" rows="3" required><?php echo htmlspecialchars($car['description']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="year" class="form-label">Año</label>
                            <input type="number" class="form-control" name="year" min="1900" max="<?php echo date('Y') + 1; ?>" value="<?php echo htmlspecialchars($car['year']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="used" id="used" <?php echo $car['used'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="used">Usado</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Imagen actual</label><br>
                            <?php if (!empty($car['img_name'])): ?>
                                <img src="/proyecto-mauri/images/<?php echo htmlspecialchars($car['img_name']); ?>.png" alt="Imagen actual" style="max-width: 120px; max-height: 80px; border:1px solid #ccc; margin-bottom:8px;">
                            <?php else: ?>
                                <span class="text-muted">No hay imagen</span>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Imagen (PNG)</label>
                            <input type="file" class="form-control" name="image" accept=".png">
                            <small class="text-muted">Deja en blanco para mantener la imagen actual</small>
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
