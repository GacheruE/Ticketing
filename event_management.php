<?php
// Include database connection file
include_once 'connect.php';

// Function to add a new event
function addEvent($eventName, $ticketPriceVIP, $ticketPriceRegular, $maxAttendees, $images) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO events (event_name, ticket_price_vip, ticket_price_regular, max_attendees, images) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sddds", $eventName, $ticketPriceVIP, $ticketPriceRegular, $maxAttendees, $images);
    return $stmt->execute();
}

// Function to edit event details
function editEvent($eventId, $eventName, $ticketPriceVIP, $ticketPriceRegular, $maxAttendees, $images) {
    global $conn;
    $stmt = $conn->prepare("UPDATE events SET event_name = ?, ticket_price_vip = ?, ticket_price_regular = ?, max_attendees = ?, images = ? WHERE id = ?");
    $stmt->bind_param("sdddsi", $eventName, $ticketPriceVIP, $ticketPriceRegular, $maxAttendees, $images, $eventId);
    return $stmt->execute();
}

// Function to delete an event
function deleteEvent($eventId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $eventId);
    return $stmt->execute();
}

// Function to get event details by ID
function getEventById($eventId) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to get total number of events
function getTotalEvents() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) FROM events");
    return $result->fetch_assoc()['COUNT(*)'];
}

// Function to get total number of tickets sold for an event
function getTotalTicketsSoldForEvent($eventId) {
    global $conn;
    $stmt = $conn->prepare("SELECT SUM(tickets_sold) AS total_tickets_sold FROM ticket_sales WHERE event_id = ?");
    $stmt->bind_param("i", $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total_tickets_sold'];
}


// Function to get events from the database
function getEvents() {
    global $conn;
    $events = array(); // Initialize an empty array to store events
    $result = $conn->query("SELECT * FROM events");

    // Check if query was successful
    if ($result) {
        // Fetch each row from the result set and store it in the events array
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    } else {
        // Handle error if query fails
        echo "Error: " . $conn->error;
    }

    return $events;
}
?>
