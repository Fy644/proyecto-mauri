<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "agencia";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Error: " . $conn->connect_error);
        exit();
    }

    $search = '';
    $results = [];

    if (isset($_GET['q']) && trim($_GET['q']) !== '') {
        $search = trim($_GET['q']);
        $stmt = $conn->prepare("SELECT * FROM carros WHERE (name LIKE CONCAT('%', ?, '%') OR year LIKE CONCAT('%', ?, '%') OR type LIKE CONCAT('%', ?, '%')) AND deleted = 0");
        $stmt->bind_param("sss", $search, $search, $search);
        $stmt->execute();
        $results = $stmt->get_result();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Buscar Coches</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .search-bar { max-width: 500px; margin: 40px auto 20px auto; }
        .navbar-search-form {
            display: none;
            position: absolute;
            right: 60px;
            top: 10px;
            z-index: 2000;
            background: #fff;
            padding: 8px 12px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .search-icon-btn {
            background: none;
            border: none;
            color: #333;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 0 8px;
        }
        @media (max-width: 991px) {
            .navbar-search-form {
                position: static;
                margin-top: 10px;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light position-relative">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Agencia Elmas Capitos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="inventory.php">Inventario</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="new_appointment.php">Prueba de coche</a>
                    </li>
                    <li class="nav-item">
                        <button class="search-icon-btn nav-link" id="showSearchBtn" title="Buscar">
                            <!-- Lupa Feather Icons: más clara y moderna -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </li>
                </ul>
            </div>
            <form class="navbar-search-form" id="navbarSearchForm" method="get" action="search.php">
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Buscar coche..." required>
                    <button class="btn btn-primary" type="submit">Buscar</button>
                    <button class="btn btn-secondary" type="button" id="closeSearchBtn">&times;</button>
                </div>
            </form>
        </div>
    </nav>
    <div class="container">
        <h1 class="text-center mt-4">
            <!-- Lupa Feather Icons grande para el título -->
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </h1>
        <form class="search-bar" method="get" action="search.php">
            <div class="input-group">
                <input type="text" class="form-control" name="q" placeholder="Nombre, año o tipo..." value="<?php echo htmlspecialchars($search); ?>" required>
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </form>
        <?php if (isset($_GET['q'])): ?>
            <h4 class="mt-4">Resultados para "<?php echo htmlspecialchars($search); ?>"</h4>
            <?php if ($results && $results->num_rows > 0): ?>
                <table class="table table-striped mt-3">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Año</th>
                            <th>Tipo</th>
                            <th>Precio</th>
                            <th>Ver</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($car = $results->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($car['name']); ?></td>
                                <td><?php echo htmlspecialchars($car['year']); ?></td>
                                <td><?php echo htmlspecialchars(ucfirst($car['type'])); ?></td>
                                <td>$<?php echo number_format($car['price']); ?></td>
                                <td><a href="view_car.php?id=<?php echo $car['id']; ?>" class="btn btn-sm btn-info">Ver</a></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning mt-3">No se encontraron coches.</div>
            <?php endif; ?>
        <?php endif; ?>
        <a href="index.php" class="btn btn-secondary mt-4">Volver al inicio</a>
    </div>
    <script>
        const showSearchBtn = document.getElementById('showSearchBtn');
        const navbarSearchForm = document.getElementById('navbarSearchForm');
        const closeSearchBtn = document.getElementById('closeSearchBtn');
        showSearchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            navbarSearchForm.style.display = 'block';
            navbarSearchForm.querySelector('input[name="q"]').focus();
        });
        closeSearchBtn.addEventListener('click', function() {
            navbarSearchForm.style.display = 'none';
        });
        // Optional: Hide search form when clicking outside
        document.addEventListener('click', function(e) {
            if (!navbarSearchForm.contains(e.target) && e.target !== showSearchBtn) {
                navbarSearchForm.style.display = 'none';
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
