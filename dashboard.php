<?php
include "includes/db.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    die("Access denied. <a href='login.php'>Login</a>");
}
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM homework WHERE uploaded_by = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Your Uploaded Homework</h2>";
while ($row = $result->fetch_assoc()) {
    echo "<div><strong>" . htmlspecialchars($row['title']) . "</strong><br>" .
         htmlspecialchars($row['description']) . "<br>" .
         "<a href='edit.php?id=" . $row['id'] . "'>Edit</a> | " .
         "<a href='delete.php?id=" . $row['id'] . "'>Delete</a></div><br>";
}
?>