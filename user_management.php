<?php
// Include database connection file
include_once 'connect.php';

// Function to add a new user
function addUser($username, $email, $password, $userType) {
    global $conn;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO user (UserName, Email, Password, UserType) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $userType);
    return $stmt->execute();
}

// Function to edit user details
function editUser($id, $username, $email, $password, $userType) {
    global $conn;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE user SET UserName = ?, Email = ?, Password = ?, UserType = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $username, $email, $hashed_password, $userType, $id);
    return $stmt->execute();
}

// Function to get user details by ID
function getUserById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


// Function to delete a user
function deleteUser($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Function to get total number of users
function getTotalUsers() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) FROM user");
    return $result->fetch_assoc()['COUNT(*)'];
}

// Function to get users from the database
function getUsers() {
    global $conn;
    $users = array(); // Initialize an empty array to store users
    $result = $conn->query("SELECT * FROM user");

    // Check if query was successful
    if ($result) {
        // Fetch each row from the result set and store it in the users array
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    } else {
        // Handle error if query fails
        echo "Error: " . $conn->error;
    }

    return $users;
}
?>

