<?php
session_start();
require_once "../db.php";
if (!isset($_SESSION['employee_logged_in']) || !isset($_POST['chat_id']) || !isset($_POST['message'])) exit;
$employee_id = $_SESSION['employee_id'];
$chat_id = intval($_POST['chat_id']);
$msg = trim($_POST['message']);
if ($msg === "") exit;

$stmt = $conn->prepare("SELECT * FROM chat_sessions WHERE id=?");
$stmt->bind_param("i", $chat_id);
$stmt->execute();
$session = $stmt->get_result()->fetch_assoc();

if ($session['user_id']) {
    $receiver_id = $session['user_id'];
    $stmt = $conn->prepare("INSERT INTO chat_messages (sender_type, reciever_type, sender_id, receiver_id, message, created_at) VALUES ('employee', 'user', ?, ?, ?, NOW())");
} elseif ($session['admin_id']) {
    $receiver_id = $session['admin_id'];
    $stmt = $conn->prepare("INSERT INTO chat_messages (sender_type, reciever_type, sender_id, receiver_id, message, created_at) VALUES ('employee', 'admin', ?, ?, ?, NOW())");
} else {
    $receiver_id = $session['employee_id'];
    $stmt = $conn->prepare("INSERT INTO chat_messages (sender_type, reciever_type, sender_id, receiver_id, message, created_at) VALUES ('employee', 'employee', ?, ?, ?, NOW())");
}

$stmt->bind_param("iis", $employee_id, $receiver_id, $msg);
$stmt->execute();
?> 