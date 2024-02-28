<?php
// Start the session
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_name'])) {
    // User is logged in, retrieve the user's name
    $userName = $_SESSION['user_name'];
} else {
    // User is not logged in, redirect to the login page
    header("Location: login.php");
    exit(); // Stop further execution of the script
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tickets</title>
    <link rel="stylesheet" href="boot.css">
    <?php include'header.php';?>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $userName; ?>! Order Tickets</h1>
        <div class="order-form">
            <?php
            // Check if event ID is provided in the URL
            if (!isset($_GET['id']) || empty($_GET['id'])) {
                // Redirect to the event page if event ID is missing
                header("Location: View.php");
                exit();
            }

            // Include database connection file and event management functions
            include_once 'connect.php';
            include_once 'event_management.php';

            // Fetch event details based on the provided ID
            $eventId = $_GET['id'];
            $event = getEventById($eventId);

            // Check if the event exists
            if (!$event) {
                // Redirect to the event page if event is not found
                header("Location: View.php");
                exit();
            }
            ?>

            <img src="<?php echo $event['images']; ?>" alt="<?php echo $event['event_name']; ?>">

            <form action="Payment.php?id=<?php echo $eventId; ?>" method="post">
                <div class="form-group">
                    <label for="ticket-type">Ticket Type:</label>
                    <select name="ticket-type" id="ticket-type" onchange="calculateTotal()">
                        <option value="vip">VIP</option>
                        <option value="regular">Regular</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity (Max 5):</label>
                    <input type="number" name="quantity" id="quantity" min="1" max="5" value="1" onchange="calculateTotal()">
                </div>

                <div class="form-group">
                    <p>Total Amount: <span id="total-amount">Ksh.<?php echo $event['ticket_price_vip']; ?></span></p>
                </div>

                <input type="hidden" name="event-id" value="<?php echo $eventId; ?>">
                <button type="submit" class="btn btn-primary">Proceed to Payment</button>
            </form>
        </div>
    </div>

    <script>
        // JavaScript function to calculate total amount based on ticket price and quantity
        function calculateTotal() {
            var ticketPrice = 0;
            var ticketType = document.getElementById('ticket-type').value;
            var quantity = document.getElementById('quantity').value;

            // Determine ticket price based on ticket type
            if (ticketType === 'vip') {
                ticketPrice = <?php echo ($event['ticket_price_vip']); ?>;
            } else {
                ticketPrice = <?php echo ($event['ticket_price_regular']); ?>;
            }

            var totalAmount = ticketPrice * quantity;

            document.getElementById('total-amount').textContent = 'Ksh.' + totalAmount;
        }
    </script>
</body>
</html>
