<?php
// Include user management functions
include_once 'connect.php';
include_once 'user_management.php';

// Check if user ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Get the user ID from the URL
    $userId = $_GET['id'];

    // Delete the user with the specified ID
    if (deleteUser($userId)) {
        // User deleted successfully, redirect to the admin dashboard or any other desired page
        header("Location: admin_dashboard.php");
        exit();
    } else {
        // Error occurred while deleting the user
        echo "Error: Failed to delete the user.";
    }
} else {
    // User ID is not provided or empty in the URL
    echo "Error: User ID is missing.";
}
?>
