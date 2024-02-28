<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header with Logo and Login Button</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <div class="header_container">
            <div class="logo">
                <img src="assets/logo1.png" alt="Logo">
            </div>
            <div class="login">
                <?php
                if (isset($_SESSION['user_name'])) {
                    echo '<a href="logout.php" class="login-btn">Logout</a>';
                } else {
                    echo '<a href="Login.php" class="login-btn">Login</a>';
                }
                ?>
            </div>
        </div>
    </header>
</body>
</html>
