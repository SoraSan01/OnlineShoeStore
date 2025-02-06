<?php
header("Content-Type: application/json");

try {
    // Create a PDO instance with better error handling
    $pdo = new PDO("mysql:host=localhost;dbname=shoe_store;charset=utf8", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Query to fetch product data
    $query = "SELECT * FROM products";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Fetch all rows as an associative array
    $rows = $stmt->fetchAll();

    if ($rows) {
        echo json_encode([
            'Result' => 'OK',
            'Records' => $rows
        ]);
    } else {
        echo json_encode([
            'Result' => 'OK',
            'Records' => [],
            'Message' => 'No products found.'
        ]);
    }

} catch (PDOException $e) {
    // Log error message on the server
    error_log("Database connection failed: " . $e->getMessage());
    echo json_encode([
        'Result' => 'ERROR',
        'Message' => 'Database connection failed: ' . $e->getMessage()  // Display a user-friendly error message
    ]);
}
?>