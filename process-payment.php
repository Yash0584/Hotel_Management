<?php
session_start();

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: booking.html");
    exit();
}

// Retrieve the form data
$card_number = htmlspecialchars($_POST['card_number']);
$expiry_date = htmlspecialchars($_POST['expiry_date']);
$cvv = htmlspecialchars($_POST['cvv']);
$cardholder_name = htmlspecialchars($_POST['cardholder_name']);
$booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;

// Check if session data exists
if (!isset($_SESSION['room_type']) || !isset($_SESSION['num_nights']) || !isset($_SESSION['final_payment'])) {
    echo "Payment session data not found. Please go back and try again.";
    exit();
}

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

// Generate a transaction ID
$transaction_id = "TXN" . time() . rand(1000, 9999);

// Insert payment record into database
$payment_sql = "INSERT INTO payments (user_id, booking_id, amount, payment_method, transaction_id, payment_status, payment_date) 
                VALUES (?, ?, ?, 'credit_card', ?, 'completed', NOW())";

$stmt = $conn->prepare($payment_sql);
$stmt->bind_param("iids", $user_id, $booking_id, $_SESSION['final_payment'], $transaction_id);

$payment_success = $stmt->execute();

// Update booking status
if ($payment_success) {
    $update_sql = "UPDATE bookings SET payment_status = 'paid' WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $booking_id);
    $update_stmt->execute();
}

// Close database connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation - Hotel Management</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .confirmation-page {
            max-width: 800px;
            margin: 100px auto;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            text-align: center;
        }

        .confirmation-page h1 {
            color: #4ecdc4;
            margin-bottom: 30px;
        }

        .confirmation-details {
            margin: 30px 0;
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .success-icon {
            font-size: 5rem;
            color: #4ecdc4;
            margin-bottom: 20px;
        }

        .btn-home {
            display: inline-block;
            padding: 15px 30px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            margin-top: 30px;
        }

        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 205, 196, 0.4);
            background: linear-gradient(45deg, #ff6b6b, #45b7d1);
        }

        .transaction-id {
            font-family: monospace;
            background: rgba(255, 255, 255, 0.1);
            padding: 5px 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="aboutus.html">About Us</a></li>
                <li><a href="contact.html">Contact Us</a></li>
                <li><a href="login.html">Login</a></li>
            </ul>
        </nav>
    </header>

    <div class="confirmation-page">
        <div class="success-icon">✓</div>
        <h1>Payment Successful!</h1>
        <p>Your booking has been confirmed and your payment has been processed successfully.</p>
        
        <div class="confirmation-details">
            <div class="detail-row">
                <span>Transaction ID:</span>
                <span class="transaction-id"><?php echo $transaction_id; ?></span>
            </div>
            <div class="detail-row">
                <span>Room Type:</span>
                <span><?php echo ucfirst($_SESSION['room_type']); ?> Room</span>
            </div>
            <div class="detail-row">
                <span>Number of Nights:</span>
                <span><?php echo $_SESSION['num_nights']; ?></span>
            </div>
            <div class="detail-row">
                <span>Amount Paid:</span>
                <span>₹<?php echo number_format($_SESSION['final_payment'], 2); ?></span>
            </div>
            <div class="detail-row">
                <span>Payment Method:</span>
                <span>Credit Card (xxxx-xxxx-xxxx-<?php echo substr($card_number, -4); ?>)</span>
            </div>
            <div class="detail-row">
                <span>Payment Date:</span>
                <span><?php echo date('d M Y, h:i A'); ?></span>
            </div>
        </div>
        
        <p>A confirmation email has been sent to your registered email address.</p>
        <p>Thank you for choosing our hotel!</p>
        
        <a href="index.html" class="btn-home">Return to Home</a>
    </div>

    <footer>
        <p>&copy; 2024 Hotel Management. All rights reserved.</p>
    </footer>
</body>
</html>