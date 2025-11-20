<?php
session_start();
include "db_connect.php";

if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit();
}

// Pagination setup
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Filter setup
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$whereClause = $search ? "WHERE title LIKE '%$search%' OR preacher LIKE '%$search%'" : '';

// Total sermons count
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM sermons $whereClause");
$totalRows = $totalResult->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

// Fetch paginated and filtered sermons
$sql = "SELECT * FROM sermons $whereClause ORDER BY sermon_date DESC LIMIT $limit OFFSET $offset";
$sermons = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sermon Downloads</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f6f8; margin: 0; padding: 0; }
        .header { background-color: #003366; color: white; padding: 20px; text-align: center; font-size: 22px; font-weight: bold; }
        .container { max-width: 1100px; margin: 40px auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #003366; }
        form { text-align: center; margin-bottom: 20px; }
        input[type="text"] { padding: 8px; width: 300px; border: 1px solid #ccc; border-radius: 5px; }
        button { padding: 8px 15px; background: #003366; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .sermon { border: 1px solid #ccc; border-radius: 8px; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .sermon h3 { margin: 0; color: #2e7d32; }
        .meta { font-size: 14px; color: #555; margin: 5px 0 10px; }
        .desc { margin: 10px 0; color: #333; }
        .download { background: #2e7d32; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-size: 14px; display: inline-block; margin-top: 10px; }
        .download:hover { background: #27682a; }
        .media-player { margin-top: 10px; width: 100%; }
        .pagination { text-align: center; margin-top: 30px; }
        .pagination a { margin: 0 5px; padding: 6px 12px; background: #ccc; color: #000; text-decoration: none; border-radius: 4px; }
        .pagination a.active { background: #003366; color: white; font-weight: bold; }
        .back { text-align: center; margin-top: 30px; }
        .back a { text-decoration: none; color: #003366; font-weight: bold; }
    </style>
</head>
<body>

<div class="header">
    Church of Pentecost, Asokwa
</div>

<div class="container">
    <h2>Download Sermons</h2>

    <form method="GET">
        <input type="text" name="search" placeholder="Search by title or preacher..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Filter</button>
    </form>

    <?php if ($sermons && $sermons->num_rows > 0): ?>
        <div class="grid">
            <?php while ($sermon = $sermons->fetch_assoc()): ?>
                <div class="sermon">
                    <h3><?php echo htmlspecialchars($sermon['title']); ?></h3>
                    <div class="meta">
                        Preached by <?php echo htmlspecialchars($sermon['preacher']); ?> on 
                        <?php echo date("F j, Y", strtotime($sermon['sermon_date'])); ?>
                    </div>
                    <?php if (!empty($sermon['description'])): ?>
                        <div class="desc">
                            <?php echo nl2br(htmlspecialchars($sermon['description'])); ?>
                        </div>
                    <?php endif; ?>

                    <?php
                        $filePath = $sermon['file_path'];
                        $fileExt = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                        if ($fileExt === 'mp4') {
                            echo "<video class='media-player' controls><source src='".htmlspecialchars($filePath)."' type='video/mp4'></video>";
                        } elseif ($fileExt === 'mp3') {
                            echo "<audio class='media-player' controls><source src='".htmlspecialchars($filePath)."' type='audio/mpeg'></audio>";
                        }
                    ?>

                    <a href="<?php echo htmlspecialchars($filePath); ?>" class="download" download>Download</a>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" class="<?php if ($i == $page) echo 'active'; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
        </div>

    <?php else: ?>
        <p style="text-align:center;">No sermons available yet.</p>
    <?php endif; ?>

    <div class="back">
        <a href="member_dashboard.php">&larr; Back to Dashboard</a>
    </div>
</div>

</body>
</html>
