<?php
session_start();
include "db_connect.php";

// Restrict access
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle member deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM members WHERE id = $id");
    header("Location: manage_members.php");
    exit();
}

// Fetch members
$members = $conn->query("SELECT * FROM members ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Members</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            margin: 0;
            background: #f4f6f8;
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
        }

        h1 {
            color: #660000;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            border-radius: 5px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #660000;
            color: white;
        }

        tr:hover {
            background: #f1f1f1;
        }

        .delete-btn {
            color: red;
            text-decoration: none;
            font-weight: bold;
        }

        .delete-btn:hover {
            text-decoration: underline;
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
        <h1>Manage Members</h1>

        <table>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>DOB</th>
                <th>Gender</th>
                <th>Status</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
            <?php $sn = 1; while ($row = $members->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $sn++; ?></td>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['dob']); ?></td>
                    <td><?php echo htmlspecialchars($row['gender']); ?></td>
                    <td><?php echo htmlspecialchars($row['marital_status']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td>
                        <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>
</html>
