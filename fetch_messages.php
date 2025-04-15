<?php

session_start();

$channel_id = $_GET['channel_id'] ?? 1;

include "db.php";

$stmt = $pdo->prepare("SELECT messages.*, users.username FROM messages JOIN users ON messages.user_id = users.id WHERE channel_id = ?ORDER BY timestamp DESC LIMIT 20");
$stmt->execute([$channel_id]);

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($messages as $message) {
    echo "<p><strong>" . htmlspecialchars($message['username']) . ":</strong> " . htmlspecialchars($message['message']) . "</p>";
}
?>