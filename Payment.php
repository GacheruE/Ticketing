<?php
// Start the session
session_start();

include_once 'connect.php';
include_once 'event_management.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: View.php");
    exit();
}

$eventId = $_GET['id'];
$event = getEventById($eventId);

// Check if the event exists
if (!$event) {
    header("Location: View.php");
    exit();
}

if (isset($_POST['ticket-type']) && isset($_POST['quantity'])) {
    $ticketType = $_POST['ticket-type'];
    $quantity = $_POST['quantity'];
    $totalAmount = $event['ticket_price_' . $ticketType] * $quantity;

    $_SESSION['event_id'] = $eventId;
    $_SESSION['event_name'] = $event['event_name'];
    $_SESSION['ticket_type'] = $ticketType;
    $_SESSION['quantity'] = $quantity;

    // Ensure that the number of tickets sold does not exceed the maximum number of attendees
    $totalTicketsSold = getTotalTicketsSoldForEvent($eventId);
    $maxAttendees = $event['max_attendees'];
    if ($totalTicketsSold + $quantity > $maxAttendees) {
      echo "<script>alert('Aplogies. Tickets for this event are sold out. Kindly choose another event.');</script>";
      echo "<script>window.location.href = 'View.php';</script>";
        exit();
    }

    $purchaseDate = date('Y-m-d H:i:s');
// Get user ID from session
$userId = $_SESSION['user_id'];

$insertQuery = "INSERT INTO ticket_sales (event_id, user_id, tickets_sold, sale_date) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($insertQuery);
$stmt->bind_param("iiss", $eventId, $userId, $quantity, $purchaseDate);


    if ($stmt->execute()) {
        // Redirect to the payment page using Flutterwave Inline
        echo "<script src='https://checkout.flutterwave.com/v3.js'></script>
              <link rel='stylesheet' type='text/css' href='boot.css' media='screen' />
              <?php include'header.php';?>
              <title>Payment</title>
              <form>
                <center>
                  <div>
                    <h3><b><i>Your order is <span style='color: purple;'>Ksh. " . ($ticketType === 'vip' ? $event['ticket_price_vip'] : $event['ticket_price_regular']) * $quantity . "</span></i></b></h3>
                    <button type='button' id='start-payment-button' onclick='makePayment()'>Pay Now</button><br>
                    <img src='" . $event['images'] . "'>
                  </div>
                  <link rel='icon' type='image/png' href='path/to/download.png'>
                  <style>
                  body {
                    background-color: #333;
                    color: white;
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                }
            
                form {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                }
            
                div {
                    width: 300px;
                    height: 210px;
                    background-color: white;
                    text-align: center;
                    padding: 20px;
                    padding-bottom: 20px;
                    border-radius: 10px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }
            
                img {
                    width: 115px;
                    height: 115px;
                    margin-top: 20px;
                }
            
                h3 {
                    color: orange;
                    margin-bottom: 10px;
                }
            
                button {
                    background-color: orange;
                    color: white;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                }
            
                button:hover {
                    background-color: #ff9800;
                }
                  </style>
                </center>
              </form>
              <script>
                function makePayment() {
                  FlutterwaveCheckout({
                    public_key: 'FLWPUBK_TEST-ce4b316565048b6eee99003c9461c691-X',
                    tx_ref: 'titanic-48981487343MDI0NzMx',
                    amount: " . ($ticketType === 'vip' ? $event['ticket_price_vip'] : $event['ticket_price_regular']) * $quantity . ",
                    currency: 'KES',
                    payment_options: 'card, mobilemoney',
                    redirect_url: '//localhost/Cytonn/Confirmation.php?eventId=<?php echo $eventId; ?>&ticketType=<?php echo $ticketType; ?>&quantity=<?php echo $quantity; ?>',

                    meta: {
                      consumer_id: 23,
                      consumer_mac: '92a3-912ba-1192a',
                    },
                    customer: { 
                      email: 'nyangendogacheru@gmail.com',
                      phone_number: '',
                      name: 'Esther Nyangendo Gacheru',
                    },
                    customizations: {
                      title: 'Event Ticket Payment',
                      description: 'Payment for event tickets',
                      logo: 'assets/Logo1.png',
                    },
                    
                  });
                }
              </script>";

    } else {
        $errorMessage = "Failed to place the order. Please try again.";
    }
}
?>
