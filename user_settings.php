<?php
    session_start();

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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['update_profile'])) {
            $new_email = $_POST['email'];
            $new_fullname = $_POST['fullname'];

            // Handle profile picture upload
            $targetDir = "userpfp/";
            $fileInfo = pathinfo($_FILES["profile_picture"]["name"]);
            $profile_picture_name = $user_id; // Use user ID as the filename
            $fileExtension = strtolower($fileInfo['extension']);
            $targetFile = $targetDir . $profile_picture_name . ".png";

            if (!is_writable($targetDir)) {
                $error_message = "The profile picture folder is not writable. Please check folder permissions.";
            } else {
                if ($_FILES["profile_picture"]["error"] === UPLOAD_ERR_OK) {
                    if ($fileExtension === "png") {
                        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile)) {
                            $update_query = $conn->prepare("UPDATE users SET email = ?, fullname = ?, profile_picture = ? WHERE id = ?");
                            $update_query->bind_param("sssi", $new_email, $new_fullname, $targetFile, $user_id);

                            if ($update_query->execute()) {
                                $success_message = "Perfil actualizado con éxito.";
                            } else {
                                $error_message = "Error al actualizar el perfil.";
                            }
                        } else {
                            $error_message = "Error al mover el archivo subido. Verifique los permisos de la carpeta.";
                        }
                    } else {
                        $error_message = "Solo se permiten archivos PNG para la foto de perfil.";
                    }
                } else {
                    $error_message = "Error al subir el archivo: " . $_FILES["profile_picture"]["error"];
                }
            }
        }

        if (isset($_POST['update_password'])) {
            $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
            $update_password_query = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_password_query->bind_param("si", $new_password, $user_id);

            if ($update_password_query->execute()) {
                $success_message = "Contraseña actualizada con éxito.";
            } else {
                $error_message = "Error al actualizar la contraseña.";
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
    </head>
    <body>
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
                <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
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
            <a href="index.php" class="btn btn-secondary mt-3">Volver al Inicio</a>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
