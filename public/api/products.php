<?php
include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

// Fetch products from the database
$sql = "SELECT name, price, image FROM products";
$result = $conn->query($sql);

if (!$result) {
    die("Error in query: " . $conn->error);
}

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
} else {
    echo "No products found.";
}

$conn->close();
?>
