<?php
session_start();
require_once "db.php";
if (!isset($_SESSION['user_id']) || !isset($_POST['chat_id']) || !isset($_POST['message'])) exit;
$user_id = $_SESSION['user_id'];
$chat_id = intval($_POST['chat_id']);
$msg = trim($_POST['message']);
if ($msg === "") exit;
$stmt = $conn->prepare("SELECT employee_id FROM chat_sessions WHERE id=?");
$stmt->bind_param("i", $chat_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$employee_id = $res['employee_id'];
$stmt = $conn->prepare("INSERT INTO chat_messages (sender_type, reciever_type, sender_id, receiver_id, message, created_at) VALUES ('user', 'employee', ?, ?, ?, NOW())");
$stmt->bind_param("iis", $user_id, $employee_id, $msg);
$stmt->execute();
?>
