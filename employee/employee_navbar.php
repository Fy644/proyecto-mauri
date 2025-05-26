<?php
    session_start();
    $level = isset($_SESSION['employee_level']) ? intval($_SESSION['employee_level']) : null;
    $name = isset($_SESSION['employee_name']) ? $_SESSION['employee_name'] : '';
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                margin: 0;
                background-color: #f8f9fa;
            }
            .sidebar {
                width: 250px;
                background-color: #343a40;
                color: #ffffff;
                display: flex;
                flex-direction: column;
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 1050;
                transition: transform 0.3s;
            }
            .sidebar-header {
                padding: 15px;
                font-size: 1.5rem;
                text-align: center;
                background-color: #23272b;
            }
            .sidebar a {
                color: #ffffff;
                text-decoration: none;
                padding: 10px 15px;
                display: block;
                transition: background-color 0.2s;
            }
            .sidebar a:hover {
                background-color: #495057;
            }
            .sidebar-name {
                margin-top: auto;
                padding: 10px;
                text-align: center;
                background-color: #23272b;
            }
            .toggle-btn {
                display: none;
            }
            .sidebar-overlay {
                display: none;
            }
            .content {
                margin-left: 250px;
                padding: 20px;
                transition: margin-left 0.3s;
            }
            @media (max-width: 768px) {
                .sidebar {
                    width: 100%;
                    height: auto;
                    position: fixed;
                    top: 0;
                    left: 0;
                    transform: translateY(-100%);
                    z-index: 1050;
                }
                .sidebar.collapsed {
                    transform: translateY(0);
                }
                .content {
                    margin-left: 0;
                    margin-top: 50px;
                    z-index: 1;
                }
                .toggle-btn {
                    display: block;
                    position: fixed;
                    top: 10px;
                    left: 10px;
                    background-color: #495057;
                    color: #ffffff;
                    border: none;
                    padding: 6px 10px;
                    border-radius: 5px;
                    cursor: pointer;
                    z-index: 1100;
                    font-size: 0.9rem;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                }
                .toggle-btn:hover {
                    background-color: #343a40;
                }
                .sidebar-overlay {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100vw;
                    height: 100vh;
                    background: rgba(0,0,0,0.3);
                    z-index: 1049;
                }
                .sidebar.open + .sidebar-overlay {
                    display: block;
                }
            }
        </style>
    </head>
    <body>
        <button class="toggle-btn" id="toggle-btn">☰</button>
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">Empleado</div>
            <a href="comisiones.php">Comisiones</a>
            <?php if ($level !== null && $level >= 4): ?>
                <a href="servicios.php">Servicios</a>
            <?php endif; ?>
            <a href="chat.php">Chat</a>
            <a href="../logout.php">Cerrar Sesión</a>
            <div class="sidebar-name">
                <?php echo htmlspecialchars($name); ?>
            </div>
        </div>
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
        <script>
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggle-btn');
            const overlay = document.getElementById('sidebar-overlay');
            function openSidebar() {
                sidebar.classList.add('open');
                sidebar.classList.add('collapsed');
                overlay.style.display = 'block';
            }
            function closeSidebar() {
                sidebar.classList.remove('open');
                sidebar.classList.remove('collapsed');
                overlay.style.display = 'none';
            }
            toggleBtn.addEventListener('click', function() {
                if (sidebar.classList.contains('open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
            overlay.addEventListener('click', closeSidebar);
            function handleResize() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('collapsed');
                    sidebar.classList.remove('open');
                    overlay.style.display = 'none';
                } else {
                    sidebar.classList.remove('open');
                    sidebar.classList.remove('collapsed');
                    overlay.style.display = 'none';
                }
            }
            window.addEventListener('resize', handleResize);
            window.addEventListener('DOMContentLoaded', handleResize);
        </script>
    </body>
</html>
