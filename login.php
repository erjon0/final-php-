<?php
session_start();
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
    } else {
        echo "Login failed!";
    }
}
?>

<form method="POST">
    <input name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
    <button>Login</button>
</form>
