<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "db.php";

    if (isset($_GET['channel_id'])) {
        $_SESSION['channel_id'] = $_GET['channel_id'];
}


if (!isset($_SESSION['channel_id'])) {
        $_SESSION['channel_id'] = 1;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Discord Clone</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="chat-box">
        <div id="messages"></div>
        <input type="text" id="message" placeholder="Type a message">
        <button onclick="sendMessage()">Send</button>
    </div>
    <div class="user-info">
        <p>Logged in as: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <a href="logout.php">Logout</a>
    <script>
        function fetchMessages() {
            fetch('fetch_messages.php?channel_id=<?php echo $_SESSION['channel_id']; ?>')
                .then(res => res.text())
                .then(data => {
                    document.getElementById('messages').innerHTML = data;
                });
        }
        <form action="manage_channels.php" method="POST">
    <input name="new_channel" placeholder="New channel" required>
    <button>Create</button>
</form>

<ul>
<?php
foreach ($channels as $ch) {
    $active = ($_SESSION['channel_id'] == $ch['id']) ? "style='font-weight:bold'" : "";
    echo "<li>
        <a href='?channel_id={$ch['id']}' $active># {$ch['name']}</a>";

    if ($ch['created_by'] == $_SESSION['user_id']) {
        echo " <a href='manage_channels.php?delete={$ch['id']}' style='color:red;'>[delete]</a>";
    }

    echo "</li>";
}
?>
</ul>   
</div>
    function sendMessage() {
        const msg = document.getElementById('message').value;
        fetch('send_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'message=' + encodeURIComponent(msg) + '&channel_id=<?php echo $_SESSION['channel_id']; ?>'
        }).then(() => {
            document.getElementById('message').value = '';
            fetchMessages();
        });
    }
        setInterval(fetchMessages, 1000); // polling every second
        fetchMessages();
    </script>
</body>
</html>
