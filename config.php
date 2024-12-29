<?php
// Establish a secure connection to the MySQL database
$con = new mysqli("localhost", "root", "", "finance_manage");

// Check for connection errors
if ($con->connect_errno) {
    echo "Failed to connect to MySQL: " . $con->connect_error . " | Please ensure the database exists and the credentials are correct.";
    exit();
}

// Set the character set to utf8mb4 for proper Unicode support
$con->set_charset("utf8mb4");

// Start the session and retrieve the user information
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userid = $_SESSION['user_id']; // Assuming user_id is stored in the session

// Check if the account_id is needed or if it's predefined for this user
$account_id = 1; // You can change this if you have a specific logic for the account_id or fetch it from the database

// Get user information (like username and email) using a prepared statement for better security
$sql_user = "SELECT username, email FROM users WHERE user_id = ?";
if ($stmt = $con->prepare($sql_user)) {
    // Bind the parameters to the SQL query
    $stmt->bind_param("i", $userid); // "i" means integer (user_id is assumed to be an integer)
    
    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    // Check if a user was found
    if ($result->num_rows == 1) {
        // Fetch user data
        $user = $result->fetch_assoc();
        $username = $user['username'];
        $useremail = $user['email'];
    } else {
        echo "User not found.";
        exit();
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Error preparing the query: " . $con->error;
    exit();
}

// Additional configuration settings can be added here if needed, such as timezone or transaction settings
?>
