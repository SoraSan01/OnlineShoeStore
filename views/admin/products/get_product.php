<?php
// Fetch product details by ID
include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

$id = $_GET['id'];
$query = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    echo json_encode(['Result' => 'OK', 'Product' => $product]);
} else {
    echo json_encode(['Result' => 'ERROR', 'Message' => 'Product not found']);
}
?>
