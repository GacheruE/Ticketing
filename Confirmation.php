<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Start the session
session_start();

// Include necessary files
include_once 'connect.php';
include_once 'event_management.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

// Check if the payment was successful
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    // Retrieve data from the payment process
    $email = $_POST['email'];

    // Get event details from session or database (assuming you store them in the session)
    $eventId = $_SESSION['event_id'];
    $eventName = $_SESSION['event_name'];
    $ticketType = $_SESSION['ticket_type'];
    $quantity = $_SESSION['quantity'];

    // Compose email message
    $subject = 'Event Ticket Confirmation';
    $message = "Dear customer,\n\n";
    $message .= "Thank you for your purchase. Below are the details of your booking:\n\n";
    $message .= "Event: $eventName\n";
    $message .= "Ticket Type: $ticketType\n";
    $message .= "Quantity: $quantity\n\n";
    $message .= "We look forward to seeing you at the event!\n\n";
    $message .= "Best regards,\n";
    $message .= "Event Management Team";

    // Send email using PHPMailer
    require 'vendor/autoload.php'; // Adjust the path as per your project structure

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'nyangendogacheru@gmail.com'; // Your Gmail email address
        $mail->Password   = 'uhck hcdk sdac vklg';         // Your Gmail password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        //Recipients
        $mail->setFrom('nyangendogacheru@gmail.com', 'Event Management Team');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo "<script>alert('Confirmation email sent successfully. Thank you!')</script>";
    } catch (Exception $e) {
        echo "Failed to send confirmation email. Please contact support. Error: {$mail->ErrorInfo}";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <link rel="stylesheet" href="boots.css">
    <?php include'header.php';?>
</head>
<body>
    <div class="confirmation-container">
        <h2>Confirmation</h2>
        <p>Thank you for your payment. Please enter your email address below to receive your ticket confirmation.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="email">Email Address:</label>
            <input type="email" name="email" id="email" required>
            <button type="submit">Send Confirmation Email</button>

        </form>
    </div>
</body>
</html>
