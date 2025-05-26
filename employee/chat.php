<?php
session_start();
require_once "../db.php";
if (!isset($_SESSION['employee_logged_in'])) {
    header("Location: ../login.php");
    exit();
}
$employee_id = $_SESSION['employee_id'];

// Start new chat with admin/employee
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_chat_type'])) {
    $type = $_POST['new_chat_type'];
    $target_id = intval($_POST['target_id']);
    $stmt = $conn->prepare("INSERT INTO chat_sessions (employee_id, admin_id) VALUES (?, ?)");
    if ($type === 'admin') {
        $stmt->bind_param("ii", $employee_id, $target_id);
    } else {
        // employee to employee chat
        $stmt->bind_param("ii", $employee_id, null);
    }
    $stmt->execute();
    $chat_id = $conn->insert_id;
    $_SESSION['employee_chat_id'] = $chat_id;
    header("Location: chat.php?chat_id=$chat_id");
    exit();
}

// Select chat session
if (isset($_GET['chat_id'])) {
    $_SESSION['employee_chat_id'] = intval($_GET['chat_id']);
}
$chat_id = $_SESSION['employee_chat_id'] ?? null;

// Handle sending message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && $chat_id) {
    $msg = trim($_POST['message']);
    if ($msg !== "") {
        // Find chat session details
        $session = $conn->query("SELECT * FROM chat_sessions WHERE id=$chat_id")->fetch_assoc();
        if ($session['user_id']) {
            $receiver_id = $session['user_id'];
            $conn->query("INSERT INTO chat_messages (sender_type, reciever_type, sender_id, receiver_id, message, created_at) VALUES ('employee', 'user', $employee_id, $receiver_id, '" . $conn->real_escape_string($msg) . "', NOW())");
        } elseif ($session['admin_id']) {
            $receiver_id = $session['admin_id'];
            $conn->query("INSERT INTO chat_messages (sender_type, reciever_type, sender_id, receiver_id, message, created_at) VALUES ('employee', 'admin', $employee_id, $receiver_id, '" . $conn->real_escape_string($msg) . "', NOW())");
        } elseif ($session['employee_id'] && $session['employee_id'] != $employee_id) {
            $receiver_id = $session['employee_id'];
            $conn->query("INSERT INTO chat_messages (sender_type, reciever_type, sender_id, receiver_id, message, created_at) VALUES ('employee', 'employee', $employee_id, $receiver_id, '" . $conn->real_escape_string($msg) . "', NOW())");
        }
    }
    header("Location: chat.php?chat_id=$chat_id");
    exit();
}

