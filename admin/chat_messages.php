<?php
session_start();
require_once "../db.php";
if (!isset($_SESSION['admin_logged_in']) || !isset($_GET['chat_id'])) exit;
$admin_id = $_SESSION['admin_id'];
$chat_id = intval($_GET['chat_id']);
$stmt = $conn->prepare("SELECT * FROM chat_sessions WHERE id=?");
$stmt->bind_param("i", $chat_id);
$stmt->execute();
$session = $stmt->get_result()->fetch_assoc();

if ($session['user_id']) {
    $target_id = $session['user_id'];
    $result = $conn->query("SELECT * FROM chat_messages WHERE (sender_type='admin' AND sender_id=$admin_id AND receiver_id=$target_id) OR (sender_type='user' AND sender_id=$target_id AND receiver_id=$admin_id) ORDER BY created_at ASC");
} elseif ($session['employee_id']) {
    $target_id = $session['employee_id'];
    $result = $conn->query("SELECT * FROM chat_messages WHERE (sender_type='admin' AND sender_id=$admin_id AND receiver_id=$target_id) OR (sender_type='employee' AND sender_id=$target_id AND receiver_id=$admin_id) ORDER BY created_at ASC");
} else {
    $result = $conn->query("SELECT * FROM chat_messages WHERE (sender_type='admin' AND sender_id=$admin_id) ORDER BY created_at ASC");
}

while ($row = $result->fetch_assoc()) {
    $type = $row['sender_type'];
    echo '<div class="message '.$type.'"><span class="bubble">'.htmlspecialchars($row['message']).'</span></div>';
}
?> 