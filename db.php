<?php
$host = "localhost";
$dbname = "discord_clone";
$user = "root";
$pass = ""; // your db password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}
?>
