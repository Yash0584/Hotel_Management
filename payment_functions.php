<?php
// Establish database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "your_database"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to insert payment data into the database
function insert_payment($user_id, $booking_id, $amount, $payment_method, $transaction_id) {
    global $conn;  // Accessing the database connection
    
    // Prepare the SQL query
    $query = "INSERT INTO payments (user_id, booking_id, amount, payment_method, transaction_id, payment_status)
              VALUES (?, ?, ?, ?, ?, 'pending')";
    
    // Prepare statement
    $stmt = $conn->prepare($query);
    
    // Bind parameters (i = int, d = double, s = string)
    $stmt->bind_param("iids", $user_id, $booking_id, $amount, $payment_method, $transaction_id);
    
    // Execute the query
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
    
    $stmt->close();
}

// Function to update payment status
function update_payment_status($payment_id, $status) {
    global $conn;
    
    $query = "UPDATE payments SET payment_status = ? WHERE payment_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $status, $payment_id);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
    
    $stmt->close();
}
?>
