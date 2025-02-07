<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If user is not logged in, return an empty response
if (!isset($_SESSION['user_id'])) {
    echo ''; 
    exit;
}

// Include database connection file
include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

$user_id = $_SESSION['user_id']; 

$query = "SELECT SUM(quantity) AS total_items FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$row = $result->fetch_assoc();
$cart_count = $row['total_items'] ? $row['total_items'] : 0;

echo $cart_count;
?>
