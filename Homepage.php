<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Booking System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <?php include'header.php';?>
</head>
<body>
<header>
    <img src="assets/homes.jpg" alt="Ticket Booking System">
    <div class="browse-btn-container">
            <a href="View.php" class="browse-btn">Browse Events</a>
    </div>
</header>


<section class="about">
    <div class="card-container">
        <div class="card">
            <h2>Mission</h2>
            <p>Our mission is to provide seamless ticket booking experience for our users.</p>
        </div>
        <div class="card">
            <h2>Vision</h2>
            <p>We envision a world where everyone can easily access and enjoy their favorite events.</p>
        </div>
        <div class="card">
            <h2>Goals</h2>
            <p>Our goals include enhancing user experience, expanding event offerings, and ensuring customer satisfaction.</p>
        </div>
    </div>
</section>


    <section class="events">
        <h2>Upcoming Events</h2>
        <div class="event-images">
            <img src="assets/MKO.pNg" alt="Event 1">
            <img src="assets/polo.jpg" alt="Event 2">
            <img src="assets/raha.jpg" alt="Event 3">
        </div>
    </section>

    <section class="testimonials">
        <h2>Testimonials</h2>
        <div class="testimonial">
            <p>"Great service! Easy to use platform and quick ticket booking process."</p>
            <p>- John Doe</p>
        </div>
        <div class="testimonial">
            <p>"I love this ticket booking system. It's my go-to for all events!"</p>
            <p>- Jane Smith</p>
        </div>
    </section>
    <?php include'footer.php';?>
   
