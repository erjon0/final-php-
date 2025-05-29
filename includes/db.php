<?php
$servername = "localhost";
$username = "homework_user";
$password = "secure_password_123";
$dbname = "homework_exchange";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>