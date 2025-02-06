<?php
header("Content-Type: application/json");

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

// Check if request method is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["Result" => "ERROR", "Message" => "Invalid request method."]);
    exit;
}

// Retrieve and validate form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
$stock = isset($_POST['stock']) ? intval($_POST['stock']) : 0;
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$image = $_FILES['image'] ?? null;

// Validate required fields
if (empty($name) || $price <= 0 || $stock < 0 || empty($description)) {
    echo json_encode(["Result" => "ERROR", "Message" => "Invalid input data."]);
    exit;
}

// Validate image upload (check if file was uploaded)
if ($image && $image['error'] === 0) {
    // Allowed file types and max size
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    // Check file type
    if (!in_array($image['type'], $allowedTypes)) {
        echo json_encode(["Result" => "ERROR", "Message" => "Invalid image format. Only JPG, PNG, and GIF are allowed."]);
        exit;
    }

    // Check file size
    if ($image['size'] > $maxSize) {
        echo json_encode(["Result" => "ERROR", "Message" => "Image size exceeds 5MB."]);
        exit;
    }

    // Set the correct upload directory path
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Projects/OnlineShoeStore/uploads/'; // Ensure this directory exists

    // Ensure the directory exists, create if not
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Creates the directory if it doesn't exist
    }

    // Generate a unique file name and move the uploaded file
    $imagePath = uniqid('product_', true) . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
    $fullImagePath = $uploadDir . $imagePath;

    if (!move_uploaded_file($image['tmp_name'], $fullImagePath)) {
        echo json_encode(["Result" => "ERROR", "Message" => "Failed to upload image."]);
        exit;
    }
} else {
    $imagePath = null; // No image uploaded
}

// Prepare and execute query
try {
    $stmt = $pdo->prepare("INSERT INTO products (name, price, stock, description, image) VALUES (:name, :price, :stock, :description, :image)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':stock', $stock);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':image', $imagePath); // Save the image path in the database
    $stmt->execute();

    echo json_encode(["Result" => "OK", "Message" => "Product added successfully."]);
} catch (PDOException $e) {
    echo json_encode(["Result" => "ERROR", "Message" => "Database error: " . $e->getMessage()]);
}
?>