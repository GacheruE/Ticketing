<?php
// Include database connection file and event management functions
include_once 'connect.php';
include_once 'event_management.php'; // Contains addEvent function

// Define variables and initialize with empty values
$eventName = $ticketPriceVIP = $ticketPriceRegular = $maxAttendees = '';
$eventName_err = $ticketPriceVIP_err = $ticketPriceRegular_err = $maxAttendees_err = '';

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate event name
    if (empty(trim($_POST["eventName"]))) {
        $eventName_err = "Please enter the event name.";
    } else {
        $eventName = trim($_POST["eventName"]);
    }

    // Validate ticket price for VIP
    if (empty(trim($_POST["ticketPriceVIP"]))) {
        $ticketPriceVIP_err = "Please enter the ticket price for VIP.";
    } else {
        $ticketPriceVIP = trim($_POST["ticketPriceVIP"]);
    }

    // Validate ticket price for Regular
    if (empty(trim($_POST["ticketPriceRegular"]))) {
        $ticketPriceRegular_err = "Please enter the ticket price for Regular.";
    } else {
        $ticketPriceRegular = trim($_POST["ticketPriceRegular"]);
    }

    // Validate max attendees
    if (empty(trim($_POST["maxAttendees"]))) {
        $maxAttendees_err = "Please enter the maximum number of attendees.";
    } else {
        $maxAttendees = trim($_POST["maxAttendees"]);
    }

    // Check if file is uploaded
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES['image']['tmp_name']);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES['image']['size'] > 500000) {
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // if everything is ok, try to upload file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_path = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "Please select an image.";
    }

    // Check input errors before adding to database
    if (empty($eventName_err) && empty($ticketPriceVIP_err) && empty($ticketPriceRegular_err) && empty($maxAttendees_err) && !empty($image_path)) {
        // Call the addEvent function
        if (addEvent($eventName, $ticketPriceVIP, $ticketPriceRegular, $maxAttendees, $image_path)) {
            // Event added successfully
            header("location: admin_dashboard.php");
            exit();
        } else {
            // Error adding event
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="add-event-container">
        <h2>Add Event</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Event Name</label>
                <input type="text" name="eventName" value="<?php echo $eventName; ?>">
                <span class="error"><?php echo $eventName_err; ?></span>
            </div>
            <div class="form-group">
                <label>Ticket Price for VIP</label>
                <input type="text" name="ticketPriceVIP" value="<?php echo $ticketPriceVIP; ?>">
                <span class="error"><?php echo $ticketPriceVIP_err; ?></span>
            </div>
            <div class="form-group">
                <label>Ticket Price for Regular</label>
                <input type="text" name="ticketPriceRegular" value="<?php echo $ticketPriceRegular; ?>">
                <span class="error"><?php echo $ticketPriceRegular_err; ?></span>
            </div>
            <div class="form-group">
                <label>Max Attendees</label>
                <input type="text" name="maxAttendees" value="<?php echo $maxAttendees; ?>">
                <span class="error"><?php echo $maxAttendees_err; ?></span>
            </div>
            <div class="form-group">
                <label for="image">Event Image:</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <div class="form-group">
                <button type="submit">Add Event</button>
            </div>
        </form>
    </div>
</body>
</html>
