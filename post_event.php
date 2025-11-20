<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $location = trim($_POST['location']);

    if ($title && $event_date && $event_time && $location) {
        $stmt = $conn->prepare("INSERT INTO events (title, description, event_date, event_time, location) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $description, $event_date, $event_time, $location);

        if ($stmt->execute()) {
            $success = "Event posted successfully!";
        } else {
            $error = "Error: " . $conn->error;
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Post Event</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background-color: #f4f6f8;
            display: flex;
        }

        .sidebar {
            width: 220px;
            background-color: #660000;
            color: white;
            height: 100vh;
            position: fixed;
            padding: 20px;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 20px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            margin: 15px 0;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .sidebar a:hover {
            background-color: #880000;
        }

        .main {
            margin-left: 220px;
            padding: 40px;
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 600px;
        }

        h1 {
            color: #660000;
            margin-bottom: 20px;
            text-align: center;
        }

        input, textarea {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            font-size: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background: #660000;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
        }

        .success {
            color: green;
            margin-bottom: 15px;
            text-align: center;
        }

        .error {
            color: red;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="manage_members.php">Manage Members</a>
        <a href="post_event.php">Post Events</a>
        <a href="upload_sermon.php">Upload Sermons</a>
        <a href="view_prayers.php">Prayer Requests</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <div class="main">
        <div class="form-container">
            <h1>Post a New Event</h1>

            <?php if ($success): ?>
                <div class="success"><?php echo $success; ?></div>
            <?php elseif ($error): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <input type="text" name="title" placeholder="Event Title" required>
                <textarea name="description" rows="4" placeholder="Event Description (optional)"></textarea>
                <input type="date" name="event_date" required>
                <input type="time" name="event_time" required>
                <input type="text" name="location" placeholder="Event Location" required>
                <button type="submit">Post Event</button>
            </form>
        </div>
    </div>

</body>
</html>
