<?php
include "includes/db.php";
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT filename FROM homework WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($filename);
if ($stmt->fetch()) {
    $filepath = "uploads/" . $filename;
    if (file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
        readfile($filepath);
        exit;
    }
}
echo "File not found.";
?>