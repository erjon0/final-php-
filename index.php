<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
        
        .nav-links a.active {
            background-color: #D84040;
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

        .homework-list {
            margin-top: 40px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .homework-list h2 {
            color: #D84040;
            border-bottom: 2px solid #8E1616;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .homework-item {
            background-color: rgba(29, 22, 22, 0.7);
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #8E1616;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
        }
        .homework-item h3 {
            margin: 0 0 10px;
            color: #fff;
        }
        .homework-item p {
            margin: 0 0 8px;
            color: #ccc;
        }
        .upload-date {
            font-size: 0.9em;
            color: #b0b0b0;
        }

        #modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.8);
            justify-content: center;
            align-items: center;
        }
        #modal-content {
            background-color: #1D1616;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #8E1616;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
        }
        #modal-close {
            color: #D84040;
            float: right;
            font-size: 1.5em;
            font-weight: bold;
            cursor: pointer;
        }
        #modal-close:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container" style="margin-top: 50px;">
        <h1>Welcome to Homework Exchange</h1>
        
        <div class="nav-links">
            <a href='index.php' class="active">Home</a>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href='login.php'>Login</a>
                <a href='register.php'>Register</a>
            <?php else: ?>
                <a href='upload.php'>Upload Homework</a>
                <a href='browse.php'>Browse</a>
                <a href='dashboard.php'>Dashboard</a>
                <a href='logout.php'>Logout</a>
            <?php endif; ?>
        </div>
        
        <div class="welcome-message">
            <?php echo isset($_SESSION['user_id']) ? "Hello! Ready to exchange some homework?" : "Please login or register to get started"; ?>
        </div>

        <div class="homework-list">
            <h2>Featured Homework Uploads</h2>

            <?php
            $exampleHomeworks = [
                [
                    'title' => 'Math Algebra Practice',
                    'description' => 'Algebra worksheet covering linear equations and inequalities.',
                    'uploaded_at' => '2025-05-01 14:30:00'
                ],
                [
                    'title' => 'History World War II Summary',
                    'description' => 'A summary document covering major events of WWII.',
                    'uploaded_at' => '2025-05-03 09:15:00'
                ],
                [
                    'title' => 'Chemistry Periodic Table Notes',
                    'description' => 'Notes and charts for the periodic table of elements.',
                    'uploaded_at' => '2025-05-05 17:45:00'
                ],
            ];

            foreach ($exampleHomeworks as $i => $hw):
            ?>
                <div class="homework-item">
                    <h3><?php echo htmlspecialchars($hw['title']); ?></h3>
                    <p><?php echo htmlspecialchars($hw['description']); ?></p>
                    <p class="upload-date">Uploaded on: <?php echo date('F j, Y, g:i a', strtotime($hw['uploaded_at'])); ?></p>
                    <div class="nav-links" style="margin-top:10px;">
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href='login.php'>Login to View</a>
                            <a href='register.php'>Register</a>
                        <?php else: ?>
                            <a href="#" class="view-btn" 
                               data-title="<?php echo htmlspecialchars($hw['title']); ?>"
                               data-description="<?php echo htmlspecialchars($hw['description']); ?>"
                               data-date="<?php echo date('F j, Y, g:i a', strtotime($hw['uploaded_at'])); ?>"
                            >View Details</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.6); align-items:center; justify-content:center;">
            <div id="modal-content" style="background:#1D1616; color:#fff; padding:30px; border-radius:8px; max-width:400px; width:90%; box-shadow:0 0 20px #000; position:relative;">
                <span id="modal-close" style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:1.5em;">&times;</span>
                <h2 id="modal-title"></h2>
                <p id="modal-description"></p>
                <p id="modal-date" style="color:#b0b0b0; font-size:0.95em;"></p>
            </div>
        </div>
    </div>

    <script>
    document.querySelectorAll('.view-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('modal-title').textContent = btn.getAttribute('data-title');
            document.getElementById('modal-description').textContent = btn.getAttribute('data-description');
            document.getElementById('modal-date').textContent = "Uploaded on: " + btn.getAttribute('data-date');
            document.getElementById('modal').style.display = 'flex';
        });
    });
    document.getElementById('modal-close').onclick = function() {
        document.getElementById('modal').style.display = 'none';
    };
    window.onclick = function(event) {
        if (event.target == document.getElementById('modal')) {
            document.getElementById('modal').style.display = 'none';
        }
    };
    </script>
</body>
</html>
