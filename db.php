<?php
$servername = "localhost";
$username   = "root";   // XAMPP mặc định
$password   = "";       // XAMPP mặc định
$dbname     = "concert_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("DB connect failed: " . $conn->connect_error); }
?>
