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

// Get the line items from the request body
$data = json_decode(file_get_contents('php://input'), true);

// Check if line_items exist in the request body
if (!isset($data['line_items']) || empty($data['line_items'])) {
    echo json_encode(['error' => 'The line_items parameter is required in payment mode.']);
    exit();
}

$line_items = $data['line_items'];  // Extract line items from request

// Create Stripe Checkout session
\Stripe\Stripe::setApiKey('sk_test_51QpWKWG0lY8F40k3LfzfoC9qu8BVgP2RTJcrDamU0RfSOOeiKqmJFiJ5FRSMDFL5Cxnxe9fPHRPB0cG3lr3Sm6eQ00HCjacFd0');

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        'success_url' => 'http://localhost/Projects/OnlineShoeStore/public/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhost/Projects/OnlineShoeStore/public/cancel.php',
        'metadata' => [
            'user_id' => $user_id, // Pass user_id to metadata
        ],
    ]);

    echo json_encode(['success' => true, 'session_url' => $session->url]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>