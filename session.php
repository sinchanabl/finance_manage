<?php
session_start(); // Start the session

// Debugging line: Check if the session email is set
if (!isset($_SESSION["email"])) {
    echo "Session email is not set!";  // Debugging message
    header("Location: login.php");
    exit();
}

// Database connection
$con = new mysqli("localhost", "root", "", "finance_manage");

// Check for connection errors
if ($con->connect_errno) {
    die("Failed to connect to MySQL: " . $con->connect_error . " | Please ensure the database exists and the credentials are correct.");
}

// Get the email from the session and fetch user data from the database
$sess_email = $_SESSION["email"];

// Prepare the SQL statement to prevent SQL injection
$sql = "SELECT user_id, first_name, last_name, email FROM users WHERE email = ?";
if ($stmt = $con->prepare($sql)) {
    // Bind the parameters to the SQL query
    $stmt->bind_param("s", $sess_email); // "s" stands for string (email)
    
    // Execute the query
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();
    
    // If the user exists, retrieve their details
    if ($result->num_rows > 0) {
        // Fetch user data
        $row = $result->fetch_assoc();
        $userid = $row["user_id"];
        $firstname = $row["first_name"];
        $lastname = $row["last_name"];
        $username = $firstname . " " . $lastname;
        $useremail = $row["email"];
    } else {
        // User not found, set default values or display an error message
        $userid = "798"; // Default or error value
        $username = "SJEC"; // Default name or error value
        $useremail = "mailid@domain.com"; // Default email or error value
        echo "User not found. Please check your email or contact support.";
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // If preparing the statement fails
    die("Error preparing statement: " . $con->error);
}

// Close the database connection
$con->close();
?>
