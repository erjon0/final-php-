<?php
session_start();
include "includes/db.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);
    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        header("Location: index.php");
        exit();
    } else {
        echo "Invalid credentials.";
    }
}
?>
<form method="POST">
    Email: <input name="email"><br>
    Password: <input type="password" name="password"><br>
    <input type="submit" value="Login">
</form>