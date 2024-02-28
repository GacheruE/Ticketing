<?php
session_start(); // Start the session

include_once 'connect.php';

$errorMessage = ""; // Initialize error message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve hashed password, user type, and user ID from the database based on the username
    $stmt = $conn->prepare("SELECT id, UserName, Password, UserType FROM user WHERE UserName = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Fetch the hashed password, user type, and user ID
        $row = $result->fetch_assoc();
        $hashedPassword = $row['Password'];
        $userType = $row['UserType'];
        $userId = $row['id'];

        // Verify the hashed password against the input password
        if (password_verify($password, $hashedPassword)) {
            // Passwords match, set session variables and redirect
            $_SESSION['user_name'] = $username;
            $_SESSION['user_type'] = $userType;
            $_SESSION['user_id'] = $userId;
            
            if ($userType === 'client') {
                header("Location: View.php");
                exit();
            } elseif ($userType === 'admin') {
                header("Location: admin_dashboard.php");
                exit();
            }
        } else {
            // Passwords do not match
            $errorMessage = "Invalid username or password.";
        }
    } else {
        // User not found in the database
        $errorMessage = "Invalid username or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <h2>Login</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <!-- Error message display -->
            <?php if (!empty($errorMessage)) : ?>
                <div class="error"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
        </form>
        <p style="color: orange;">Dont have an account?<a href="Register.php">Sign Up</a></p>
    </div>
</body>
</html>
