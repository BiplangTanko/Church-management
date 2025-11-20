<?php
session_start();
include "db_connect.php";

// Restrict access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            min-height: 100vh;
            background-color: #f4f6f8;
        }

        /* Sidebar */
        .sidebar {
            width: 220px;
            background-color: #660000;
            color: white;
            position: fixed;
            height: 100%;
            padding: 20px;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 20px;
            text-align: center;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            margin: 15px 0;
            font-weight: bold;
            transition: background 0.2s;
            padding: 10px;
            border-radius: 5px;
        }

        .sidebar a:hover {
            background-color: #880000;
        }

        /* Main content */
        .main {
            margin-left: 220px;
            padding: 40px;
            flex: 1;
        }

        .main h1 {
            color: #660000;
            margin-bottom: 25px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: scale(1.03);
        }

        .card a {
            display: block;
            color: #660000;
            font-weight: bold;
            text-decoration: none;
            font-size: 16px;
            margin-top: 10px;
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
        <h1>Welcome to Admin Dashboard</h1>

        <div class="grid">
            <div class="card">
                <h3>Manage Members</h3>
                <a href="manage_members.php">View Members</a>
            </div>
            <div class="card">
                <h3>Post Events</h3>
                <a href="post_event.php">Create Event</a>
            </div>
            <div class="card">
                <h3>Upload Sermons</h3>
                <a href="upload_sermon.php">Upload File</a>
            </div>
            <div class="card">
                <h3>Prayer Requests</h3>
                <a href="view_prayers.php">View Prayers</a>
            </div>
        </div>
    </div>

</body>
</html>
