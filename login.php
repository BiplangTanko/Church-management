<?php
session_start();
include "db_connect.php";

$success = "";
$error = "";

// Handle redirect success message from registration
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check user in database
    $stmt = $conn->prepare("SELECT id, full_name, password FROM members WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['member_id'] = $user['id'];
            $_SESSION['member_name'] = $user['full_name'];
            header("Location: member_dashboard.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f8;
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
            width: 350px;
            background: white;
            margin: 50px auto;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #003366;
            margin-bottom: 20px;
        }

        input, button {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        button {
            background: #2e7d32;
            color: white;
            font-weight: bold;
            border: none;
        }

        .popup, .error {
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .popup {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: none;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <div class="header">
        Church of Pentecost, Asokwa
    </div>

    <div class="container">
        <h2>Member Login</h2>

        <?php if ($success): ?>
            <div id="popup" class="popup"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>

    <script>
        const popup = document.getElementById("popup");
        if (popup) {
            popup.style.display = "block";
            setTimeout(() => {
                popup.style.display = "none";
            }, 4000);
        }
    </script>
</body>
</html>
