<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["Result" => "ERROR", "Message" => "Invalid request method."]);
    exit;
}

// Ensure the ID is provided
if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode(["Result" => "ERROR", "Message" => "Missing product ID."]);
    exit;
}

include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

$product_id = $_POST['id'];
$query = "DELETE FROM products WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);

if ($stmt->execute()) {
    echo json_encode(["Result" => "OK", "Message" => "Product deleted successfully."]);
} else {
    echo json_encode(["Result" => "ERROR", "Message" => "Failed to delete product."]);
}

$stmt->close();
$conn->close();
?>
