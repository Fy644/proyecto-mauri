<?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "agencia";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Error: " . $conn->connect_error);
        exit();
    }

    if (!isset($_GET['id'])) {
        die("Error: No se proporcionó el ID del coche.");
    }

    $car_id = intval($_GET['id']);
    $car = $conn->query("SELECT * FROM carros WHERE id = $car_id AND deleted = 0")->fetch_assoc();

    if (!$car) {
        die("Error: Coche no encontrado.");
    }

    $login_error = null;
    $show_login_popup = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
        $user_username = $_POST['username'];
        $user_password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($user_password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: view_car.php?id=$car_id");
                exit();
            } else {
                $login_error = "Contraseña incorrecta.";
                $show_login_popup = true;
            }
        } else {
            $login_error = "Usuario no encontrado.";
            $show_login_popup = true;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
        session_destroy();
        header("Location: view_car.php?id=$car_id");
        exit();
    }

    // Fetch user data if logged in
    $user_data = [];
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT profile_picture FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user_data = $result->fetch_assoc();
        }
    }

    // Fetch review counts for chart
$review_counts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
$review_count_sql = "SELECT score, COUNT(*) as cnt FROM reviews WHERE id_car = $car_id AND deleted = 0 GROUP BY score";
$review_count_res = $conn->query($review_count_sql);
if ($review_count_res) {
    while ($row = $review_count_res->fetch_assoc()) {
        $score = intval($row['score']);
        $review_counts[$score] = intval($row['cnt']);
    }
}

// Calculate average score
$avg_score = null;
$avg_score_sql = "SELECT AVG(score) as avg_score, COUNT(*) as total_reviews FROM reviews WHERE id_car = $car_id AND deleted = 0";
$avg_score_res = $conn->query($avg_score_sql);
if ($avg_score_res && $avg_score_res->num_rows > 0) {
    $row = $avg_score_res->fetch_assoc();
    $avg_score = $row['avg_score'] !== null ? round($row['avg_score'], 2) : null;
    $total_reviews = intval($row['total_reviews']);
} else {
    $avg_score = null;
    $total_reviews = 0;
}

