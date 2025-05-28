<?php
    session_start();
    if (!isset($_SESSION['employee_logged_in']) || !$_SESSION['employee_logged_in']) {
        header("Location: ../employee_login.php");
        exit();
    }
    $employee_id = $_SESSION['employee_id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "agencia";
    $conn = new mysqli($servername, $username, $password, $database);
    if ($conn->connect_error) {
        die("Error: " . $conn->connect_error);
    }

    // Handle updates to date_finish and date_pickup
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_service'])) {
        $service_id = intval($_POST['service_id']);
        $date_finish = !empty($_POST['date_finish']) ? $_POST['date_finish'] : null;
        $date_pickup = !empty($_POST['date_pickup']) ? $_POST['date_pickup'] : null;

        $stmt = $conn->prepare("UPDATE service SET date_finish = ?, date_pickup = ? WHERE id = ? AND id_employee = ?");
        $stmt->bind_param("ssii", $date_finish, $date_pickup, $service_id, $employee_id);
        $stmt->execute();
        $stmt->close();
    }

    // Fetch services for this employee, join with users for name
    $sql = "SELECT s.*, u.fullname AS user_name 
            FROM service s 
            LEFT JOIN users u ON s.id_user = u.id 
            WHERE s.id_employee = ? AND s.deleted = 0
            ORDER BY s.id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $services = [];
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
    $stmt->close();
    $conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Servicios Asignados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-badge { font-size: 0.95em; }
        .status-needs { background: #ffc107; color: #212529; }
        .status-waiting { background: #0dcaf0; color: #212529; }
        .status-finished { background: #198754; color: #fff; }
        .status-col { min-width: 180px; }
        /* Remove margin from table container, let .content handle spacing */
        .service-table-container { width: 100%; }
        @media (max-width: 768px) {
            .service-table-container { margin: 0; }
        }
    </style>
</head>
<body>
    <?php include("employee_navbar.php"); ?>
    <div class="content">
        <h2>Servicios Asignados</h2>
        <div class="service-table-container">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Auto (ID)</th>
                        <th>Problema</th>
                        <th>Fecha Solicitud</th>
                        <th>Fecha Terminado</th>
                        <th>Fecha Entregado</th>
                        <th class="status-col">Estado</th>
                        <th>Garantía</th>
                        <th>Actualizar</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($services as $service): ?>
                    <tr>
                        <td><?= htmlspecialchars($service['id']) ?></td>
                        <td><?= htmlspecialchars($service['user_name'] ?: 'Usuario #'.$service['id_user']) ?></td>
                        <td><?= htmlspecialchars($service['id_car']) ?></td>
                        <td><?= htmlspecialchars($service['problem']) ?></td>
                        <td><?= htmlspecialchars($service['date_request']) ?></td>
                        <td>
                            <form method="post" style="display:inline;">
                                <input type="date" name="date_finish" value="<?= htmlspecialchars($service['date_finish']) ?>" class="form-control form-control-sm" style="width:140px;" />
                        </td>
                        <td>
                                <input type="date" name="date_pickup" value="<?= htmlspecialchars($service['date_pickup']) ?>" class="form-control form-control-sm" style="width:140px;" />
                        </td>
                        <td>
                            <?php
                                if (empty($service['date_finish'])) {
                                    echo '<span class="badge status-badge status-needs">Necesita trabajo</span>';
                                } elseif (empty($service['date_pickup'])) {
                                    echo '<span class="badge status-badge status-waiting">Terminado, esperando entrega</span>';
                                } else {
                                    echo '<span class="badge status-badge status-finished">Entregado y finalizado</span>';
                                }
                            ?>
                        </td>
                        <td>
                            <?php if ($service['waranty'] == 1): ?>
                                <span class="badge bg-success">En garantía</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Sin garantía</span>
                            <?php endif; ?>
                        </td>
                        <td>
                                <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
                                <button type="submit" name="update_service" class="btn btn-sm btn-primary">Guardar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($services)): ?>
                    <tr><td colspan="10" class="text-center">No hay servicios asignados.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
