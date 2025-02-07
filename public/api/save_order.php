<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'User  not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents("php://input"), true);
$line_items = $data['line_items'];

$order_id = uniqid('order_', true); // Generate a unique order ID
$order_date = date('Y-m-d H:i:s');
$total_amount = array_reduce($line_items, function($carry, $item) {
    return $carry + ($item['price_data']['unit_amount'] / 100) * $item['quantity'];
}, 0);

$conn->begin_transaction();

try {
    // Insert into orders
    $stmt = $conn->prepare("INSERT INTO orders (order_id, user_id, order_date, total_amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $order_id, $user_id, $order_date, $total_amount);
    $stmt->execute();

    // Check if the order was created successfully
    if ($stmt->affected_rows === 0) {
        throw new Exception('Failed to create order.');
    }

    // Log the order_id for debugging
    error_log("Order ID created: " . $order_id);

    // Insert into order_items
    foreach ($line_items as $item) {
        $product_name = $item['price_data']['product_data']['name'];
        $quantity = $item['quantity'];
        $price = $item['price_data']['unit_amount'] / 100;

        // Fetch the product ID based on the product name or other criteria
        $stmt = $conn->prepare("SELECT id FROM products WHERE name = ?");
        $stmt->bind_param("s", $product_name);
        $stmt->execute();
        $stmt->bind_result($product_id);
        $stmt->fetch();
        $stmt->close();

        if ($product_id) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssd", $order_id, $product_id, $product_name, $quantity, $price);
            $stmt->execute();
        } else {
            throw new Exception('Product not found: ' . $product_name);
        }
    }

    $conn->commit();
    echo json_encode(['success' => true, 'order_id' => $order_id]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>