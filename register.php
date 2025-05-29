<?php
include "includes/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $error = "Email already registered. Please use a different email or log in.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);
        $stmt->execute();
        $success = "Registered successfully! <a href='login.php'>Login</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Homework Exchange</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1D1616;
            margin: 0;
            padding: 0;
            color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .login-container {
            background-color: rgba(29, 22, 22, 0.9);
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
            border: 1px solid #8E1616;
            width: 400px;
            max-width: 90%;
        }
        
        h1 {
            color: #D84040;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2em;
            border-bottom: 2px solid #8E1616;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #ccc;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #8E1616;
            background-color: #1D1616;
            color: white;
            font-size: 16px;
            transition: border 0.3s ease;
        }
        
        input:focus {
            outline: none;
            border-color: #D84040;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background-color: #8E1616;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #D84040;
            font-size: 16px;
            margin-top: 10px;
        }
        
        button:hover {
            background-color: #D84040;
        }
        
        .error {
            color: #D84040;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: rgba(216, 64, 64, 0.2);
            border-radius: 5px;
            border-left: 4px solid #D84040;
        }
        
        .success {
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: rgba(76, 175, 80, 0.2);
            border-radius: 5px;
            border-left: 4px solid #4CAF50;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #ccc;
        }
        
        .register-link a {
            color: #D84040;
            text-decoration: none;
            font-weight: bold;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Register</h1>

        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Register</button>
        </form>

        <div class="register-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>