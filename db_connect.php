<?php
// db_connect.php

$host = "localhost";
$user = "root";
$password = ""; // change if you have a password
$dbname = "church_db";

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
