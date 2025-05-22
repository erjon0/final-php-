<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homework Exchange</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1D1616;
            margin: 0;
            padding: 0;
            color: #f5f5f5;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: rgba(29, 22, 22, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
            border: 1px solid #8E1616;
        }
        
        h1 {
            color: #D84040;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            border-bottom: 2px solid #8E1616;
            padding-bottom: 15px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }
        
        .nav-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .nav-links a {
            text-decoration: none;
            color: white;
            background-color: #8E1616;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
            border: 1px solid #D84040;
        }
        
        .nav-links a:hover {
            background-color: #D84040;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
        }
        
        .welcome-message {
            text-align: center;
            font-size: 1.2em;
            margin-top: 30px;
            color: #ccc;
            padding: 15px;
            background-color: rgba(142, 22, 22, 0.2);
            border-radius: 5px;
            border-left: 4px solid #D84040;
        }
    </style>
</head>
<body>
    <div class="container" style="margin-top: 50px;">
        <h1>Welcome to Homework Exchange</h1>
        
        <div class="nav-links">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href='login.php'>Login</a>
                <a href='register.php'>Register</a>
            <?php else: ?>
                <a href='upload.php'>Upload Homework</a>
                <a href='dashboard.php'>Dashboard</a>
                <a href='logout.php'>Logout</a>
            <?php endif; ?>
        </div>
        
        <div class="welcome-message">
            <?php echo isset($_SESSION['user_id']) ? "Hello! Ready to exchange some homework?" : "Please login or register to get started"; ?>
        </div>
    </div>
</body>
</html>