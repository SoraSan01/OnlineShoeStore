<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

// Get the POST data
$id = $_POST['id'];
$name = $_POST['name'];
$updated_at = date('Y-m-d H:i:s'); // Assuming you want to update the timestamp to the current time

// Prepare the query
$query = "UPDATE brands SET name = ?, updated_at = ? WHERE id = ?";
$stmt = $conn->prepare($query);

// Bind the parameters
$stmt->bind_param("ssi", $name, $updated_at, $id); // 'ssi' means string, string, integer

// Execute the query
$stmt->execute();

// Check if the update was successful
if ($stmt->affected_rows > 0) {
    echo "Brand updated successfully.";
} else {
    echo "Failed to update brand.";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
