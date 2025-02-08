<?php
require __DIR__ . '/../vendor/autoload.php';

session_start();

// Check if the session ID is provided
if (!isset($_GET['session_id'])) {
    die("Invalid session ID.");
}

$session_id = $_GET['session_id'];

\Stripe\Stripe::setApiKey('sk_test_51QpWKWG0lY8F40k3LfzfoC9qu8BVgP2RTJcrDamU0RfSOOeiKqmJFiJ5FRSMDFL5Cxnxe9fPHRPB0cG3lr3Sm6eQ00HCjacFd0');

try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);
    $line_items = \Stripe\Checkout\Session::allLineItems($session_id, ['limit' => 100]);

    // Display order confirmation
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Order Receipt</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .receipt-container {
            max-width: 400px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .header h1 {
            font-size: 22px;
            font-weight: bold;
        }
        .divider {
            border-bottom: 1px dashed #aaa;
            margin: 10px 0;
        }
        .order-details {
            text-align: left;
            font-size: 14px;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
        }
        .button {
            display: block;
            width: 100%;
            margin-top: 10px;
            padding: 10px;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
        }
        .print-btn {
            background-color: #007bff;
            color: white;
        }
        .print-btn:hover {
            background-color: #0056b3;
        }
        .download-btn {
            background-color: #28a745;
            color: white;
        }
        .download-btn:hover {
            background-color: #218838;
        }
        .home-btn {
            background-color: #6c757d;
            color: white;
        }
        .home-btn:hover {
            background-color: #5a6268;
        }
        @media print {
            .print-btn, .download-btn, .home-btn {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="text-center">
        <div class="receipt-container" id="receipt">
            <div class="header">
                <h1>Online Shoe Store</h1>
                <p><small>Thank you for your purchase!</small></p>
            </div>
            <div class="divider"></div>
            
            <div class="order-details">
                <?php
                if (isset($session->metadata->order_id)) {
                    echo "<p><strong>Order ID:</strong> " . htmlspecialchars($session->metadata->order_id) . "</p>";
                } else {
                    echo "<p>Error: Order ID not found.</p>";
                }
                ?>
                <p><strong>Transaction Date:</strong> <?php echo date("F j, Y, g:i A"); ?></p>
                <p><strong>Customer:</strong> <?php echo htmlspecialchars($session->customer_details->email ?? "Guest"); ?></p>

                <div class="divider"></div>

                <table class="w-full text-left mt-2">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Qty</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($line_items->data as $item) {
                            $product_name = htmlspecialchars($item->description ?? "Unknown Product");
                            $quantity = htmlspecialchars($item->quantity);
                            $price = number_format($item->amount_total / 100, 2);

                            echo "<tr>";
                            echo "<td>$product_name</td>";
                            echo "<td class='text-center'>$quantity</td>";
                            echo "<td class='text-right'>₱$price</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <div class="divider"></div>
                <p class="total">Total Amount: ₱<?php echo number_format($session->amount_total / 100, 2); ?></p>
            </div>
        </div>

        <!-- Buttons below the receipt -->
        <div class="mt-5">
            <button onclick="printReceipt()" class="button print-btn">🖨️ Print Receipt</button>
            <button onclick="downloadReceipt()" class="button download-btn">📥 Download Receipt</button>
            <a href="/Projects/OnlineShoeStore/public/cart.php" class="button home-btn">🏠 Return to Home</a>
        </div>
    </div>

    <script>
        function printReceipt() {
            let printWindow = window.open("", "", "width=600,height=800");
            let receiptContent = document.getElementById("receipt").outerHTML;
            let styles = document.head.innerHTML;

            printWindow.document.write('<html><head><title>Print Receipt</title>' + styles + '</head><body>');
            printWindow.document.write(receiptContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        }

        function downloadReceipt() {
            let receiptContent = document.getElementById("receipt").outerHTML;
            let styles = document.head.innerHTML;
            
            let fullHTML = `
                <html>
                    <head>
                        <title>Receipt</title>
                        ${styles} 
                    </head>
                    <body>
                        ${receiptContent}
                    </body>
                </html>
            `;

            let blob = new Blob([fullHTML], { type: "text/html" });
            let link = document.createElement("a");
            link.href = URL.createObjectURL(blob);
            link.download = "receipt.html";
            
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>

    <?php
} catch (\Stripe\Exception\ApiErrorException $e) {
    die("Error retrieving session: " . $e->getMessage());
}
?>
