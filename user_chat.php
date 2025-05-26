<?php
session_start();
require_once "db.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Find or create chat session
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['topic'])) {
    $topic = $_POST['topic'];
    // Assign random employee
    $result = $conn->query("SELECT id FROM employees WHERE deleted=0 ORDER BY RAND() LIMIT 1");
    $row = $result->fetch_assoc();
    $employee_id = $row['id'];
    $stmt = $conn->prepare("INSERT INTO chat_sessions (user_id, employee_id, topic) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $employee_id, $topic);
    $stmt->execute();
    $chat_id = $conn->insert_id;
    $_SESSION['chat_id'] = $chat_id;
    header("Location: user_chat.php");
    exit();
} elseif (isset($_SESSION['chat_id'])) {
    $chat_id = $_SESSION['chat_id'];
} else {
    // Find existing chat
    $result = $conn->query("SELECT id FROM chat_sessions WHERE user_id=$user_id ORDER BY created_at DESC LIMIT 1");
    if ($row = $result->fetch_assoc()) {
        $chat_id = $row['id'];
        $_SESSION['chat_id'] = $chat_id;
    } else {
        $chat_id = null;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat de Usuario</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background-color: #343a40; }
        .navbar-brand, .nav-link { color: #ffffff !important; }
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
        .chat-container { max-width: 600px; margin: 40px auto; }
        .chat-box { background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 0; }
        .chat-header { padding: 20px; border-bottom: 1px solid #eee; }
        .chat-messages { padding: 20px; height: 350px; overflow-y: auto; }
        .message { margin-bottom: 10px; }
        .message.user { text-align: right; }
        .message.employee { text-align: left; }
        .message .bubble { display: inline-block; padding: 10px 15px; border-radius: 20px; }
        .message.user .bubble { background: #007bff; color: #fff; }
        .message.employee .bubble { background: #e9ecef; color: #333; }
        .chat-input-area { border-top: 1px solid #eee; padding: 15px 20px; }
        @media (max-width: 600px) {
            .chat-container { margin: 0; }
            .chat-box { border-radius: 0; }
        }
    </style>
</head>
<body>
    <?php include 'user_navbar.php'; ?>
    <div class="chat-container">
        <div class="chat-box mt-5">
            <div class="chat-header">
                <h4 class="mb-0">Chat de Soporte</h4>
            </div>
            <?php if (!$chat_id): ?>
                <form method="post" class="p-4">
                    <label for="topic" class="form-label">¿Sobre qué necesitas ayuda?</label>
                    <select name="topic" id="topic" class="form-select mb-3" required>
                        <option value="">Selecciona una opción</option>
                        <option value="car_help">Ayuda para elegir un coche</option>
                        <option value="service_update">Actualización sobre mi servicio</option>
                    </select>
                    <button type="submit" class="btn btn-primary w-100">Iniciar Chat</button>
                </form>
            <?php else: ?>
                <div id="messages" class="chat-messages"></div>
                <form id="chatForm" class="chat-input-area d-flex">
                    <input type="text" name="message" id="messageInput" class="form-control me-2" placeholder="Escribe tu mensaje..." autocomplete="off" required>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php if ($chat_id): ?>
    <script>
        function fetchMessages() {
            fetch('user_chat_messages.php?chat_id=<?= $chat_id ?>')
                .then(res => res.text())
                .then(html => {
                    const messagesDiv = document.getElementById('messages');
                    const atBottom = messagesDiv.scrollTop + messagesDiv.clientHeight >= messagesDiv.scrollHeight - 10;
                    messagesDiv.innerHTML = html;
                    if (atBottom) {
                        messagesDiv.scrollTop = messagesDiv.scrollHeight;
                    }
                });
        }
        document.addEventListener('DOMContentLoaded', function() {
            fetchMessages();
            setInterval(fetchMessages, 2000);

            document.getElementById('chatForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const msg = document.getElementById('messageInput').value.trim();
                if (!msg) return;
                fetch('user_chat_send.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'chat_id=<?= $chat_id ?>&message=' + encodeURIComponent(msg)
                }).then(() => {
                    document.getElementById('messageInput').value = '';
                    fetchMessages();
                });
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>
