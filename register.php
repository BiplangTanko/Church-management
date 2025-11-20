<?php
session_start();
include "db_connect.php";

// Define both to avoid undefined variable errors
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $marital_status = $_POST['marital_status'];
    $address = $_POST['address'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT id FROM members WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $error = "Email already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO members (full_name, email, phone, dob, gender, marital_status, address, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $full_name, $email, $phone, $dob, $gender, $marital_status, $address, $password);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Registration successful! Please login.";
            header("Location: login.php");
            exit();
        } else {
            $error = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Member Registration</title>
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
            letter-spacing: 1px;
        }

        .container {
            width: 400px;
            background: white;
            margin: 40px auto;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #003366;
            margin-bottom: 25px;
        }

        input, select, textarea, button {
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
            transition: background 0.3s ease;
        }

        button:hover {
            background: #27682a;
        }

        .success {
            color: #2e7d32;
            text-align: center;
            margin-bottom: 15px;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        Church of Pentecost, Asokwa
    </div>

    <div class="container">
        <h2>Member Registration</h2>
        <?php if ($success) echo "<p class='success'>$success</p>"; ?>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>

        <form method="POST" action="">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number">
            <input type="date" name="dob" placeholder="Date of Birth">
            <select name="gender" required>
                <option value="">-- Select Gender --</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <select name="marital_status" required>
                <option value="">-- Marital Status --</option>
                <option value="Single">Single</option>
                <option value="Married">Married</option>
            </select>
            <textarea name="address" placeholder="Address"></textarea>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
