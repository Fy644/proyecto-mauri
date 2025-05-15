<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                display: flex;
                margin: 0;
                background-color: #f8f9fa; /* Light gray background */
            }
            .sidebar {
                width: 250px;
                background-color: #343a40; /* Dark gray for sidebar */
                color: #ffffff;
                display: flex;
                flex-direction: column;
                position: fixed;
                top: 0;
                bottom: 0;
                left: 0;
                z-index: 1050; /* Ensure it overlays content */
            }
            .sidebar-header {
                padding: 15px;
                font-size: 1.5rem;
                text-align: center;
                background-color: #23272b; /* Slightly darker for header */
            }
            .sidebar a {
                color: #ffffff;
                text-decoration: none;
                padding: 10px 15px;
                display: block;
                transition: background-color 0.2s;
            }
            .sidebar a:hover {
                background-color: #495057; /* Slightly lighter gray on hover */
            }
            .content {
                margin-left: 250px;
                flex-grow: 1;
                padding: 20px;
            }
            .toggle-btn {
                display: none; /* Hide toggle button by default */
            }

            /* Mobile styles */
            @media (max-width: 768px) {
                .sidebar {
                    width: 100%;
                    height: auto;
                    position: fixed;
                    top: 0;
                    left: 0;
                    transform: translateY(-100%);
                    z-index: 1050; /* Ensure it overlays content */
                }
                .sidebar.collapsed {
                    transform: translateY(0);
                }
                .content {
                    margin-left: 0;
                    margin-top: 50px; /* Adjust for the height of the navbar */
                    z-index: 1; /* Ensure content is below the sidebar */
                }
                .toggle-btn {
                    display: block; /* Show toggle button on mobile */
                    position: fixed;
                    top: 10px;
                    left: 10px;
                    background-color: #495057; /* Match sidebar hover color */
                    color: #ffffff;
                    border: none;
                    padding: 6px 10px; /* Smaller size */
                    border-radius: 5px;
                    cursor: pointer;
                    z-index: 1100; /* Ensure it appears over the menu */
                    font-size: 0.9rem; /* Slightly smaller font size */
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                }
                .toggle-btn:hover {
                    background-color: #343a40; /* Match sidebar color */
                }
            }
        </style>
    </head>
    <body>
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">Admin Panel</div>
            <a href="new_car.php">Agregar Nuevo Coche</a>
            <a href="edit_car.php">Editar Coche</a>
            <a href="new_admin.php">Agregar Nuevo Administrador</a>
            <a href="edit_admin.php">Editar Administrador</a>
            <a href="new_employee.php">Agregar Nuevo Empleado</a>
            <a href="edit_employee.php">Editar Empleado</a>
            <a href="view_appointments.php">Ver Citas</a>
            <a href="sales.php">Ver Ventas</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
        <button class="toggle-btn" id="toggle-btn">☰</button>
        <script>
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggle-btn');

            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('collapsed');
            });
        </script>
    </body>
</html>
