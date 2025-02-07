<?php
// Include database connection
try {
    // Establish database connection
    $pdo = new PDO("mysql:host=localhost;dbname=shoe_store;charset=utf8", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    echo json_encode(["Result" => "ERROR", "Message" => "Database connection failed: " . $e->getMessage()]);
    exit;
}
header('Content-Type: application/json');

try {
    // Ensure $pdo is defined
    if (!isset($pdo)) {
        throw new Exception("Database connection is missing.");
    }

    // Validate HTTP request method
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo json_encode(["Result" => "ERROR", "Message" => "Invalid request method."]);
        exit;
    }

    // Validate POST parameters
    $categoryName = isset($_POST['name']) ? trim($_POST['name']) : "";
    $categoryDescription = isset($_POST['description']) ? trim($_POST['description']) : "";

    if (empty($categoryName)) {
        echo json_encode(["Result" => "ERROR", "Message" => "Category name is required."]);
        exit;
    }

    // Check if category already exists
    $stmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
    $stmt->execute([$categoryName]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["Result" => "ERROR", "Message" => "Category already exists."]);
        exit;
    }

    // Insert new category
    $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (:name, :description)");
    $stmt->bindParam(':name', $categoryName);
    $stmt->bindParam(':description', $categoryDescription);
    
    if ($stmt->execute()) {
        echo json_encode(["Result" => "OK", "Message" => "Category added successfully!"]);
    } else {
        echo json_encode(["Result" => "ERROR", "Message" => "Failed to add category."]);
    }
} catch (Exception $e) {
    echo json_encode(["Result" => "ERROR", "Message" => "Error: " . $e->getMessage()]);
}
?>
