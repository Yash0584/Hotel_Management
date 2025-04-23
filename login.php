<?php
// Start session
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "hotel_management");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form input
$username = $_POST['username'];
$password = $_POST['password'];

// Query to check user
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

// Validate user
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username;
        header("Location: booking.html");
        exit();
    } else {
        echo "Invalid password.";
    }
} else {
    echo "User not found.";
}

$conn->close();
?>
