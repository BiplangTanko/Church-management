<?php
session_start();
include "db_connect.php";

// Only allow logged-in admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$success = $error = "";

// Handle sermon upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $preacher = trim($_POST['preacher']);
    $sermon_date = $_POST['sermon_date'];
    $file = $_FILES['sermon_file'];

    if ($title && $preacher && $sermon_date && $file['name']) {
        $allowed = ['pdf', 'mp3', 'mp4'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            if (!is_dir('sermons')) {
                mkdir('sermons', 0777, true);
            }

            $newFileName = time() . '_' . basename($file['name']);
            $uploadPath = "sermons/" . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $stmt = $conn->prepare("INSERT INTO sermons (title, description, preacher, sermon_date, file_path) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $title, $description, $preacher, $sermon_date, $uploadPath);

                if ($stmt->execute()) {
                    $success = "Sermon uploaded successfully!";
                } else {
                    $error = "Database error: " . $conn->error;
                }
            } else {
                $error = "Failed to upload file.";
            }
        } else {
            $error = "Invalid file type. Only PDF, MP3, or MP4 allowed.";
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $query = $conn->query("SELECT file_path FROM sermons WHERE id = $id");
    if ($query && $row = $query->fetch_assoc()) {
        @unlink($row['file_path']);
        $conn->query("DELETE FROM sermons WHERE id = $id");
        $success = "Sermon deleted successfully.";
    }
}

// Search + Pagination
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;

$whereClause = "";
if ($search) {
    $safeSearch = $conn->real_escape_string($search);
    $whereClause = "WHERE title LIKE '%$safeSearch%' OR preacher LIKE '%$safeSearch%'";
}

$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM sermons $whereClause");
$total = $totalQuery->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

$sermons = $conn->query("SELECT * FROM sermons $whereClause ORDER BY sermon_date DESC LIMIT $limit OFFSET $offset");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload & Manage Sermons</title>
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

        .form-container, .manage-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
            margin-bottom: 40px;
            max-width: 1100px;
            margin-left: auto;
            margin-right: auto;
        }

        h1, h2 {
            color: #660000;
            text-align: center;
            margin-bottom: 20px;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ccc;
            vertical-align: top;
        }

        th {
            background-color: #003366;
            color: white;
        }

        .actions a {
            margin-right: 10px;
            color: #660000;
            font-weight: bold;
            text-decoration: none;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        video, audio {
            max-width: 200px;
            height: auto;
        }

        .search-form {
            text-align: center;
            margin-top: 10px;
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

        .pagination a:hover {
            background-color: #005599;
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
        <h1>Upload Sermon</h1>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Sermon Title" required>
            <textarea name="description" rows="3" placeholder="Description (optional)"></textarea>
            <input type="text" name="preacher" placeholder="Preacher's Name" required>
            <input type="date" name="sermon_date" required>
            <input type="file" name="sermon_file" accept=".pdf,.mp3,.mp4" required>
            <button type="submit" name="upload">Upload Sermon</button>
        </form>
    </div>

    <div class="manage-section">
        <h2>Manage Sermons</h2>

        <div class="search-form">
            <form method="GET">
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by title or preacher">
                <button type="submit">Search</button>
            </form>
        </div>

        <?php if ($sermons && $sermons->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Preacher</th>
                        <th>Date</th>
                        <th>Media</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $sermons->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['preacher']); ?></td>
                            <td><?php echo htmlspecialchars($row['sermon_date']); ?></td>
                            <td>
                                <?php
                                    $ext = strtolower(pathinfo($row['file_path'], PATHINFO_EXTENSION));
                                    if ($ext === 'mp4') {
                                        echo "<video controls><source src='".htmlspecialchars($row['file_path'])."' type='video/mp4'></video>";
                                    } elseif ($ext === 'mp3') {
                                        echo "<audio controls><source src='".htmlspecialchars($row['file_path'])."' type='audio/mpeg'></audio>";
                                    } else {
                                        echo "<a href='".htmlspecialchars($row['file_path'])."' target='_blank'>PDF</a>";
                                    }
                                ?>
                            </td>
                            <td class="actions">
                                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this sermon?')">Delete</a>
                                <a href="<?php echo htmlspecialchars($row['file_path']); ?>" download>Download</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        <?php else: ?>
            <p style="text-align:center;">No sermons found.</p>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
