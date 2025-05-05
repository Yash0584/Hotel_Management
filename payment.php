<?php
session_start();

// Check if session data exists
if (!isset($_SESSION['room_type']) || !isset($_SESSION['num_nights']) || !isset($_SESSION['price_per_night'])) {
    echo "Booking details not found. Please go back and book a room.";
    exit();
}

// Retrieve session data
$room_type = $_SESSION['room_type'];
$num_nights = $_SESSION['num_nights'];
$price_per_night = $_SESSION['price_per_night'];

// Calculate total amount, discount, tax, etc.
$total_amount = $num_nights * $price_per_night;
$discount = $total_amount * 0.10;  // 10% discount
$discounted_amount = $total_amount - $discount;
$tax = $discounted_amount * 0.18;  // 18% tax
$final_payment = $discounted_amount + $tax;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container">
        <h1>Payment Information</h1>

        <!-- Booking Details Section -->
        <h2>Booking Details</h2>
        <p><strong>Room Type:</strong> <?php echo htmlspecialchars($room_type); ?></p>
        <p><strong>Number of Nights:</strong> <?php echo htmlspecialchars($num_nights); ?></p>
        <p><strong>Amount (Per Night):</strong> ₹<?php echo htmlspecialchars($price_per_night); ?></p>
        <p><strong>Total Amount:</strong> ₹<?php echo htmlspecialchars($total_amount); ?></p>

        <!-- Discount, Tax, and Final Payment -->
        <h3>Payment Breakdown</h3>
        <p><strong>Discount Applied (10%):</strong> ₹<?php echo htmlspecialchars($discount); ?></p>
        <p><strong>Discounted Amount:</strong> ₹<?php echo htmlspecialchars($discounted_amount); ?></p>
        <p><strong>Tax (18%):</strong> ₹<?php echo htmlspecialchars($tax); ?></p>

        <h2><strong>Final Payment Amount:</strong> ₹<?php echo htmlspecialchars($final_payment); ?></h2>

        <!-- Payment Options -->
        <h3>Select Payment Method</h3>
        <form action="process-payment.php" method="POST">
            <div class="form-group">
                <label>
                    <input type="radio" name="payment_method" value="credit_card" required> Credit Card
                </label><br>
                <label>
                    <input type="radio" name="payment_method" value="bank_transfer"> Bank Transfer
                </label><br>
                <label>
                    <input type="radio" name="payment_method" value="upi"> UPI
                </label><br>
                <label>
                    <input type="radio" name="payment_method" value="paypal"> PayPal
                </label><br>
                <label>
                    <input type="radio" name="payment_method" value="cash_on_delivery"> Cash on Delivery
                </label><br>
            </div>

            <!-- Credit Card Payment Details (only visible when Credit Card is selected) -->
            <div id="credit_card_details" style="display:none;">
                <h4>Credit Card Details</h4>
                <div class="form-group">
                    <label for="card_number">Card Number</label>
                    <input type="text" id="card_number" name="card_number">
                </div>
                <div class="form-group">
                    <label for="expiry_date">Expiry Date (MM/YY)</label>
                    <input type="text" id="expiry_date" name="expiry_date">
                </div>
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" id="cvv" name="cvv">
                </div>
                <div class="form-group">
                    <label for="cardholder_name">Cardholder Name</label>
                    <input type="text" id="cardholder_name" name="cardholder_name">
                </div>
            </div>

            <!-- Bank Transfer Details (only visible when Bank Transfer is selected) -->
            <div id="bank_transfer_details" style="display:none;">
                <h4>Bank Transfer Details</h4>
                <p>Please transfer the amount to the following bank account:</p>
                <p><strong>Account Name:</strong> XYZ Bank</p>
                <p><strong>Account Number:</strong> 123456789</p>
                <p><strong>IFSC Code:</strong> XYZ0001234</p>
            </div>

            <!-- UPI Details (only visible when UPI is selected) -->
            <div id="upi_details" style="display:none;">
                <h4>UPI Payment</h4>
                <p>Pay to the following UPI ID: <strong>example@upi</strong></p>
            </div>

            <button type="submit">Proceed to Pay</button>
        </form>
    </div>

    <script>
        // Toggle payment details based on selected payment method
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        paymentMethods.forEach((method) => {
            method.addEventListener('change', function () {
                // Hide all payment details
                document.getElementById('credit_card_details').style.display = 'none';
                document.getElementById('bank_transfer_details').style.display = 'none';
                document.getElementById('upi_details').style.display = 'none';
                
                // Show selected payment method details
                if (this.value === 'credit_card') {
                    document.getElementById('credit_card_details').style.display = 'block';
                } else if (this.value === 'bank_transfer') {
                    document.getElementById('bank_transfer_details').style.display = 'block';
                } else if (this.value === 'upi') {
                    document.getElementById('upi_details').style.display = 'block';
                }
            });
        });
    </script>

</body>
</html>
