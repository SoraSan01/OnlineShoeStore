<?php
// Update product details
include_once $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

$id = $_POST['id'];
$name = $_POST['name'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$description = $_POST['description'];
$image = $_FILES['image']['name'];

// Prepare the base query
$query = "UPDATE products SET name = ?, price = ?, stock = ?, description = ?";

// Check if a new image is uploaded
if (!empty($image)) {
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/uploads/";
    $targetFile = $targetDir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
    $image = basename($image);
    $query .= ", image = ?"; // Add image to the query
}

// Complete the query
$query .= " WHERE id = ?";
$stmt = $conn->prepare($query);

// Bind parameters
if (!empty($image)) {
    $stmt->bind_param("sdisss", $name, $price, $stock, $description, $image, $id);
} else {
    $stmt->bind_param("sdiss", $name, $price, $stock, $description, $id); // Exclude image
}

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(['Result' => 'OK']);
} else {
    echo json_encode(['Result' => 'ERROR', 'Message' => 'Failed to update product: ' . $stmt->error]);
}
?>