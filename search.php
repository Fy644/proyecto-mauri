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

$search_results = [];
$search_query = "";

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $search_query = trim($_GET['q']);
    // Only search in the name field
    $search_term = "%" . $search_query . "%";
    
    $sql = "SELECT * FROM carros WHERE LOWER(name) LIKE LOWER(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $search_results[] = $row;
    }
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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de búsqueda - Agencia Lou-Lou</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            background-color: #ffffff;
            border: none;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card-img-top {
            object-fit: cover;
            width: 100%;
            height: 200px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .navbar {
            background-color: #343a40;
        }
        .navbar-brand, .nav-link {
            color: #ffffff !important;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
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
    </style>
</head>
<body>
    <?php include 'user_navbar.php'; ?>
    
    <div class="container mt-4">
        <h1 class="text-center mb-4">
            <?php if (!empty($search_query)): ?>
                Resultados de búsqueda para "<?php echo htmlspecialchars($search_query); ?>"
            <?php else: ?>
                Búsqueda de coches
            <?php endif; ?>
        </h1>

        <?php if (empty($search_results) && !empty($search_query)): ?>
            <div class="alert alert-info text-center">
                No se encontraron resultados para "<?php echo htmlspecialchars($search_query); ?>"
            </div>
        <?php endif; ?>

        <div class="row">
            <?php foreach ($search_results as $car): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="images/<?php echo htmlspecialchars($car['img_name']); ?>.png" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($car['name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo htmlspecialchars($car['name']); ?> 
                                (<?php echo htmlspecialchars($car['year']); ?>)
                            </h5>
                            <p class="card-text">
                                <?php 
                                    $shortDescription = strlen($car['description']) > 40 
                                        ? substr($car['description'], 0, 40) . '...' 
                                        : $car['description'];
                                    echo htmlspecialchars($shortDescription);
                                ?>
                            </p>
                            <p class="card-text">
                                <strong>Precio:</strong> $<?php echo htmlspecialchars($car['price']); ?>
                            </p>
                            <p class="card-text">
                                <strong>Tipo:</strong> <?php echo htmlspecialchars(ucfirst($car['type'])); ?>
                            </p>
                            <a href="view_car.php?id=<?php echo $car['id']; ?>" 
                               class="btn btn-primary">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if (!empty($debug_info)): ?>
        <div class="container mt-4">
            <div class="alert alert-info">
                <h4>Debug Information:</h4>
                <pre><?php print_r($debug_info); ?></pre>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
