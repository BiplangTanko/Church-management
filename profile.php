<?php
session_start();
include "db_connect.php";

// Restrict access
if (!isset($_SESSION['member_id'])) {
    header("Location: login.php");
    exit();
}

$member_id = $_SESSION['member_id'];
$success = "";
$error = "";

// Fetch current member info
$stmt = $conn->prepare("SELECT full_name, email, phone, dob, gender, marital_status, address FROM members WHERE id = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
$member = $result->fetch_assoc();

// Handle update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $new_password = $_POST['password'];

    if (!empty($new_password)) {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE members SET phone=?, address=?, password=? WHERE id=?");
        $update->bind_param("sssi", $phone, $address, $hashed, $member_id);
    } else {
        $update = $conn->prepare("UPDATE members SET phone=?, address=? WHERE id=?");
        $update->bind_param("ssi", $phone, $address, $member_id);
    }

    if ($update->execute()) {
        $success = "Profile updated successfully.";
    } else {
        $error = "Update failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
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

        input[readonly] {
            background-color: #f5f5f5;
            font-weight: bold;
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
    <h2>My Profile</h2>

    <?php if ($success): ?>
        <div class="message success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="message error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" value="<?php echo htmlspecialchars($member['full_name']); ?>" readonly>
        <input type="email" value="<?php echo htmlspecialchars($member['email']); ?>" readonly>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($member['phone']); ?>" placeholder="Phone Number">
        <input type="text" value="<?php echo htmlspecialchars($member['dob']); ?>" readonly>
        <input type="text" value="<?php echo htmlspecialchars($member['gender']); ?>" readonly>
        <input type="text" value="<?php echo htmlspecialchars($member['marital_status']); ?>" readonly>
        <textarea name="address" rows="3" placeholder="Address"><?php echo htmlspecialchars($member['address']); ?></textarea>
        <input type="password" name="password" placeholder="New Password (leave blank to keep current)">
        <button type="submit">Update Profile</button>
    </form>

    <div class="back">
        <a href="member_dashboard.php">&larr; Back to Dashboard</a>
    </div>
</div>

</body>
</html>
