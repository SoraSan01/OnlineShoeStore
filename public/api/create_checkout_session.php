<?php
require __DIR__ . '/../../vendor/autoload.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in.']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Decode the input data
$data = json_decode(file_get_contents('php://input'), true);

// Validate line_items
if (!isset($data['line_items']) || empty($data['line_items'])) {
    echo json_encode(['error' => 'The line_items parameter is required.']);
    exit();
}

$line_items = $data['line_items'];

// Check if session variables for user details are set
$required_fields = ['fullname', 'email', 'address', 'phone_number'];
$missing_fields = [];

foreach ($required_fields as $field) {
    if (!isset($_SESSION[$field]) || empty($_SESSION[$field])) {
        $missing_fields[] = $field;
    }
}

if (!empty($missing_fields)) {
    echo json_encode(['error' => 'Please update your profile. Missing information: ' . implode(", ", $missing_fields)]);
    exit();
}

// Set your Stripe secret key
\Stripe\Stripe::setApiKey('sk_test_51QpWKWG0lY8F40k3LfzfoC9qu8BVgP2RTJcrDamU0RfSOOeiKqmJFiJ5FRSMDFL5Cxnxe9fPHRPB0cG3lr3Sm6eQ00HCjacFd0');

try {
    // Create the order and get the order ID
    $orderid = createOrder($user_id);
    $_SESSION['order_id'] = $orderid;

    // Create the Stripe Checkout session
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $line_items,
        'mode' => 'payment',
        'success_url' => 'http://localhost/Projects/OnlineShoeStore/public/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhost/Projects/OnlineShoeStore/public/cancel.php',
        'metadata' => [
            'user_id' => $user_id,
            'order_id' => $orderid,
        ],
    ]);

    echo json_encode(['success' => true, 'session_url' => $session->url]);
} catch (\Stripe\Exception\ApiErrorException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

// Function to create an order
function createOrder($user_id) {
    include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

    $fullname = $_SESSION['fullname'];
    $email = $_SESSION['email'];
    $address = $_SESSION['address'];
    $phone_number = $_SESSION['phone_number'];

    $orderid = uniqid('order_', true);

    $insert_order_query = "INSERT INTO orders (orderid, user_id, name, email, address, phone_number) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_order_query);

    if (!$stmt) {
        echo json_encode(['error' => 'Prepare failed: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param('sissss', $orderid, $user_id, $fullname, $email, $address, $phone_number);

    if (!$stmt->execute()) {
        echo json_encode(['error' => 'Failed to save order details: ' . $stmt->error]);
        exit();
    }

    $stmt->close();
    return $orderid;
}
