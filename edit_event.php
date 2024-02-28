<?php
include_once 'connect.php';
include_once 'event_management.php';

// Define variables
$eventName = $ticketPriceVIP = $ticketPriceRegular = $maxAttendees = '';
$eventName_err = $ticketPriceVIP_err = $ticketPriceRegular_err = $maxAttendees_err = '';

// Check if event ID is provided in the URL
if (isset($_GET['id'])) {
    $eventId = $_GET['id'];

    // Fetch event details based on the event ID
    $event = getEventById($eventId);

    if (!$event) {
        echo "Event not found.";
        exit;
    } else {
        // Populate variables with event details
        $eventName = $event['event_name'];
        $ticketPriceVIP = $event['ticket_price_vip'];
        $ticketPriceRegular = $event['ticket_price_regular'];
        $maxAttendees = $event['max_attendees'];
    }
} else {
    echo "Event ID is missing.";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $eventName = $_POST['eventName'];
    $ticketPriceVIP = $_POST['ticketPriceVIP'];
    $ticketPriceRegular = $_POST['ticketPriceRegular'];
    $maxAttendees = $_POST['maxAttendees'];

    // Image upload handling
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is a valid image
    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if ($uploadOk == 1 && move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Update event details in the database
            if (editEvent($eventId, $_POST['eventName'], $_POST['ticketPriceVIP'], $_POST['ticketPriceRegular'], $_POST['maxAttendees'], $target_file)) {
                header("Location: admin_dashboard.php");
                exit;
            } else {
                echo "Failed to update event.";
            }
        }
        
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="edit-event-container">
        <h2>Edit Event</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $eventId; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Event Name</label>
                <input type="text" name="eventName" value="<?php echo $eventName; ?>">
            </div>
            <div class="form-group">
                <label>Ticket Price for VIP</label>
                <input type="text" name="ticketPriceVIP" value="<?php echo $ticketPriceVIP; ?>">
            </div>
            <div class="form-group">
                <label>Ticket Price for Regular</label>
                <input type="text" name="ticketPriceRegular" value="<?php echo $ticketPriceRegular; ?>">
            </div>
            <div class="form-group">
                <label>Max Attendees</label>
                <input type="text" name="maxAttendees" value="<?php echo $maxAttendees; ?>">
            </div>
            <div class="form-group">
                <label for="image">Event Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            <div class="form-group">
                <button type="submit">Update Event</button>
            </div>
        </form>
    </div>
</body>
</html>
