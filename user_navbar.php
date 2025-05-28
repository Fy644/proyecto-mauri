<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$user_data = [];
if (isset($_SESSION['user_id'])) {
    $conn = $conn ?? new mysqli("localhost", "root", "", "agencia");
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT profile_picture FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
    }
}
$login_error = $login_error ?? null;
$show_login_popup = $show_login_popup ?? false;
?>
<style>
    .navbar {
        background-color: #343a40; /* Dark gray for navbar */
    }
    .navbar-brand, .nav-link {
        color: #ffffff !important; /* White text for navbar links */
    }
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

</style>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Agencia Lou-Lou</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="inventory.php">Inventario</a></li>
                <li class="nav-item"><a class="nav-link" href="new_appointment.php">Prueba de coche</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a href="service_request.php" class="nav-link">Solicitar servicio</a></li>
                    <li class="nav-item"><a href="review_car.php" class="nav-link">Reseñar coche</a></li>
                <?php endif; ?>
                <li class="nav-item"><a href="contacts.php" class="nav-link">Informes</a></li>
            </ul>
        </div>
        <!-- Search bar start -->
        <form class="d-flex me-3" role="search" action="search.php" method="get" style="max-width: 300px;">
            <input class="form-control me-2" type="search" name="q" placeholder="Buscar coches..." aria-label="Buscar" required>
            <button class="btn btn-outline-success" type="submit">Buscar</button>
        </form>
        <!-- Search bar end -->
        <img src="<?php echo isset($_SESSION['user_id']) && !empty($user_data['profile_picture']) ? htmlspecialchars($user_data['profile_picture']) : 'Untitled.svg'; ?>"
             alt="User Login" class="user-login-icon" onclick="toggleUserLogin()">
        <div class="user-login-form" id="userLoginForm">
            <?php if (isset($_SESSION['user_id'])): ?>
                <div class="text-center mb-3">
                    <img src="<?php echo htmlspecialchars($user_data['profile_picture'] ?? 'Untitled.svg'); ?>"
                         alt="Profile Picture" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                </div>
                <p class="text-center">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
                <a href="user_settings.php" class="btn btn-primary mb-2">User Settings</a>
                <form method="post" action="">
                    <button type="submit" name="logout" class="btn btn-danger">Log Out</button>
                </form>
            <?php else: ?>
                <div class="text-center mb-3">
                    <img src="Untitled.svg"
                         alt="Default Profile Picture" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                </div>
                <?php if ($login_error): ?>
                    <div class="alert alert-danger"><?php echo $login_error; ?></div>
                <?php endif; ?>
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary">Iniciar Sesión</button>
                    <a href="register.php" class="btn btn-secondary">Registrarse</a>
                </form>
            <?php endif; ?>
        </div>
    </div>
</nav>
<script>
function toggleUserLogin() {
    const form = document.getElementById('userLoginForm');
    form.style.display = form.style.display === 'block' ? 'none' : 'block';
}
// Automatically show login popup if login error exists
<?php if ($show_login_popup): ?>
    document.addEventListener('DOMContentLoaded', function () {
        toggleUserLogin();
    });
<?php endif; ?>
</script>
<script>
            function toggleUserLogin() {
                const form = document.getElementById('userLoginForm');
                form.style.display = form.style.display === 'block' ? 'none' : 'block';
            }

            // Automatically show login popup if login error exists
            <?php if ($show_login_popup): ?>
                document.addEventListener('DOMContentLoaded', function () {
                    toggleUserLogin();
                });
            <?php endif; ?>
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
