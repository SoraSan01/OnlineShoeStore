<?php
// Include database connection
require_once $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

// Check if ID is provided
if (isset($_GET['id'])) {
    $categoryId = $_GET['id'];

    // Prepare SQL statement to delete the category
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $categoryId);

    // Execute the query
    if ($stmt->execute()) {
        echo "Category deleted successfully.";
    } else {
        echo "Error deleting category: " . $stmt->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo "Category ID is required.";
}
?>
