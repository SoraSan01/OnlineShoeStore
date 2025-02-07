<?php
require __DIR__ . '/../../vendor/autoload.php';

// Set your Stripe secret key
\Stripe\Stripe::setApiKey('sk_test_51QpWKWG0lY8F40k3LfzfoC9qu8BVgP2RTJcrDamU0RfSOOeiKqmJFiJ5FRSMDFL5Cxnxe9fPHRPB0cG3lr3Sm6eQ00HCjacFd0');

// Retrieve the request's body and parse it as JSON
$payload = @file_get_contents('php://input');
$event = null;

try {
    $event = \Stripe\Event::constructFrom(json_decode($payload, true));
} catch (\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->type) {
    case 'checkout.session.completed':
        $session = $event->data->object;

        // Extract necessary details from the session
        $user_id = $session->metadata->user_id; // Pass user_id in metadata during session creation
        $total_amount = $session->amount_total / 100; // Convert from cents to dollars
        $line_items = \Stripe\Checkout\Session::allLineItems($session->id, ['limit' => 100]);

        // Save order details to the database
        saveOrderToDatabase($user_id, $total_amount, $line_items);
        break;

    default:
        // Unexpected event type
        http_response_code(400);
        exit();
}

http_response_code(200);

// Function to save order details to the database
function saveOrderToDatabase($user_id, $total_amount, $line_items) {
    include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

    // Generate a unique order ID
    $order_id = uniqid('order_', true);
    $order_date = date('Y-m-d H:i:s');

    // Begin database transaction
    $conn->begin_transaction();

    try {
        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (order_id, user_id, order_date, total_amount) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $order_id, $user_id, $order_date, $total_amount);
        $stmt->execute();

        // Insert into order_items table
        foreach ($line_items->data as $item) {
            $product_name = $item->price->product_data['name'];
            $quantity = $item->quantity;
            $price = $item->price->unit_amount / 100;

            // Fetch the product ID
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

        // Commit the transaction
        $conn->commit();
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        error_log("Error saving order: " . $e->getMessage());
    }

    $stmt->close();
    $conn->close();
}
?>