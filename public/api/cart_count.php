<?php
// Include database connection file
include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

// Check if the user is logged in (you can implement your own authentication check)
session_start();
$user_id = $_SESSION['user_id'];  // Assuming the user ID is stored in the session

if ($user_id) {
    // Query to get the cart count for the logged-in user
    $query = "SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);  // Bind the user_id
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch the result
    $row = $result->fetch_assoc();
    $cart_count = $row['total_items'] ? $row['total_items'] : 0;  // Default to 0 if no items

    echo $cart_count;  // Output the cart count
} else {
    echo 0;  // If the user is not logged in, return 0
}
?>
