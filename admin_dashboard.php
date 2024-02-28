<?php
// Include database connection file and user/event management functions
include_once 'connect.php';
include_once 'user_management.php'; 
include_once 'event_management.php'; 


$totalUsers = getTotalUsers();
$totalEvents = getTotalEvents();


// Handle user management functionalities
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_user':
                // Handle add user functionality
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $userType = $_POST['userType'];
                addUser($username, $email, $password, $userType);
                break;
            case 'edit_user':
                // Handle edit user functionality
                $id = $_POST['id'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $userType = $_POST['userType'];
                editUser($id, $username, $email, $password, $userType);
                break;
            case 'delete_user':
                // Handle delete user functionality
                $id = $_POST['id'];
                deleteUser($id);
                break;
            case 'add_event':
                // Handle add event functionality
                $images = $_POST['images'];
                $eventName = $_POST['eventName'];
                $ticketPriceVIP = $_POST['ticketPriceVIP'];
                $ticketPriceRegular = $_POST['ticketPriceRegular'];
                $maxAttendees = $_POST['maxAttendees'];
                addEvent($eventName, $ticketPriceVIP, $ticketPriceRegular, $maxAttendees);
                break;
            case 'edit_event':
                // Handle edit event functionality
                // Similar to add event functionality
                break;
            case 'delete_event':
                // Handle delete event functionality
                $eventId = $_POST['eventId'];
                deleteEvent($eventId);
                break;
            default:
                // Handle invalid action
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <?php include'header.php';?>
</head>
<body>
    <div class="admin-dashboard">
        <h2>Welcome to the Admin Dashboard</h2>
        <div class="analytics">
            <h3>Analytics</h3>
            <p>Total Users: <?php echo $totalUsers; ?></p>
            <p>Total Events: <?php echo $totalEvents; ?></p>
        </div>
         <!-- User Management Section -->
    <div class="user-management">
        <h3>User Management</h3>
        <div class="add-new">
            <a href="add_user.php">Add New User</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch users from the database
                $users = getUsers(); 
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>{$user['id']}</td>";
                    echo "<td>{$user['UserName']}</td>";
                    echo "<td>{$user['Email']}</td>";
                    echo "<td>{$user['UserType']}</td>";
                    echo "<td>
                            <a href='edit_user.php?id={$user['id']}'>Edit</a> |
                            <a href='delete_user.php?id={$user['id']}'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Event Management Section -->
    <div class="event-management">
        <h3>Event Management</h3>
        <div class="add-new">
            <a href="add_event.php">Add New Event</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Event Name</th>
                    <th>Ticket Price (VIP)</th>
                    <th>Ticket Price (Regular)</th>
                    <th>Max Attendees</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch events from the database
                $events = getEvents(); // You need to implement this function to retrieve events from the database

                foreach ($events as $event) {
                    echo "<tr>";
                    echo "<td>{$event['id']}</td>";
                    echo "<td>{$event['images']}</td>";
                    echo "<td>{$event['event_name']}</td>";
                    echo "<td>{$event['ticket_price_vip']}</td>";
                    echo "<td>{$event['ticket_price_regular']}</td>";
                    echo "<td>{$event['max_attendees']}</td>";
                    echo "<td>
                            <a href='edit_event.php?id={$event['id']}'>Edit</a> |
                            <a href='delete_event.php?id={$event['id']}'>Delete</a>
                          </td>";
                    echo "</tr>";
                }
                ?>
                </tbody>
                </table>
                </div>
                <!-- Events and Tickets Sold Section -->
<div class="events-and-tickets-sold">
    <h3>Events and Tickets Sold</h3>
    <table>
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Total Tickets Sold</th>
                <th>Percentage Sold</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch events and calculate total tickets bought and percentage sold for each event
$events = getEvents();

foreach ($events as $event) {
    $eventId = $event['id'];
    $totalTicketsSold = getTotalTicketsSoldForEvent($eventId); // Fetch total tickets sold for the event

    $total_tickets_sold = $totalTicketsSold; // Total tickets sold for the event
    $maxAttendees = $event['max_attendees']; // Maximum attendees for the event

    // Calculate the percentage sold
    $percentageSold = ($total_tickets_sold / $maxAttendees) * 100;

    echo "<tr>";
    echo "<td>{$event['event_name']}</td>";
    echo "<td>{$total_tickets_sold}</td>";
    echo "<td>{$percentageSold}%</td>";
    echo "</tr>";
}
            ?>
        </tbody>
    </table>
</div>

            </tbody>
        </table>
    </div>
</div>
    </div>
</body>
</html>
