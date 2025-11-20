<?php
session_start();
include "db_connect.php";

// Only allow logged-in admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$whereClause = "";
if ($search) {
    $safeSearch = $conn->real_escape_string($search);
    $whereClause = "WHERE members.full_name LIKE '%$safeSearch%' OR prayer_requests.title LIKE '%$safeSearch%'";
}

$countQuery = $conn->query("SELECT COUNT(*) AS total FROM prayer_requests INNER JOIN members ON prayer_requests.member_id = members.id $whereClause");
$total = $countQuery->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

$result = $conn->query("SELECT prayer_requests.*, members.full_name FROM prayer_requests INNER JOIN members ON prayer_requests.member_id = members.id $whereClause ORDER BY submitted_at DESC LIMIT $limit OFFSET $offset");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Prayer Requests</title>
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
        }

        .content {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
            max-width: 1100px;
            margin: auto;
        }

        h2 {
            color: #660000;
            text-align: center;
        }

        .search-form {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 8px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 8px 15px;
            background-color: #660000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ccc;
            vertical-align: top;
        }

        th {
            background-color: #003366;
            color: white;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            background-color: #003366;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .pagination a.active {
            background-color: #27682a;
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
    <div class="content">
        <h2>Prayer Requests</h2>

        <div class="search-form">
            <form method="GET">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by member name or title">
                <button type="submit">Search</button>
            </form>
        </div>

        <?php if ($result && $result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>#</th>
                    <th>Member Name</th>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                </tr>
                <?php $sn = $offset + 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                        <td><?php echo date("F j, Y g:i A", strtotime($row['submitted_at'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>" class="<?php echo ($i == $page) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        <?php else: ?>
            <p style="text-align:center;">No prayer requests found.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
