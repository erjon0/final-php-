<?php
session_start();
include "db.php";

// Create channel
if (isset($_POST['new_channel'])) {
    $name = trim($_POST['new_channel']);
    $stmt = $pdo->prepare("INSERT INTO channels (name, created_by) VALUES (?, ?)");
    $stmt->execute([$name, $_SESSION['user_id']]);
    header("Location: index.php");
    exit();
}

// Delete channel
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $channel_id = $_GET['delete'];

    // Only allow delete if user created it
    $stmt = $pdo->prepare("SELECT * FROM channels WHERE id = ? AND created_by = ?");
    $stmt->execute([$channel_id, $_SESSION['user_id']]);

    if ($stmt->rowCount()) {
        $pdo->prepare("DELETE FROM messages WHERE channel_id = ?")->execute([$channel_id]);
        $pdo->prepare("DELETE FROM channels WHERE id = ?")->execute([$channel_id]);
    }

    header("Location: index.php");
    exit();
}
?>