// Handle review filter
$filter_score = isset($_GET['filter_score']) ? intval($_GET['filter_score']) : 0;
$filter_sql = "SELECT rating, name, score FROM reviews WHERE id_car = $car_id AND deleted = 0";
if ($filter_score >= 1 && $filter_score <= 5) {
    $filter_sql .= " AND score = $filter_score";
}
$filter_sql .= " ORDER BY id DESC";
$reviews_res = $conn->query($filter_sql);
$reviews = [];
if ($reviews_res) {
    while ($row = $reviews_res->fetch_assoc()) {
        $reviews[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($car['name']); ?> - Detalles del Coche</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <style>
            body {
                background-color: #f8f9fa; /* Light gray background */
            }
            .car-image {
                max-width: 100%;
                height: auto;
                border-radius: 8px;
            }
            .navbar {
                background-color: #343a40; /* Dark gray for navbar */
            }
            .navbar-brand, .nav-link {
                color: #ffffff !important; /* White text for navbar links */
            }
            .btn-success {
                background-color: #007bff; /* Blue for buttons */
                border: none;
            }
            .btn-success:hover {
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
    </head>
    <body>
        <?php include 'user_navbar.php'; ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-6">
                    <img src="images/<?php echo htmlspecialchars($car['img_name']); ?>.png" alt="<?php echo htmlspecialchars($car['name']); ?>" class="car-image">
                </div>
                <div class="col-md-6">
                    <h1><?php echo htmlspecialchars($car['name']); ?> (<?php echo $car['year']; ?>)</h1>
                    <p><strong>Precio:</strong> $<?php echo number_format($car['price']); ?></p>
                    <p><strong>Tipo:</strong> <?php echo ucfirst($car['type']); ?></p>
                    <p><strong>Usado:</strong> <?php echo $car['used'] ? "Sí" : "No"; ?></p>
                    <p><strong>Descripción:</strong> <?php echo htmlspecialchars($car['description']); ?></p>
                    <a href="buy_car.php?id=<?php echo $car['id']; ?>" class="btn btn-success">Comprar Este Coche</a>
                    <a href="javascript:history.back()" class="btn btn-secondary">Regresar</a>
                </div>
            </div>
            <hr>
            <h3 class="mt-4 mb-3">Valoraciones de usuarios</h3>
            <div class="d-flex align-items-center mb-2" style="gap: 24px;">
                <div id="review-chart" style="width:100%;max-width:500px;height:300px;"></div>
                <div>
                    <div class="fs-5">
                        <strong>Promedio:</strong>
                        <?php if ($avg_score !== null): ?>
                            <span class="text-warning" style="font-size:1.5em;"><?php echo number_format($avg_score, 2); ?> ★</span>
                            <span class="text-muted" style="font-size:0.9em;">(<?php echo $total_reviews; ?> reseña<?php echo $total_reviews == 1 ? '' : 's'; ?>)</span>
                        <?php else: ?>
                            <span class="text-muted">Sin reseñas</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <form method="get" class="mb-3">
                <input type="hidden" name="id" value="<?php echo $car_id; ?>">
                <label for="filter_score" class="form-label">Filtrar reseñas por puntuación:</label>
                <select name="filter_score" id="filter_score" class="form-select" style="width:auto;display:inline-block;" onchange="this.form.submit()">
                    <option value="0" <?php if ($filter_score == 0) echo 'selected'; ?>>Todas</option>
                    <option value="5" <?php if ($filter_score == 5) echo 'selected'; ?>>5 estrellas</option>
                    <option value="4" <?php if ($filter_score == 4) echo 'selected'; ?>>4 estrellas</option>
                    <option value="3" <?php if ($filter_score == 3) echo 'selected'; ?>>3 estrellas</option>
                    <option value="2" <?php if ($filter_score == 2) echo 'selected'; ?>>2 estrellas</option>
                    <option value="1" <?php if ($filter_score == 1) echo 'selected'; ?>>1 estrella</option>
                </select>
            </form>
            <div>
                <?php if (count($reviews) > 0): ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="border rounded p-2 mb-2">
                            <span class="badge bg-success"><?php echo str_repeat("★", $review['score']); ?></span>
                            <em><?php echo htmlspecialchars($review['rating']); ?></em>
                            <br>
                            <small class="text-muted">Por <?php echo htmlspecialchars($review['name']); ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No hay reseñas para este coche<?php echo ($filter_score ? " con esa puntuación" : ""); ?>.</p>
                <?php endif; ?>
            </div>
        </div>
        <a href="login.php" class="admin-login">Admin Login</a>
        <script>
            // Automatically show login popup if login error exists
            <?php if ($show_login_popup): ?>
                document.addEventListener('DOMContentLoaded', function () {
                    toggleUserLogin();
                });
            <?php endif; ?>

            // Highcharts bar chart for review counts
            document.addEventListener('DOMContentLoaded', function () {
                Highcharts.chart('review-chart', {
                    chart: { type: 'column' },
                    title: { text: 'Distribución de valoraciones' },
                    xAxis: {
                        categories: ['1 estrella', '2 estrellas', '3 estrellas', '4 estrellas', '5 estrellas'],
                        title: { text: 'Puntuación' }
                    },
                    yAxis: {
                        min: 0,
                        allowDecimals: false,
                        title: { text: 'Cantidad de usuarios' }
                    },
                    series: [{
                        name: 'Usuarios',
                        data: [
                            <?php echo $review_counts[1]; ?>,
                            <?php echo $review_counts[2]; ?>,
                            <?php echo $review_counts[3]; ?>,
                            <?php echo $review_counts[4]; ?>,
                            <?php echo $review_counts[5]; ?>
                        ],
                        colorByPoint: true
                    }],
                    legend: { enabled: false }
                });
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
