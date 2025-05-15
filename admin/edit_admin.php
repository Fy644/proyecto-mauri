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

    // Fetch all admins for the dropdown menu
    $admins = $conn->query("SELECT id, username FROM admins");

    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['admin_id'])) {
        $admin_id = intval($_GET['admin_id']);
        $admin = $conn->query("SELECT * FROM admins WHERE id = $admin_id")->fetch_assoc();

        if (!$admin) {
            die("Error: Admin not found.");
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['delete'])) {
            $admin_id = intval($_POST['admin_id']);
            $sql = "DELETE FROM admins WHERE id = $admin_id";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Admin deleted successfully.";
                unset($admin); // Remove the admin data
            } else {
                $error_message = "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $admin_id = intval($_POST['admin_id']);
            $username = substr($_POST['username'], 0, 50); // Limit username to 50 characters
            $password = !empty($_POST['password']) ? substr($_POST['password'], 0, 255) : '';
            $hashed_password = !empty($password) ? password_hash($password, PASSWORD_BCRYPT) : $admin['password'];

            $sql = "UPDATE admins SET 
                    username = '$username', 
                    password = '$hashed_password' 
                    WHERE id = $admin_id";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Admin updated successfully.";
                $admin = $conn->query("SELECT * FROM admins WHERE id = $admin_id")->fetch_assoc(); // Refresh admin data
            } else {
                $error_message = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Editar Administrador</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .user-login-icon {
                width: 32px;
                height: 32px;
                cursor: pointer;
            }
            .user-login-form {
                display: none;
                position: fixed;
                top: 50px;
                right: 20px;
                background: white;
                border: 1px solid #ddd;
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }
        </style>
    </head>
    <body>
        <?php include 'navbar.php'; ?>
        <div class="content">
            <div class="container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h1 class="text-center">Editar Administrador</h1>
                    <a href="admin_panel.php" class="btn btn-secondary">Regresar</a>
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
                    </form>
                </div>
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <!-- Dropdown to select an admin -->
                <form method="get" action="">
                    <div class="mb-3">
                        <label for="admin_id" class="form-label">Selecciona Administrador para editar</label>
                        <select name="admin_id" class="form-select" onchange="this.form.submit()" required>
                            <option value="">-- Selecciona Administrador --</option>
                            <?php while ($row = $admins->fetch_assoc()): ?>
                                <option value="<?php echo $row['id']; ?>" <?php echo (isset($admin_id) && $admin_id == $row['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($row['username']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </form>

                <?php if (isset($admin)): ?>
                    <!-- Form to edit the selected admin -->
                    <form method="post" action="">
                        <input type="hidden" name="admin_id" value="<?php echo $admin['id']; ?>">
                        <div class="mb-3">
                            <label for="username" class="form-label">Usuario</label>
                            <input type="text" class="form-control" name="username" maxlength="50" value="<?php echo htmlspecialchars($admin['username']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña (Deja en blanco para dejar contraseña actual)</label>
                            <input type="password" class="form-control" name="password" maxlength="255">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Actualizar Administrador</button>
                            <button type="submit" name="delete" class="btn btn-danger" onclick="return confirm('Estas seguro que quieres borrar este administrador?');">Borrar Administrador</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <script>
            function toggleUserLogin() {
                const form = document.getElementById('userLoginForm');
                form.style.display = form.style.display === 'block' ? 'none' : 'block';
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
