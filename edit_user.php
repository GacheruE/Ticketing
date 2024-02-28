<?php
// Include user management functions
include_once 'user_management.php';

// Check if user ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Get the user ID from the URL
    $userId = $_GET['id'];

    // Fetch user details by ID
    $user = getUserById($userId);

    if (!$user) {
        echo "Error: User not found.";
        exit();
    }

    // Initialize variables to store user details
    $username = $user['UserName'];
    $email = $user['Email'];
    $userType = $user['UserType'];

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $userType = $_POST['userType'];

        // Update user details
        if (editUser($userId, $username, $email, $password, $userType)) {
            // User details updated successfully
            header("Location: admin_dashboard.php");
            exit();
        } else {
            // Error occurred while updating user details
            echo "Error: Failed to update user details.";
        }
    }
} else {
    // User ID is not provided or empty in the URL
    echo "Error: User ID is missing.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="edit-user-container">
        <h2>Edit User</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $userId; ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username; ?>" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter new password">
            </div>
            <div class="form-group">
                <label>User Type</label>
                <select name="userType" required>
                    <option value="admin" <?php if ($userType === 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="user" <?php if ($userType === 'user') echo 'selected'; ?>>User</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit">Update User</button>
            </div>
        </form>
    </div>
</body>
</html>
