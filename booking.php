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
    // Get the last inserted booking ID
    $booking_id = $conn->insert_id;
    
    // Assuming you have a session or method to get the user_id (e.g., from session or authentication)
    // Here, for simplicity, let's assume we pass the user ID from the form
    $user_id = 1; // Replace this with actual user data, e.g., from $_SESSION['user_id']
    
    // Redirect to payment page with booking ID and user ID
    header("Location: payment.html?id=$user_id&booking_id=$booking_id");
    exit(); // Stop script execution after the redirect
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
