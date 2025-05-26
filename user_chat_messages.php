<?php
session_start();
require_once "db.php";
if (!isset($_SESSION['user_id']) || !isset($_GET['chat_id'])) exit;
$user_id = $_SESSION['user_id'];
$chat_id = intval($_GET['chat_id']);
$stmt = $conn->prepare("SELECT * FROM chat_sessions WHERE id=?");
$stmt->bind_param("i", $chat_id);
$stmt->execute();
$session = $stmt->get_result()->fetch_assoc();
$employee_id = $session['employee_id'];
$result = $conn->query("SELECT * FROM chat_messages WHERE (sender_type='user' AND sender_id=$user_id AND receiver_id=$employee_id) OR (sender_type='employee' AND sender_id=$employee_id AND receiver_id=$user_id) ORDER BY created_at ASC");
while ($row = $result->fetch_assoc()) {
    $type = $row['sender_type'] === 'user' ? 'user' : 'employee';
    echo '<div class="message '.$type.'"><span class="bubble">'.htmlspecialchars($row['message']).'</span></div>';
}
?>
