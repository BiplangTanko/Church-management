<?php
session_start();
include "db_connect.php";

// Restrict to logged-in members
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch events
$events = $conn->query("SELECT * FROM events ORDER BY event_date ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upcoming Events</title>
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
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #003366;
        }

        .event {
            border-left: 5px solid #2e7d32;
            padding: 15px;
            margin-bottom: 20px;
            background: #f9f9f9;
            border-radius: 6px;
        }

        .event h3 {
            margin: 0;
            color: #2e7d32;
        }

        .event .meta {
            font-size: 14px;
            color: #555;
            margin-bottom: 10px;
        }

        .event p {
            margin: 0;
        }

        .back {
            text-align: center;
            margin-top: 30px;
        }

        .back a {
            text-decoration: none;
            color: #003366;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        Church of Pentecost, Asokwa
    </div>

    <div class="container">
        <h2>Upcoming Events</h2>

        <?php if ($events->num_rows > 0): ?>
            <?php while ($event = $events->fetch_assoc()): ?>
                <div class="event">
                    <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                    <div class="meta">
                        <?php echo date("F j, Y", strtotime($event['event_date'])); ?> at 
                        <?php echo date("g:i A", strtotime($event['event_time'])); ?>
                    </div>
                    <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="text-align:center;">No events available.</p>
        <?php endif; ?>

        <div class="back">
            <a href="member_dashboard.php">&larr; Back to Dashboard</a>
        </div>
    </div>

</body>
</html>
