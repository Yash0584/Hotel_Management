<?php
$conn = new mysqli("localhost", "root", "", "hotel_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);

if ($stmt->execute()) {
    header("Location: login.html");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
