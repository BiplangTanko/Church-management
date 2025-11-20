<?php
session_start();
include "db_connect.php";

// Restrict to logged-in members
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit();
}

$member_id = $_SESSION['member_id'];
$success = "";
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $message = trim($_POST['message']);

    if (empty($title) || empty($message)) {
        $error = "Please fill in both title and message.";
    } else {
        $stmt = $conn->prepare("INSERT INTO prayer_requests (member_id, title, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $member_id, $title, $message);

        if ($stmt->execute()) {
            $success = "Prayer request submitted successfully.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Submit Prayer Request</title>
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
            max-width: 600px;
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

        input, textarea, button {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            font-size: 15px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #2e7d32;
            color: white;
            font-weight: bold;
            border: none;
        }

        .message {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
        <h2>Submit Prayer Request</h2>

        <?php if ($success): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="text" name="title" placeholder="Request Title" required>
            <textarea name="message" rows="5" placeholder="Your prayer request..." required></textarea>
            <button type="submit">Submit Request</button>
        </form>

        <div class="back">
            <a href="member_dashboard.php">&larr; Back to Dashboard</a>
        </div>
    </div>

</body>
</html>
