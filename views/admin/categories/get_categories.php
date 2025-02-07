<?php
// Include database connection
include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

header('Content-Type: application/json'); // Ensure the content type is JSON

// Check if an ID is provided
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Get the ID from the query string
    $query = "SELECT * FROM categories WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $brand = $result->fetch_assoc();
        echo json_encode($brand);  // Return the specific brand data as JSON
    } else {
        echo json_encode(['error' => 'Brand not found.']);
    }
} else {
    // Query to get all categories
    $query = "SELECT * FROM categories";
    $result = $conn->query($query);

    if ($result) {
        $brands = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($brands as &$brand) {
            $brand['updated_at'] = date('Y-m-d h:i A', strtotime($brand['updated_at']));
            $brand['created_at'] = date('Y-m-d h:i A', strtotime($brand['created_at']));
        }
        
        echo json_encode($brands);  // Return the brands data as JSON
    } else {
        echo json_encode(['error' => 'Failed to fetch brands']);
    }
}

// Close connection
$conn->close();
?>