<?php
// Include database connection file and user management functions
include_once 'connect.php';
include_once 'user_management.php'; // Contains addUser function

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form data
    $username = $_POST['UserName'] ?? '';
    $email = $_POST['Email'] ?? '';
    $password = $_POST['Password'] ?? '';
    $userType = $_POST['UserType'] ?? '';

    if (!empty($username) && !empty($email) && !empty($password) && !empty($userType)) {
        // Call addUser function
        $result = addUser($username, $email, $password, $userType);

        if ($result) {
            // User added successfully
            header("Location: admin_dashboard.php"); // Redirect to dashboard or success page
            exit();
        } else {
            $errorMessage = "Failed to add user. Please try again.";
        }
    } else {
        $errorMessage = "All form fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="add-user-container">
        <h2>Add New User</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="UserName" placeholder="Username" required>
            <input type="email" name="Email" placeholder="Email" required>
            <input type="password" name="Password" placeholder="Password" required>
            <select name="UserType" required>
                <option value="">Select User Type</option>
                <option value="admin">Admin</option>
                <option value="client">Client</option>
            </select>
            <button type="submit">Add User</button>
            <?php if (isset($errorMessage)) : ?>
                <div class="error"><?php echo $errorMessage; ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
