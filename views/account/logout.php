<?php
session_start();  // Start the session

// Function to log out the user
function logout() {
    // Destroy the session
    session_unset();   // Remove all session variables
    session_destroy(); // Destroy the session itself

    // Redirect to the login page
    header("Location: /Projects/OnlineShoeStore/views/account/login.php");
    exit();
}

// Call the logout function
logout();
?>
