<?php
    session_start();

    // Enable error reporting for debugging
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
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

    $user_id = $_SESSION['user_id'];
    $user_query = $conn->prepare("SELECT username, email, fullname, profile_picture FROM users WHERE id = ?");
    $user_query->bind_param("i", $user_id);
    $user_query->execute();
    $user_result = $user_query->get_result();
    $user = $user_result->fetch_assoc();

    // Fetch cars purchased by the user
    $purchased_cars = [];
    $purchased_query = $conn->prepare("
        SELECT sales.id AS sale_id, carros.name AS car_name, carros.year AS car_year, carros.img_name AS car_image, 
               sales.price AS car_price, sales.datetimePurchase AS purchase_date 
        FROM sales 
        INNER JOIN carros ON sales.id_car = carros.id 
        WHERE sales.client_id = ? AND sales.deleted = 0
    ");
    $purchased_query->bind_param("i", $user_id);
    $purchased_query->execute();
    $purchased_result = $purchased_query->get_result();
    while ($row = $purchased_result->fetch_assoc()) {
        $purchased_cars[] = $row;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['logout'])) {
            session_destroy();
            header("Location: index.php");
            exit();
        }

        if (isset($_POST['update_profile'])) {
            $new_email = trim($_POST['email']);
            $new_fullname = trim($_POST['fullname']);
            $errors = [];

            // Validate email
            if (empty($new_email)) {
                $errors[] = "El correo electrónico es requerido.";
            } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "El formato del correo electrónico no es válido.";
            } elseif (strlen($new_email) > 100) {
                $errors[] = "El correo electrónico no puede tener más de 100 caracteres.";
            }

            // Validate fullname
            if (empty($new_fullname)) {
                $errors[] = "El nombre completo es requerido.";
            } elseif (strlen($new_fullname) > 100) {
                $errors[] = "El nombre completo no puede tener más de 100 caracteres.";
            }

            // Handle profile picture upload
            $targetDir = "userpfp/";
            $profile_picture_name = $user_id; // Use user ID as the filename
            $targetFile = $targetDir . $profile_picture_name . ".png";

            if (!is_writable($targetDir)) {
                $errors[] = "La carpeta de fotos de perfil no tiene permisos de escritura.";
            } else {
                // Only process file upload if a file was actually selected
                if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] !== UPLOAD_ERR_NO_FILE) {
                    $fileInfo = pathinfo($_FILES["profile_picture"]["name"]);
                    $fileExtension = isset($fileInfo['extension']) ? strtolower($fileInfo['extension']) : '';

                    if ($_FILES["profile_picture"]["error"] === UPLOAD_ERR_OK) {
                        if ($fileExtension === "png") {
                            if ($_FILES["profile_picture"]["size"] > 5000000) { // 5MB limit
                                $errors[] = "La imagen es demasiado grande. El tamaño máximo es 5MB.";
                            } else {
                                if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
                                    $update_query = $conn->prepare("UPDATE users SET email = ?, fullname = ?, profile_picture = ? WHERE id = ?");
                                    $update_query->bind_param("sssi", $new_email, $new_fullname, $targetFile, $user_id);
                                } else {
                                    $errors[] = "Error al mover el archivo subido. Verifique los permisos de la carpeta.";
                                }
                            }
                        } else {
                            $errors[] = "Solo se permiten archivos PNG para la foto de perfil.";
                        }
                    } else {
                        $errors[] = "Error al subir el archivo: " . $_FILES["profile_picture"]["error"];
                    }
                } else {
                    // If no file was uploaded, just update email and fullname
                    $update_query = $conn->prepare("UPDATE users SET email = ?, fullname = ? WHERE id = ?");
                    $update_query->bind_param("ssi", $new_email, $new_fullname, $user_id);
                }

                // Execute the update query if it was prepared and there are no errors
                if (empty($errors) && isset($update_query) && $update_query->execute()) {
                    $success_message = "Perfil actualizado con éxito.";
                } else if (!empty($errors)) {
                    $error_message = implode("<br>", $errors);
                } else {
                    $error_message = "Error al actualizar el perfil.";
                }
            }
        }

        if (isset($_POST['update_password'])) {
            $new_password = trim($_POST['new_password']);
            $errors = [];

            // Validate password
            if (empty($new_password)) {
                $errors[] = "La contraseña es requerida.";
            } elseif (strlen($new_password) < 8) {
                $errors[] = "La contraseña debe tener al menos 8 caracteres.";
            } elseif (strlen($new_password) > 100) {
                $errors[] = "La contraseña no puede tener más de 100 caracteres.";
            } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/", $new_password)) {
                $errors[] = "La contraseña debe contener al menos una letra mayúscula, una minúscula y un número.";
            }

            if (empty($errors)) {
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                $update_password_query = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_password_query->bind_param("si", $hashed_password, $user_id);

                if ($update_password_query->execute()) {
                    $success_message = "Contraseña actualizada con éxito.";
                } else {
                    $error_message = "Error al actualizar la contraseña.";
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
        <title>Configuración de Usuario</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f8f9fa; /* Light gray background */
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
            .btn-secondary {
                background-color: #6c757d; /* Gray for secondary buttons */
                border: none;
            }
            .btn-secondary:hover {
                background-color: #5a6268; /* Darker gray on hover */
            }
            .user-login-icon {
                width: 32px;
                height: 32px;
                cursor: pointer;
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
                bottom: 10px;
                left: 10px;
                font-size: 0.9rem;
                color: #6c757d;
                text-decoration: none;
            }
            .admin-login:hover {
                color: #343a40;
            }
            .rounded-circle {
                width: 80px; /* Match size with index/inventory pages */
                height: 80px;
                object-fit: cover;
            }
            .car-image {
                width: 100%;
                height: 200px;
                object-fit: cover;
                border-radius: 8px;
            }
        </style>
    </head>
    <body>
        <?php include 'user_navbar.php'; ?>
        <div class="container mt-5">
            <h1 class="text-center">Configuración de Usuario</h1>
            <p class="text-center">Bienvenido, <?php echo htmlspecialchars($user['username']); ?>.</p>
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <div class="text-center mb-4">
                <?php
                    $profile_picture = $user['profile_picture'] ?? 'default_profile.png'; // Default profile picture
                ?>
                <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="rounded-circle">
            </div>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="fullname" class="form-label">Nombre Completo</label>
                    <input type="text" class="form-control" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Foto de Perfil (solo PNG)</label>
                    <input type="file" class="form-control" name="profile_picture" accept="image/png">
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary">Actualizar Perfil</button>
            </form>
            <hr>
            <form method="post" action="">
                <div class="mb-3">
                    <label for="new_password" class="form-label">Nueva Contraseña</label>
                    <input type="password" class="form-control" name="new_password" required>
                </div>
                <button type="submit" name="update_password" class="btn btn-secondary">Actualizar Contraseña</button>
            </form>
            <hr>
            <h2 class="text-center mt-5">Coches Comprados</h2>
            <?php if (!empty($purchased_cars)): ?>
                <div class="row">
                    <?php foreach ($purchased_cars as $car): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="images/<?php echo htmlspecialchars($car['car_image']); ?>.png" alt="<?php echo htmlspecialchars($car['car_name']); ?>" class="car-image">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($car['car_name']); ?> (<?php echo $car['car_year']; ?>)</h5>
                                    <p class="card-text"><strong>Precio:</strong> $<?php echo number_format($car['car_price'], 2); ?></p>
                                    <p class="card-text"><strong>Fecha de Compra:</strong> <?php echo htmlspecialchars($car['purchase_date']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center">No has comprado ningún coche.</p>
            <?php endif; ?>
            <a href="index.php" class="btn btn-secondary mt-3">Volver al Inicio</a>
        </div>
        <a href="login.php" class="admin-login">Admin Login</a>
        <script>
            function toggleUserLogin() {
                const form = document.getElementById('userLoginForm');
                form.style.display = form.style.display === 'block' ? 'none' : 'block';
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
