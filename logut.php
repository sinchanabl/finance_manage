<?php
session_start();

// Destroy the session to log the user out
if(session_destroy()) {
    // Redirect to the login page after session is destroyed
    header("Location: login.php");
    exit(); // Ensure no further code is executed
}
?>
