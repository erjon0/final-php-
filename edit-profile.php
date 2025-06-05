<?php
session_start();
include "includes/db.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access denied. <a href='login.php'>Login</a>");
}

$userId = $_SESSION['user_id'];
$message = '';
$error = '';

// Fetch current user data
$stmt = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate inputs
    if (empty($username) || empty($email)) {
        $error = "Username and email are required fields.";
    } else {
        // Check if email already exists for another user
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Email already in use by another account.";
        } else {
            // If password change is requested
            if (!empty($current_password)) {
                // Verify current password
                $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $stmt->bind_result($hashed_password);
                $stmt->fetch();
                $stmt->close();
                
                if (!password_verify($current_password, $hashed_password)) {
                    $error = "Current password is incorrect.";
                } elseif (empty($new_password) || empty($confirm_password)) {
                    $error = "New password and confirmation are required.";
                } elseif ($new_password !== $confirm_password) {
                    $error = "New passwords do not match.";
                } elseif (strlen($new_password) < 6) {
                    $error = "New password must be at least 6 characters long.";
                } else {
                    // Update user with new password
                    $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, password = ? WHERE id = ?");
                    $stmt->bind_param("sssi", $username, $email, $hashed_new_password, $userId);
                    if ($stmt->execute()) {
                        $message = "Profile updated successfully with new password.";
                        // Refresh user data
                        $user['username'] = $username;
                        $user['email'] = $email;
                    } else {
                        $error = "Error updating profile.";
                    }
                }
            } else {
                // Update user without changing password
                $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
                $stmt->bind_param("ssi", $username, $email, $userId);
                if ($stmt->execute()) {
                    $message = "Profile updated successfully.";
                    // Refresh user data
                    $user['username'] = $username;
                    $user['email'] = $email;
                } else {
                    $error = "Error updating profile.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Homework Exchange</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1D1616;
            margin: 0;
            padding: 0;
            color: #f5f5f5;
        }
        
        .container {
            max-width: 800px;
            margin: 50px auto;
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
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #D84040;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #8E1616;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.1);
            color: #f5f5f5;
            font-size: 16px;
            box-sizing: border-box;
        }
        
        input[type="submit"] {
            background-color: #8E1616;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            transition: all 0.3s ease;
            display: block;
            margin: 30px auto 0;
            border: 1px solid #D84040;
        }
        
        input[type="submit"]:hover {
            background-color: #D84040;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
        }
        
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }
        
        .success {
            background-color: rgba(40, 167, 69, 0.2);
            border-left: 4px solid #28a745;
            color: #28a745;
        }
        
        .error {
            background-color: rgba(220, 53, 69, 0.2);
            border-left: 4px solid #dc3545;
            color: #dc3545;
        }
        
        .nav-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .nav-links a {
            text-decoration: none;
            color: white;
            background-color: #8E1616;
            padding: 10px 20px;
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
        
        .section-title {
            color: #D84040;
            border-bottom: 1px solid #8E1616;
            padding-bottom: 10px;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        
        .password-note {
            font-size: 0.9em;
            color: #ccc;
            margin-top: 5px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Edit Profile</h1>
        
        <?php if (!empty($message)): ?>
            <div class="message success"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <form method="POST" action="edit-profile.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            
            <h3 class="section-title">Change Password (Optional)</h3>
            <div class="password-note">Leave blank if you don't want to change your password</div>
            
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password">
            </div>
            
            <div class="form-group">
                <label for="new_password">New Password (minimum 6 characters)</label>
                <input type="password" id="new_password" name="new_password">
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>
            
            <input type="submit" value="Update Profile">
        </form>
        
        <div class="nav-links">
            <a href="dashboard.php">Back to Dashboard</a>
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
