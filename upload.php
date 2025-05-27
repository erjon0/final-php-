<?php
session_start();
include "includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $subject = trim($_POST['subject']);
    $user_id = $_SESSION['user_id'];

    // File upload handling
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];
        
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed = ['pdf', 'doc', 'docx', 'txt', 'zip', 'png', 'jpg', 'jpeg'];
        
        if (in_array($file_ext, $allowed)) {
            if ($file_error === 0) {
                if ($file_size <= 5000000) { // 5MB max
                    $file_name_new = uniqid('', true) . '.' . $file_ext;
                    $uploadDir = 'uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true); // create folder if not exists
                    }
                    $file_destination = $uploadDir . $file_name_new;
                    
                    if (move_uploaded_file($file_tmp, $file_destination)) {
                        $stmt = $conn->prepare("INSERT INTO homeworks (user_id, title, description, subject, file_path) VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("issss", $user_id, $title, $description, $subject, $file_destination);
                        
                        if ($stmt->execute()) {
                            $success = "Homework uploaded successfully!";
                        } else {
                            $error = "Database error. Please try again.";
                        }
                    } else {
                        $error = "Error uploading file.";
                    }
                } else {
                    $error = "File size too large (max 5MB).";
                }
            } else {
                $error = "Error uploading file.";
            }
        } else {
            $error = "Invalid file type. Allowed: PDF, DOC, DOCX, TXT, ZIP, PNG, JPG";
        }
    } else {
        $error = "Please select a file to upload.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Homework - Homework Exchange</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1D1616;
            margin: 0;
            padding: 0;
            color: #f5f5f5;
            min-height: 100vh;
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
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #8E1616;
            background-color: #1D1616;
            color: white;
            font-size: 16px;
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        input[type="file"] {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            border: 1px dashed #8E1616;
            background-color: rgba(142, 22, 22, 0.1);
            color: white;
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
        }
        
        .file-info {
            margin-top: 5px;
            font-size: 0.9em;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload Homework</h1>
        
        <div class="nav-links">
            <a href='dashboard.php'>Dashboard</a>
            <a href='logout.php'>Logout</a>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="upload.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Homework Title</label>
                <input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group">
                <label for="subject">Subject</label>
                <select id="subject" name="subject" required>
                    <option value="">Select a subject</option>
                    <option value="Math">Math</option>
                    <option value="Science">Science</option>
                    <option value="English">English</option>
                    <option value="History">History</option>
                    <option value="Computer Science">Computer Science</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            
            <div class="form-group">
                <label for="file">Homework File</label>
                <input type="file" id="file" name="file" required>
                <div class="file-info">Allowed formats: PDF, DOC, DOCX, TXT, ZIP, PNG, JPG (Max 5MB)</div>
            </div>
            
            <button type="submit">Upload Homework</button>
        </form>
    </div>
</body>
</html>