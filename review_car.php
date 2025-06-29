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

// Fetch user data for navbar and fullname for review
$user_id = $_SESSION['user_id'];
$user_data = [];
$user_fullname = '';
$sql = "SELECT profile_picture, fullname FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
    $user_fullname = $user_data['fullname'];
}

// Fetch only cars purchased by the user for dropdown
$cars = [];
$purchased_query = $conn->prepare("
    SELECT carros.id, carros.name, carros.year
    FROM sales
    INNER JOIN carros ON sales.id_car = carros.id
    WHERE sales.client_id = ? AND sales.deleted = 0
    GROUP BY carros.id
");
$purchased_query->bind_param("i", $user_id);
$purchased_query->execute();
$purchased_result = $purchased_query->get_result();
while ($row = $purchased_result->fetch_assoc()) {
    $cars[] = $row;
}

$success = null;
$error = null;

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $car_id = intval($_POST['car_id']);
    $score = intval($_POST['score']);
    $review_text = trim($_POST['review_text']);
    $reviewer_name = htmlspecialchars($user_fullname);

    // Validate car ownership
    $owned_car_ids = array_column($cars, 'id');
    if (!in_array($car_id, $owned_car_ids)) {
        $error = "Solo puedes reseñar coches que has comprado.";
    }
    // Validate score (1-5)
    elseif ($score < 1 || $score > 5) {
        $error = "La puntuación debe estar entre 1 y 5.";
    }
    // Validate review text
    elseif (empty($review_text)) {
        $error = "Por favor escribe tu reseña.";
    }
    // Validate review text length (text field in database)
    elseif (strlen($review_text) > 65535) {
        $error = "La reseña es demasiado larga.";
    }
    // Validate reviewer name
    elseif (empty($reviewer_name) || strlen($reviewer_name) > 32) {
        $error = "El nombre del reseñador no es válido.";
    }
    else {
        // Check if user has already reviewed this car
        $check_stmt = $conn->prepare("SELECT id FROM reviews WHERE id_car = ? AND name = ? AND deleted = 0");
        $check_stmt->bind_param("is", $car_id, $reviewer_name);
        $check_stmt->execute();
        if ($check_stmt->get_result()->num_rows > 0) {
            $error = "Ya has reseñado este coche.";
        } else {
            // Insert the review
            $stmt = $conn->prepare("INSERT INTO reviews (rating, name, score, id_car, deleted) VALUES (?, ?, ?, ?, 0)");
            $stmt->bind_param("ssii", $review_text, $reviewer_name, $score, $car_id);
            if ($stmt->execute()) {
                // Prevent resubmission on reload
                header("Location: review_car.php?success=1");
                exit();
            } else {
                $error = "Error al guardar la reseña: " . htmlspecialchars($conn->error);
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}

// Show success message if redirected after review
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = "¡Reseña enviada exitosamente!";
}

// Fetch recent reviews
$recent_reviews = [];
$recent_query = $conn->query("SELECT r.rating, r.name, r.score, c.name AS car_name, c.year FROM reviews r INNER JOIN carros c ON r.id_car = c.id WHERE r.deleted = 0 ORDER BY r.id DESC LIMIT 10");
while ($row = $recent_query->fetch_assoc()) {
    $recent_reviews[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reseñar coche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #343a40; }
        .navbar-brand, .nav-link { color: #ffffff !important; }
        .btn-primary { background-color: #007bff; border: none; }
        .btn-primary:hover { background-color: #0056b3; }
        .user-login-icon { width: 32px; height: 32px; cursor: pointer; }
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
        .admin-login:hover { color: #343a40; }
    </style>
</head>
<body>
    <?php include 'user_navbar.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center">Reseñar un coche</h1>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="post" class="mb-5">
            <div class="mb-3">
                <label for="car_id" class="form-label">Selecciona un coche</label>
                <select name="car_id" class="form-select" required>
                    <option value="">-- Selecciona --</option>
                    <?php foreach ($cars as $car): ?>
                        <option value="<?php echo $car['id']; ?>">
                            <?php echo htmlspecialchars($car['name'] . " (" . $car['year'] . ")"); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="score" class="form-label">Puntuación</label>
                <select name="score" class="form-select" required>
                    <option value="">-- Selecciona --</option>
                    <option value="1">1 - Muy malo</option>
                    <option value="2">2 - Malo</option>
                    <option value="3">3 - Regular</option>
                    <option value="4">4 - Bueno</option>
                    <option value="5">5 - Excelente</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="review_text" class="form-label">Tu reseña</label>
                <textarea name="review_text" class="form-control" rows="4" maxlength="65535" required></textarea>
                <div class="form-text">Comparte tu experiencia con el vehículo.</div>
            </div>
            <button type="submit" name="submit_review" class="btn btn-primary">Enviar Reseña</button>
        </form>
        <h2 class="text-center mb-4">Reseñas recientes</h2>
        <?php if (!empty($recent_reviews)): ?>
            <div class="list-group">
                <?php foreach ($recent_reviews as $review): ?>
                    <div class="list-group-item">
                        <strong><?php echo htmlspecialchars($review['car_name'] . " (" . $review['year'] . ")"); ?></strong>
                        <span class="badge bg-success ms-2"><?php echo str_repeat("★", $review['score']); ?></span>
                        <p class="mb-1"><?php echo htmlspecialchars($review['rating']); ?></p>
                        <small class="text-muted">Por <?php echo htmlspecialchars($review['name']); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No hay reseñas aún.</p>
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary mt-4">Volver al Inicio</a>
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
