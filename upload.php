<?php
include "includes/db.php";
session_start();
if (!isset($_SESSION['user_id'])) die("Login first.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $desc = $_POST['description'];
    $file = $_FILES['file'];

    $filename = basename($file["name"]);
    $target = "uploads/" . $filename;
    move_uploaded_file($file["tmp_name"], $target);

    $stmt = $conn->prepare("INSERT INTO homework (title, description, filename, uploaded_by) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $title, $desc, $filename, $_SESSION['user_id']);
    $stmt->execute();
    echo "Uploaded!";
}
?>
<form method="POST" enctype="multipart/form-data">
    Title: <input name="title"><br>
    Description: <textarea name="description"></textarea><br>
    File: <input type="file" name="file"><br>
    <input type="submit" value="Upload">
</form>