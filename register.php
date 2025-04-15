<?php
session_start();
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // Simple validation
    if ($password !== $confirm) {
        echo "Passwords do not match!";
    } else {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            echo "Username already taken!";
        } else {
            // Hash password and store
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hash]);

            echo "Registration successful! <a href='login.php'>Login here</a>";
            exit();
        }
    }
}
?>

<form method="POST">
    <h2>Register</h2>
    <input name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="password" name="confirm" placeholder="Confirm Password" required><br>
    <button>Register</button>
</form>

<p>Already have an account? <a href="login.php">Login</a></p>
