<?php
// Start session to get the user_id
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Get the product_id from the POST request
$product_id = $_POST['product_id'];
$user_id = $_SESSION['user_id'];

// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

// Prepare and execute the query to remove the item from the cart
$sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $product_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to remove item from cart']);
}

$stmt->close();
$conn->close();
?>
