<?php
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

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the brand name from the POST request
    $brandName = trim($_POST['name']);

    // Validate the brand name
    if (empty($brandName)) {
        echo json_encode(['Result' => 'ERROR', 'Message' => 'Brand name is required.']);
        exit;
    }

    // Prepare the SQL statement
    $stmt = $pdo->prepare("INSERT INTO brands (name) VALUES (:name)");

    // Bind the parameters
    $stmt->bindParam(':name', $brandName);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['Result' => 'OK', 'Message' => 'Brand added successfully!']);
    } else {
        echo json_encode(['Result' => 'ERROR', 'Message' => 'Failed to add brand.']);
    }
} else {
    // If the request method is not POST
    echo json_encode(['Result' => 'ERROR', 'Message' => 'Invalid request method.']);
}
?>