// List chats assigned to this employee
$chats = $conn->query("SELECT * FROM chat_sessions WHERE employee_id=$employee_id ORDER BY created_at DESC");
$messages = [];
if ($chat_id) {
    $session = $conn->query("SELECT * FROM chat_sessions WHERE id=$chat_id")->fetch_assoc();
    if ($session['user_id']) {
        $target_id = $session['user_id'];
        $result = $conn->query("SELECT * FROM chat_messages WHERE (sender_type='employee' AND sender_id=$employee_id AND receiver_id=$target_id) OR (sender_type='user' AND sender_id=$target_id AND receiver_id=$employee_id) ORDER BY created_at ASC");
    } elseif ($session['admin_id']) {
        $target_id = $session['admin_id'];
        $result = $conn->query("SELECT * FROM chat_messages WHERE (sender_type='employee' AND sender_id=$employee_id AND receiver_id=$target_id) OR (sender_type='admin' AND sender_id=$target_id AND receiver_id=$employee_id) ORDER BY created_at ASC");
    } else {
        $target_id = $session['employee_id'];
        $result = $conn->query("SELECT * FROM chat_messages WHERE (sender_type='employee' AND sender_id=$employee_id AND receiver_id=$target_id) OR (sender_type='employee' AND sender_id=$target_id AND receiver_id=$employee_id) ORDER BY created_at ASC");
    }
    while ($row = $result->fetch_assoc()) $messages[] = $row;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat de Empleado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .chat-container { max-width: 600px; margin: 40px auto; }
        .chat-box { background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 0; }
        .chat-header { padding: 20px; border-bottom: 1px solid #eee; }
        .chat-messages { padding: 20px; height: 350px; overflow-y: auto; }
        .message { margin-bottom: 10px; }
        .message.employee { text-align: right; }
        .message.user, .message.admin { text-align: left; }
        .message .bubble { display: inline-block; padding: 10px 15px; border-radius: 20px; }
        .message.employee .bubble { background: #007bff; color: #fff; }
        .message.user .bubble, .message.admin .bubble { background: #e9ecef; color: #333; }
        .chat-input-area { border-top: 1px solid #eee; padding: 15px 20px; }
        .chat-selector { padding: 20px; border-bottom: 1px solid #eee; }
        @media (max-width: 600px) {
            .chat-container { margin: 0; }
            .chat-box { border-radius: 0; }
        }
    </style>
</head>
<body>
    <?php include './employee_navbar.php'; ?>
    <div class="chat-container">
        <div class="chat-box mt-5">
            <div class="chat-header">
                <h4 class="mb-0">Chats Asignados</h4>
            </div>
            <div class="chat-selector">
                <?php while ($row = $chats->fetch_assoc()): ?>
                    <a href="?chat_id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm mb-1"><?= $row['topic'] ? htmlspecialchars($row['topic']) : 'Chat' ?> #<?= $row['id'] ?></a>
                <?php endwhile; ?>
            </div>
            <?php if ($chat_id): ?>
                <div id="messages" class="chat-messages">
                    <?php foreach ($messages as $msg): ?>
                        <div class="message <?= $msg['sender_type'] ?>">
                            <span class="bubble"><?= htmlspecialchars($msg['message']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <form id="chatForm" class="chat-input-area d-flex">
                    <input type="text" name="message" id="messageInput" class="form-control me-2" placeholder="Escribe tu mensaje..." autocomplete="off" required>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            <?php else: ?>
                <div class="p-4 text-center">Selecciona un chat para comenzar.</div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let currentChatId = null;
    let lastMessageId = 0;

    function loadMessages(chatId) {
        currentChatId = chatId;
        const chatBox = document.getElementById('messages');
        chatBox.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div></div>';
        
        fetch(`chat_messages.php?chat_id=${chatId}`)
            .then(response => response.text())
            .then(html => {
                chatBox.innerHTML = html;
                chatBox.scrollTop = chatBox.scrollHeight;
                // Get the last message ID for future updates
                const messages = chatBox.getElementsByClassName('message');
                if (messages.length > 0) {
                    lastMessageId = parseInt(messages[messages.length - 1].dataset.messageId);
                }
            });
    }

    function sendMessage() {
        if (!currentChatId) return;
        
        const messageInput = document.getElementById('message');
        const message = messageInput.value.trim();
        if (!message) return;

        const formData = new FormData();
        formData.append('chat_id', currentChatId);
        formData.append('message', message);

        fetch('chat_send.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(() => {
            messageInput.value = '';
            loadMessages(currentChatId);
        });
    }

    function checkNewMessages() {
        if (!currentChatId) return;
        
        fetch(`chat_messages.php?chat_id=${currentChatId}&last_id=${lastMessageId}`)
            .then(response => response.text())
            .then(html => {
                if (html.trim()) {
                    const chatBox = document.getElementById('messages');
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    const newMessages = tempDiv.getElementsByClassName('message');
                    
                    if (newMessages.length > 0) {
                        chatBox.innerHTML = html;
                        chatBox.scrollTop = chatBox.scrollHeight;
                        lastMessageId = parseInt(newMessages[newMessages.length - 1].dataset.messageId);
                    }
                }
            });
    }

    // Check for new messages every 2 seconds
    setInterval(checkNewMessages, 2000);

    // Handle enter key in message input
    document.getElementById('message').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
    </script>
</body>
</html>
