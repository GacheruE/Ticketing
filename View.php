<?php
session_start(); // Start the session

// Check if the user is logged in
if(isset($_SESSION['user_name'])) {
    // User is logged in, retrieve the user's name, id and type
    $userName = $_SESSION['user_name'];
    $userType = $_SESSION['user_type'];
    $userId =  $_SESSION['user_id'] ;
} else {
    // User is not logged in, redirect to the login page
    header("Location: login.php");
    exit(); // Stop further execution of the script
}

// Include database connection file and event management functions
include_once 'connect.php';
include_once 'event_management.php'; // Contains getEvents function

// Fetch events from the database
$events = getEvents();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Page</title>
    <link rel="stylesheet" href="style.css">
    <?php include'header.php';?>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $userName; ?>! Upcoming Events</h1>
        <div class="events-container">
            <?php foreach ($events as $event): ?>
                <div class="event">
                    <img src="<?php echo $event['images']; ?>" alt="<?php echo $event['event_name']; ?>">
                    <h2><?php echo $event['event_name']; ?></h2>
                    <p>VIP Ticket Price: Ksh.<?php echo $event['ticket_price_vip']; ?></p>
                    <p>Regular Ticket Price: Ksh.<?php echo $event['ticket_price_regular']; ?></p>
                    <a href='Order.php?id=<?php echo $event['id']; ?>' class='btn btn-primary'>Buy Now</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
