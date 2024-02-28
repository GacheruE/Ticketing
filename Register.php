<?php
// Include database connection file
include_once 'connect.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare SQL statement to insert user data
    $stmt = $conn->prepare("INSERT INTO user (UserName, Email, Password, UserType) VALUES (?, ?, ?, 'client')");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Registration successful
        $registration_success = true;
    } else {
        // Registration failed
        $registration_success = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="styles.css">
    <?php include'header.php';?>
</head>
<body>
    <div class="registration-container">
        <h2>Registration</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
            <?php if (isset($registration_success) && $registration_success) : ?>
                <p class="success-message">Registration successful. You can now login.</p>
            <?php elseif (isset($registration_success) && !$registration_success) : ?>
                <p class="error-message">Registration failed. Please try again.</p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>