<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

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
        $name = $_POST['name'];
        $price = $_POST['price'];
        $type = $_POST['type'];
        $featured = isset($_POST['featured']) ? 1 : 0;
        $description = $_POST['description'];
        $year = $_POST['year'];
        $used = isset($_POST['used']) ? 1 : 0; // New 'used' attribute

        // Handle image upload
        $targetDir = "../images/";
        $fileInfo = pathinfo($_FILES["image"]["name"]);
        $img_name = $fileInfo['filename']; // Get filename without extension
        $fileExtension = strtolower($fileInfo['extension']);
        $targetFile = $targetDir . $img_name . ".png";

        // Check if the target directory is writable
        if (!is_writable($targetDir)) {
            $error_message = "The images folder is not writable. Please check folder permissions.";
        } else {
            // Validate file type
            if ($fileExtension !== "png") {
                $error_message = "Only PNG files are allowed.";
            } else {
                if ($_FILES["image"]["error"] === UPLOAD_ERR_OK) {
                    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                        $sql = "INSERT INTO carros (name, price, type, featured, description, img_name, year, used, deleted) VALUES ('$name', '$price', '$type', '$featured', '$description', '$img_name', '$year', '$used', 0)";

                        if ($conn->query($sql) === TRUE) {
                            $success_message = "New car added successfully";
                        } else {
                            $error_message = "Error: " . $sql . "<br>" . $conn->error;
                        }
                    } else {
                        $error_message = "Error moving the uploaded file. Check folder permissions.";
                    }
                } else {
                    $error_message = "File upload error: " . $_FILES["image"]["error"];
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Agregar Nuevo Carro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body>
        <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="text-center">Agregar Nuevo Carro</h1>
                <a href="admin_panel.php" class="btn btn-secondary">Regresar</a>
            </div>
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <form method="post" action="" enctype="multipart/form-data" id="carForm">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre del Carro</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Precio</label>
                    <input type="number" class="form-control" name="price" required>
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
                    <textarea class="form-control" name="description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="year" class="form-label">Año</label>
                    <input type="number" class="form-control" name="year" required>
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
