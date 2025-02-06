<?php
// Start session to get the user ID
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php"; // Include DB connection

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Input validation (to avoid malicious input)
if (!is_numeric($quantity) || $quantity <= 0) {
    echo json_encode(['error' => 'Invalid quantity']);
    exit();
}

// Update the cart quantity
$sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $quantity, $user_id, $product_id);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Quantity updated successfully']);
} else {
    echo json_encode(['error' => 'Failed to update quantity']);
}

$stmt->close();
$conn->close();
?>
