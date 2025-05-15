<?php
    session_start();
    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: login.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panel de Administración</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            .admin-container {
                display: flex;
                flex-wrap: wrap; /* Allow wrapping */
                gap: 15px; /* Add spacing between items */
                justify-content: center; /* Center items horizontally */
                max-width: 800px; /* Increase the container width */
                margin: 50px auto;
            }
            .admin-box {
                flex: 0 0 calc(50% - 15px); /* Two items per row */
                padding: 30px; /* Increase padding for better spacing */
                border: 1px solid #ddd;
                border-radius: 8px;
                background-color: #f8f9fa;
                text-align: center;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .admin-box a {
                text-decoration: none;
                font-size: 1.4rem; /* Increase font size for better readability */
                color: #007bff;
            }
            .admin-box a:hover {
                text-decoration: underline;
                color: #0056b3;
            }
            .content {
                margin-left: 250px;
                padding: 20px;
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
        <?php include 'navbar.php'; ?>
        <div class="content">
            <div class="container mt-4">
                <h1 class="text-center">¡Bienvenido, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h1>
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
