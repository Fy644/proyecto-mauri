<?php
session_start();
require_once "../db.php";
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
$admin_id = $_SESSION['admin_id'];

// Start new chat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['target_type'])) {
    $type = $_POST['target_type'];
    $target_id = intval($_POST['target_id']);
    if ($type === 'user') {
        $stmt = $conn->prepare("INSERT INTO chat_sessions (admin_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $admin_id, $target_id);
    } elseif ($type === 'employee') {
        $stmt = $conn->prepare("INSERT INTO chat_sessions (admin_id, employee_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $admin_id, $target_id);
    } else {
        $stmt = $conn->prepare("INSERT INTO chat_sessions (admin_id) VALUES (?)");
        $stmt->bind_param("i", $admin_id);
    }
    $stmt->execute();
    $chat_id = $conn->insert_id;
    $_SESSION['admin_chat_id'] = $chat_id;
    header("Location: admin_chat.php?chat_id=$chat_id");
    exit();
}

// Select chat session
if (isset($_GET['chat_id'])) {
    $_SESSION['admin_chat_id'] = intval($_GET['chat_id']);
}
$chat_id = $_SESSION['admin_chat_id'] ?? null;

// Handle sending message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && $chat_id) {
    $msg = trim($_POST['message']);
    if ($msg !== "") {
        $session = $conn->query("SELECT * FROM chat_sessions WHERE id=$chat_id")->fetch_assoc();
        if ($session['user_id']) {
            $receiver_id = $session['user_id'];
            $conn->query("INSERT INTO chat_messages (sender_type, reciever_type, sender_id, receiver_id, message, created_at) VALUES ('admin', 'user', $admin_id, $receiver_id, '" . $conn->real_escape_string($msg) . "', NOW())");
        } elseif ($session['employee_id']) {
            $receiver_id = $session['employee_id'];
            $conn->query("INSERT INTO chat_messages (sender_type, reciever_type, sender_id, receiver_id, message, created_at) VALUES ('admin', 'employee', $admin_id, $receiver_id, '" . $conn->real_escape_string($msg) . "', NOW())");
        } else {
            // admin to admin
            $conn->query("INSERT INTO chat_messages (sender_type, reciever_type, sender_id, receiver_id, message, created_at) VALUES ('admin', 'admin', $admin_id, $admin_id, '" . $conn->real_escape_string($msg) . "', NOW())");
        }
    }
    header("Location: admin_chat.php?chat_id=$chat_id");
    exit();
}

// List all chats
$chats = $conn->query("SELECT * FROM chat_sessions WHERE admin_id=$admin_id ORDER BY created_at DESC");
$messages = [];
if ($chat_id) {
    $session = $conn->query("SELECT * FROM chat_sessions WHERE id=$chat_id")->fetch_assoc();
    if ($session['user_id']) {
        $target_id = $session['user_id'];
        $result = $conn->query("SELECT * FROM chat_messages WHERE (sender_type='admin' AND sender_id=$admin_id AND receiver_id=$target_id) OR (sender_type='user' AND sender_id=$target_id AND receiver_id=$admin_id) ORDER BY created_at ASC");
    } elseif ($session['employee_id']) {
        $target_id = $session['employee_id'];
        $result = $conn->query("SELECT * FROM chat_messages WHERE (sender_type='admin' AND sender_id=$admin_id AND receiver_id=$target_id) OR (sender_type='employee' AND sender_id=$target_id AND receiver_id=$admin_id) ORDER BY created_at ASC");
    } else {
        $result = $conn->query("SELECT * FROM chat_messages WHERE (sender_type='admin' AND sender_id=$admin_id) ORDER BY created_at ASC");
    }
    while ($row = $result->fetch_assoc()) $messages[] = $row;
}

// For new chat: list users/employees
$users = $conn->query("SELECT id, username FROM users");
$employees = $conn->query("SELECT id, name FROM employees WHERE deleted=0");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat de Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .chat-box { background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); padding: 20px; max-width: 600px; margin: 40px auto; }
        .message { margin-bottom: 10px; }
        .message.admin { text-align: right; }
        .message.user, .message.employee { text-align: left; }
        .message .bubble { display: inline-block; padding: 10px 15px; border-radius: 20px; }
        .message.admin .bubble { background: #007bff; color: #fff; }
        .message.user .bubble, .message.employee .bubble { background: #e9ecef; color: #333; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="chat-box">
            <h4 class="mb-3">Chats de Admin</h4>
            <form method="post" class="mb-3">
                <div class="row g-2">
                    <div class="col">
                        <select name="target_type" class="form-select" required>
                            <option value="">Nuevo chat con...</option>
                            <option value="user">Usuario</option>
                            <option value="employee">Empleado</option>
                        </select>
                    </div>
                    <div class="col">
                        <select name="target_id" class="form-select" required>
                            <option value="">Selecciona usuario/empleado</option>
                            <?php while ($u = $users->fetch_assoc()): ?>
                                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
                            <?php endwhile; ?>
                            <?php while ($e = $employees->fetch_assoc()): ?>
                                <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Iniciar</button>
                    </div>
                </div>
            </form>
            <div class="mb-3">
                <?php while ($row = $chats->fetch_assoc()): ?>
                    <a href="?chat_id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm mb-1">Chat #<?= $row['id'] ?></a>
                <?php endwhile; ?>
            </div>
            <?php if ($chat_id): ?>
                <div id="messages">
                    <?php foreach ($messages as $msg): ?>
                        <div class="message <?= $msg['sender_type'] ?>">
                            <span class="bubble"><?= htmlspecialchars($msg['message']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <form method="post" class="mt-3 d-flex">
                    <input type="text" name="message" class="form-control me-2" placeholder="Escribe tu mensaje..." autocomplete="off" required>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                </form>
            <?php else: ?>
                <div>Selecciona un chat para comenzar.</div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
