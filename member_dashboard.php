<?php
session_start();

// Restrict access if not logged in
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit();
}

$member_name = $_SESSION['member_name'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Member Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
        }

        .container {
            max-width: 800px;
            margin: 60px auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            color: #003366;
        }

        .links {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
        }

        .links a {
            display: block;
            padding: 15px;
            background-color: #2e7d32;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .links a:hover {
            background-color: #27682a;
        }

        .logout {
            margin-top: 40px;
        }

        .logout a {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

    </style>
</head>
<body>

    <div class="header">
        Church of Pentecost, Asokwa
    </div>

    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($member_name); ?>!</h2>
        <p>Select an option below to continue:</p>

        <div class="links">
            <a href="events.php">📅 Upcoming Events</a>
            <a href="sermons.php">📖 Download Sermons</a>
            <a href="prayer_request.php">🙏 Submit Prayer Request</a>
            <a href="profile.php">👤 My Profile</a>
        </div>

        <div class="logout">
            <p><a href="logout.php">Logout</a></p>
        </div>
    </div>

</body>
</html>
