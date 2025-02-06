<?php
require __DIR__ . '/../../vendor/autoload.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit();
}

// Fetch the user ID from session
$user_id = $_SESSION['user_id'];

// Include the database connection
include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

// Get the line items from the request body
$data = json_decode(file_get_contents('php://input'), true);
$line_items = $data['line_items'];  // Extract line items from request

// Create Stripe Checkout session
\Stripe\Stripe::setApiKey('sk_test_51QpWKWG0lY8F40k3LfzfoC9qu8BVgP2RTJcrDamU0RfSOOeiKqmJFiJ5FRSMDFL5Cxnxe9fPHRPB0cG3lr3Sm6eQ00HCjacFd0');

try {
    // Create a checkout session
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        'success_url' => 'http://localhost/Projects/OnlineShoeStore/public/success.php',
        'cancel_url' => 'http://localhost/Projects/OnlineShoeStore/public/cancel.php',
    ]);

    // Remove cart items from the database after successful checkout
    $product_ids = array_map(function ($item) {
        return $item['price_data']['product_data']['name']; // Use the product name or ID
    }, $line_items);

    // Create a query to delete cart items from the database for the logged-in user
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ? AND product_id IN ($placeholders)");
    $stmt->bind_param('i' . str_repeat('i', count($product_ids)), $user_id, ...$product_ids);

    if ($stmt->execute()) {
        // Successfully removed items from cart
        echo json_encode(['id' => $session->id]);
    } else {
        echo json_encode(['error' => 'Failed to remove items from cart.']);
    }

} catch (\Stripe\Exception\ApiErrorException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
