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
                    <li class="nav-item"><a href="user_chat.php" class="nav-link">Chat</a></li>
                <?php endif; ?>
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
