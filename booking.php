<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "hotel_management";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form values
$room_type = $_POST['room-type'];
$check_in = $_POST['check-in'];
$check_out = $_POST['check-out'];
$full_name = $_POST['full-name'];
$email = $_POST['email'];
$facilities = '';

if (isset($_POST['facilities'])) {
    // Handle multiple checkboxes
    if (is_array($_POST['facilities'])) {
        $facilities = implode(", ", $_POST['facilities']);
    } else {
        $facilities = $_POST['facilities'];
    }
}

// Insert into database
$sql = "INSERT INTO bookings (room_type, check_in, check_out, full_name, email, facilities) 
        VALUES ('$room_type', '$check_in', '$check_out', '$full_name', '$email', '$facilities')";

if ($conn->query($sql) === TRUE) {
    echo "<h2>Booking successful!</h2><p><a href='index.html'>Return to Home</a></p>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
