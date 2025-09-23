<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "concert_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['contact-name'] ?? '';
    $email     = $_POST['contact-email'] ?? '';
    $company   = $_POST['contact-company'] ?? '';
    $message   = $_POST['contact-message'] ?? '';

    $stmt = $conn->prepare("INSERT INTO contacts (full_name, email, company, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $full_name, $email, $company, $message);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Message sent successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to send message."]);
    }

    $stmt->close();
    $conn->close();
}
?>
