<?php
// ...existing code if any...
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informes y Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .contact-section { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 32px; }
        .social-links a { margin-right: 15px; font-size: 1.7rem; color: #444; transition: color 0.2s; }
        .social-links a:hover { color: #007bff; }
        .post-links a { display: block; margin-bottom: 8px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Agencia Elmas Capitos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="inventory.php">Inventario</a></li>
                    <li class="nav-item"><a class="nav-link" href="new_appointment.php">Prueba de coche</a></li>
                    <!-- Agregado: opción de Informes -->
                    <li class="nav-item"><a class="nav-link active" href="contacts.php">Informes</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="contact-section">
        <h2 class="mb-4 text-center">Contáctanos e Informes</h2>
        <div class="mb-4 text-center">
            <a href="mailto:informes@elmascapitos.com?subject=Solicitud%20de%20informes" class="btn btn-primary">
                Enviar correo a informes@elmascapitos.com
            </a>
        </div>
        <h5 class="mb-3">Síguenos en redes sociales:</h5>
        <div class="social-links mb-4">
            <a href="https://facebook.com/elmascapitos" target="_blank" title="Facebook">
                <svg width="28" height="28" fill="currentColor" viewBox="0 0 16 16"><path d="M8.94 8.5H10.5V16H7.5V8.5H6V6.5h1.5V5.5c0-1.1.9-2 2-2h1.5v2H10c-.28 0-.5.22-.5.5v1h2l-.5 2H9.5v7.5H8.5V8.5z"/></svg>
            </a>
            <a href="https://twitter.com/elmascapitos" target="_blank" title="Twitter">
                <svg width="28" height="28" fill="currentColor" viewBox="0 0 16 16"><path d="M16 3.039a6.461 6.461 0 0 1-1.885.516A3.301 3.301 0 0 0 15.555 1.8a6.533 6.533 0 0 1-2.084.797A3.286 3.286 0 0 0 7.88 4.03c0 .258.03.51.085.75A9.325 9.325 0 0 1 1.112 2.1a3.284 3.284 0 0 0 1.018 4.381A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115c-.211 0-.417-.02-.616-.058a3.293 3.293 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.142-.004-.284-.01-.425A6.673 6.673 0 0 0 16 3.039z"/></svg>
            </a>
            <a href="https://instagram.com/elmascapitos" target="_blank" title="Instagram">
                <svg width="28" height="28" fill="currentColor" viewBox="0 0 16 16"><path d="M8 3c1.657 0 1.85.006 2.5.036.637.03.98.14 1.21.23.27.1.46.22.66.42.2.2.32.39.42.66.09.23.2.57.23 1.21.03.65.036.84.036 2.5s-.006 1.85-.036 2.5c-.03.637-.14.98-.23 1.21-.1.27-.22.46-.42.66-.2.2-.39.32-.66.42-.23.09-.57.2-1.21.23-.65.03-.84.036-2.5.036s-1.85-.006-2.5-.036c-.637-.03-.98-.14-1.21-.23-.27-.1-.46-.22-.66-.42-.2-.2-.32-.39-.42-.66-.09-.23-.2-.57-.23-1.21C3.006 9.85 3 9.66 3 8s.006-1.85.036-2.5c.03-.637.14-.98.23-1.21.1-.27.22-.46.42-.66.2-.2.39-.32.66-.42.23-.09.57-.2 1.21-.23C6.15 3.006 6.34 3 8 3zm0-1.5C6.34 1.5 6.15 1.506 5.5 1.536c-.72.033-1.21.15-1.64.32-.47.18-.86.42-1.25.81-.39.39-.63.78-.81 1.25-.17.43-.287.92-.32 1.64C1.506 6.15 1.5 6.34 1.5 8c0 1.66.006 1.85.036 2.5.033.72.15 1.21.32 1.64.18.47.42.86.81 1.25.39.39.78.63 1.25.81.43.17.92.287 1.64.32C6.15 14.494 6.34 14.5 8 14.5c1.66 0 1.85-.006 2.5-.036.72-.033 1.21-.15 1.64-.32.47-.18.86-.42 1.25-.81.39-.39.63-.78.81-1.25.17-.43.287-.92.32-1.64.03-.65.036-.84.036-2.5 0-1.66-.006-1.85-.036-2.5-.033-.72-.15-1.21-.32-1.64-.18-.47-.42-.86-.81-1.25-.39-.39-.78-.63-1.25-.81-.43-.17-.92-.287-1.64-.32C9.85 1.506 9.66 1.5 8 1.5z"/><circle cx="8" cy="8" r="3"/><circle cx="12.5" cy="3.5" r="1"/></svg>
            </a>
        </div>
        <h5 class="mb-3">Posts recomendados:</h5>
        <div class="post-links">
            <a href="https://blogcarros.com/como-elegir-tu-auto" target="_blank">Cómo elegir tu auto ideal</a>
            <a href="https://blogcarros.com/mantenimiento-basico" target="_blank">Mantenimiento básico de tu coche</a>
            <a href="https://blogcarros.com/tendencias-2024" target="_blank">Tendencias de autos 2024</a>
            <a href="https://blogcarros.com/financiamiento" target="_blank">Opciones de financiamiento para autos</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
