<?php

session_start();

$channel_id = $_POST['channel_id'] ?? 1;

include "db.php";

if (isset($_POST['message']) && isset($_SESSION['user_id'])) {
    $message = $_POST['message'];
    $stmt = $pdo->prepare("INSERT INTO messages (user_id, message, channel_id) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $message, $channel_id]);
}
?>