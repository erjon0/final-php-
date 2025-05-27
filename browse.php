<?php
session_start();
include "includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get all homeworks except user's own
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT h.*, u.username FROM homeworks h JOIN users u ON h.user_id = u.id WHERE h.user_id != ? ORDER BY h.uploaded_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$homeworks = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Homework - Homework Exchange</title>
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
            font-size: 2em;
            border-bottom: 2px solid #8E1616;
            padding-bottom: 10px;
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
        
        .homework-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .homework-card {
            background-color: rgba(142, 22, 22, 0.1);
            border: 1px solid #8E1616;
            border-radius: 5px;
            padding: 15px;
            transition: all 0.3s ease;
        }
        
        .homework-card:hover {
            border-color: #D84040;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .homework-card h3 {
            color: #f5f5f5;
            margin-top: 0;
            border-bottom: 1px solid #8E1616;
            padding-bottom: 10px;
        }
        
        .homework-card p {
            color: #ccc;
            margin: 5px 0;
        }
        
        .homework-card .author {
            color: #D84040;
            font-style: italic;
        }
        
        .homework-card .subject {
            display: inline-block;
            background-color: rgba(216, 64, 64, 0.2);
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 0.8em;
        }
        
        .homework-card .date {
            font-size: 0.9em;
            color: #999;
        }
        
        .download-btn {
            display: inline-block;
            background-color: #8E1616;
            color: white;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
            transition: all 0.3s ease;
            border: 1px solid #D84040;
        }
        
        .download-btn:hover {
            background-color: #D84040;
        }
        
        .no-homeworks {
            text-align: center;
            color: #ccc;
            padding: 20px;
            background-color: rgba(142, 22, 22, 0.2);
            border-radius: 5px;
            grid-column: 1 / -1;
        }
        
        .search-form {
            margin-bottom: 30px;
            display: flex;
            gap: 10px;
        }
        
        .search-form input {
            flex: 1;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #8E1616;
            background-color: #1D1616;
            color: white;
        }
        
        .search-form button {
            padding: 10px 20px;
            background-color: #8E1616;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #D84040;
        }
        
        .search-form button:hover {
            background-color: #D84040;
        }
    </style>
</head>
<body>
    <div class="container" style="margin-top: 50px;">
        <h1>Browse Homework</h1>
        
        <div class="nav-links">
            <a href='upload.php'>Upload Homework</a>
            <a href='dashboard.php'>Dashboard</a>
            <a href='logout.php'>Logout</a>
        </div>
        
        <form method="GET" class="search-form">
            <input type="text" name="search" placeholder="Search homework..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
        
        <div class="homework-list">
            <?php if (count($homeworks) > 0): ?>
                <?php foreach ($homeworks as $hw): ?>
                    <div class="homework-card">
                        <h3><?php echo htmlspecialchars($hw['title']); ?></h3>
                        <p class="subject"><?php echo htmlspecialchars($hw['subject']); ?></p>
                        <p class="author">By <?php echo htmlspecialchars($hw['username']); ?></p>
                        <p class="date">Uploaded on <?php echo date('M j, Y', strtotime($hw['uploaded_at'])); ?></p>
                        <p><?php echo htmlspecialchars($hw['description']); ?></p>
                        <a href="download.php?id=<?php echo $hw['id']; ?>" class="download-btn">Download</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-homeworks">No homeworks available at the moment.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>