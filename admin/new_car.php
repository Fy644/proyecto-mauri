<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../includes/validation.php';

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "agencia";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Error: " . $conn->connect_error);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $errors = [];
        
        // Validate name (varchar(32))
        $name = trim($_POST['name']);
        if ($error = validateVarchar($name, 'nombre del carro', 32)) {
            $errors[] = $error;
        }
        
        // Validate price (int)
        $price = $_POST['price'];
        if ($error = validateInt($price, 'precio')) {
            $errors[] = $error;
        }
        if ($price <= 0) {
            $errors[] = "El precio debe ser mayor que 0.";
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
            $errors[] = "El año debe estar entre 1900 y " . ($current_year + 1) . ".";
        }
        
        // Validate used (tinyint)
        $used = isset($_POST['used']) ? 1 : 0;
        
        // Validate image
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Por favor selecciona una imagen.";
        } else {
            $fileInfo = pathinfo($_FILES["image"]["name"]);
            $fileExtension = strtolower($fileInfo['extension']);
            if ($fileExtension !== "png") {
                $errors[] = "Solo se permiten archivos PNG.";
            }
        }
        
        if (empty($errors)) {
            // Handle image upload
            $targetDir = "../images/";
            $fileInfo = pathinfo($_FILES["image"]["name"]);
            $img_name = substr($fileInfo['filename'], 0, 32); // Limit to 32 characters
            $targetFile = $targetDir . $img_name . ".png";
            
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Sanitize inputs for database
                $name = $conn->real_escape_string($name);
                $type = $conn->real_escape_string($type);
                $description = $conn->real_escape_string($description);
                $img_name = $conn->real_escape_string($img_name);
                
                $sql = "INSERT INTO carros (name, price, type, featured, description, img_name, year, used, deleted) 
                        VALUES ('$name', $price, '$type', $featured, '$description', '$img_name', $year, $used, 0)";
                
                if ($conn->query($sql) === TRUE) {
                    $success_message = "Carro agregado exitosamente.";
                } else {
                    $error_message = "Error al agregar el carro: " . $conn->error;
                }
            } else {
                $error_message = "Error al subir la imagen.";
            }
        } else {
            $error_message = implode("<br>", $errors);
        }
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agregar Nuevo Carro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <div class="content">
            <div class="container mt-4">
                <h1 class="text-center">Agregar Nuevo Carro</h1>
                <!-- Page content here -->
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <form method="post" action="" enctype="multipart/form-data" id="carForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Carro</label>
                        <input type="text" class="form-control" name="name" maxlength="32" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Precio</label>
                        <input type="number" class="form-control" name="price" min="1" max="2147483647" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipo</label>
                        <select name="type" class="form-select" required>
                            <option value="sport">Sport</option>
                            <option value="sedan">Sedan</option>
                            <option value="suv">SUV</option>
                            <option value="truck">Truck</option>
                            <option value="van">Van</option>
                            <option value="hatchback">Hatchback</option>
                            <option value="coupe">Coupe</option>
                        </select>
                    </div>
                    <div class="mb-3 d-flex align-items-center gap-3">
                        <div>
                            <label for="featured" class="form-check-label">Destacado</label>
                            <input type="checkbox" class="form-check-input" name="featured">
                        </div>
                        <div>
                            <label for="used" class="form-check-label">Usado</label>
                            <input type="checkbox" class="form-check-input" name="used">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripcion</label>
                        <textarea class="form-control" name="description" rows="3" maxlength="65535" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="year" class="form-label">Año</label>
                        <input type="number" class="form-control" name="year" min="1900" max="<?php echo date('Y') + 1; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Subir Foto (solo PNG)</label>
                        <input type="file" class="form-control" name="image" accept="image/png" required>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Agregar Carro</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            document.getElementById('carForm').addEventListener('submit', function (event) {
                const fileInput = document.querySelector('input[name="image"]');
                const file = fileInput.files[0];
                if (!file) {
                    console.error("No file selected.");
                    event.preventDefault();
                } else if (file.type !== "image/png") {
                    console.error("Formato invalido. por favor solo archivos PNG.");
                    alert("Formato invalido. por favor solo archivos PNG");
                    event.preventDefault();
                } else {
                    console.log("File selected:", file.name);
                }
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-wEmeIV1mKuiNp12z93r+8mW9ckKnQe7f4pANCzW5yJlHCu6pC3e6pniU9FjF9ajs" crossorigin="anonymous"></script>
    </body>
</html>
