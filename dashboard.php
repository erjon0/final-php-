<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// You would typically fetch user data and homework from database here
$username = $_SESSION['username'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - Homework Exchange</title>
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
        
        .nav-links a.active {
            background-color: #D84040;
        }
        
        .welcome-message {
            text-align: center;
            font-size: 1.3em;
            margin-bottom: 30px;
            color: #fff;
            padding: 20px;
            background-color: rgba(142, 22, 22, 0.3);
            border-radius: 5px;
            border-left: 4px solid #D84040;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .dashboard-card {
            background-color: rgba(29, 22, 22, 0.7);
            padding: 25px;
            border-radius: 8px;
            border: 1px solid #8E1616;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }

        .dashboard-card h3 {
            color: #D84040;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 1.4em;
            border-bottom: 1px solid #8E1616;
            padding-bottom: 10px;
        }

        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            color: #fff;
            text-align: center;
            margin: 15px 0;
        }

        .recent-activity {
            margin-top: 30px;
        }

        .activity-item {
            background-color: rgba(29, 22, 22, 0.5);
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            border-left: 3px solid #8E1616;
        }

        .activity-item:hover {
            border-left-color: #D84040;
            background-color: rgba(29, 22, 22, 0.8);
        }

        .quick-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }

        .quick-action-btn {
            background-color: #8E1616;
            color: white;
            padding: 15px 25px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s ease;
            border: 1px solid #D84040;
            text-align: center;
            min-width: 150px;
        }

        .quick-action-btn:hover {
            background-color: #D84040;
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="container" style="margin-top: 50px;">
        <h1>Dashboard</h1>
        
        <div class="nav-links">
            <a href='index.php'>Home</a>
            <a href='upload.php'>Upload Homework</a>
            <a href='browse.php'>Browse</a>
            <a href='dashboard.php' class="active">Dashboard</a>
            <a href='logout.php'>Logout</a>
        </div>
        
        <div class="welcome-message">
            Welcome back, <?php echo htmlspecialchars($username); ?>! 
            <br><small>Here's your homework exchange activity</small>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>📚 Your Uploads</h3>
                <div class="stat-number">5</div>
                <p style="text-align: center; color: #ccc;">Homework assignments uploaded</p>
            </div>

            <div class="dashboard-card">
                <h3>📥 Downloads</h3>
                <div class="stat-number">12</div>
                <p style="text-align: center; color: #ccc;">Files you've downloaded</p>
            </div>

            <div class="dashboard-card">
                <h3>⭐ Rating</h3>
                <div class="stat-number">4.8</div>
                <p style="text-align: center; color: #ccc;">Average rating from peers</p>
            </div>

            <div class="dashboard-card">
                <h3>🏆 Points</h3>
                <div class="stat-number">245</div>
                <p style="text-align: center; color: #ccc;">Exchange points earned</p>
            </div>
        </div>

        <div class="quick-actions">
            <a href="upload.php" class="quick-action-btn">📤 Upload New</a>
            <a href="browse.php" class="quick-action-btn">🔍 Browse All</a>
            <a href="profile.php" class="quick-action-btn">👤 Edit Profile</a>
        </div>

        <div class="recent-activity">
            <div class="dashboard-card">
                <h3>📋 Recent Activity</h3>
                
                <div class="activity-item">
                    <strong>Math Calculus Notes</strong> was downloaded by 3 users
                    <br><small style="color: #b0b0b0;">2 hours ago</small>
                </div>
                
                <div class="activity-item">
                    <strong>Physics Lab Report</strong> received a 5-star rating
                    <br><small style="color: #b0b0b0;">1 day ago</small>
                </div>
                
                <div class="activity-item">
                    <strong>History Essay Draft</strong> was uploaded successfully
                    <br><small style="color: #b0b0b0;">2 days ago</small>
                </div>
                
                <div class="activity-item">
                    <strong>Chemistry Formulas</strong> was downloaded by you
                    <br><small style="color: #b0b0b0;">3 days ago</small>
                </div>
            </div>
        </div>

        <div class="dashboard-card" style="margin-top: 20px;">
            <h3>💡 Quick Tips</h3>
            <ul style="color: #ccc; line-height: 1.6;">
                <li>Upload high-quality homework to earn more points</li>
                <li>Rate downloaded content to help other students</li>
                <li>Check back regularly for new uploads in your subjects</li>
                <li>Use descriptive titles and tags for better discoverability</li>
            </ul>
        </div>
    </div>
</body>
</html>
