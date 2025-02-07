<?php
// Include database connection
require_once $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

// Check if data is received from the form
if (isset($_POST['id'], $_POST['name'], $_POST['description'])) {
    $categoryId = $_POST['id'];
    $categoryName = $_POST['name'];
    $categoryDescription = $_POST['description'];

    // Prepare SQL statement to update the category
    $stmt = $conn->prepare("UPDATE categories SET name = ?, description = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("ssi", $categoryName, $categoryDescription, $categoryId);

    // Execute the query
    if ($stmt->execute()) {
        echo "Category updated successfully.";
    } else {
        echo "Error updating category: " . $stmt->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo "All fields are required.";
}
?>
