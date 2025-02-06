<?php
// Start session to manage user login status
session_start();

// Database connection
include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']); // Assuming you store user ID in session when logged in

// SQL query to fetch products
$sql = "SELECT * FROM products"; // Assuming the table name is 'products'
$result = $conn->query($sql);

// Function to handle adding product to the cart
function addToCart($productId) {
    global $conn; // To access the database connection

    // Check if user is logged in
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        
        // Sanitize the product ID to prevent SQL injection
        $productId = (int)$productId;
        
        // Check if the product is already in the cart
        $sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo "<script>alert('Error preparing query.');</script>";
            return;
        }
        
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Product is already in the cart, you can update the quantity
            $row = $result->fetch_assoc();
            $newQuantity = $row['quantity'] + 1; // Increment quantity

            // Update quantity if product already in cart
            $updateSql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("iii", $newQuantity, $userId, $productId);
            $updateStmt->execute();

            if ($updateStmt->affected_rows > 0) {
                echo "<script>showToast('Product quantity updated in your cart!');</script>";
            } else {
                echo "<script>showToast('Failed to update cart.');</script>";
            }
        } else {
            // Product not in cart, so insert a new entry
            $insertSql = "INSERT INTO cart (user_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);

            // Get the product price using the product ID
            $price = getProductPrice($productId, $conn); // Assume this function fetches the price based on product ID
            if (!$price) {
                echo "<script>showToast('Error fetching product price.');</script>";
                return;
            }

            $quantity = 1; // Initialize quantity variable

            $insertStmt->bind_param("iiid", $userId, $productId, $quantity, $price); // Bind price as well
            $insertStmt->execute();

            if ($insertStmt->affected_rows > 0) {
                echo "<script>showToast('Product added to cart!');</script>";
            } else {
                echo "<script>showToast('Failed to add product to cart.');</script>";
            }
        }

        // Redirect to the cart page after adding the product
        echo "<script>setTimeout(function() { window.location.href = '/Projects/OnlineShoeStore/public/cart.php'; });</script>";
    } else {
        // Redirect to login page if not logged in
        echo "<script>window.location.href = '/Projects/OnlineShoeStore/views/Account/login.php';</script>";
    }
}

function getProductPrice($productId, $conn) {
    $sql = "SELECT price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['price']; // Return the price
    } else {
        return false; // If product not found
    }
}

// Handle Add to Cart button click (via GET)
if (isset($_GET['add_to_cart'])) {
    $productId = (int) $_GET['add_to_cart'];  // Cast the product ID to an integer
    addToCart($productId);
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Shoe Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: "Roboto", sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <?php include "includes/navbar.php"; ?>

    <!-- Item Section -->
    <div class="container mx-auto mt-10">
        <h2 class="text-center text-3xl font-bold mb-8">Browse Our Shoes</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
            <?php
            // Check if there are products in the database
            if ($result->num_rows > 0) {
                // Loop through the products and display each one
                while($row = $result->fetch_assoc()) {
                    // Adjust the image URL to be full path if it's relative
                    $image_url = (filter_var($row['image'], FILTER_VALIDATE_URL)) ? $row['image'] : "/Projects/OnlineShoeStore/uploads/" . $row['image'];
                    
                    echo '
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center transform transition-transform hover:scale-105">
                        <img alt="' . htmlspecialchars($row['description'], ENT_QUOTES) . '" class="w-full h-auto rounded-lg mb-4" height="100" src="' . $image_url . '" width="100"/>
                        <p class="text-gray-700 mb-4">₱' . number_format($row['price'], 2) . '</p>
                        <h5 class="text-xl font-semibold mb-2">' . htmlspecialchars($row['name'], ENT_QUOTES) . '</h5>
                        <a href="?add_to_cart=' . $row['id'] . '" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-all">Add to Cart</a>
                    </div>';
                }
            } else {
                echo "<p>No products available at the moment.</p>";
            }
            ?>
        </div>
    </div>

    <script>
        // Show Toast notification
        function showToast(message) {
            let toast = document.createElement('div');
            toast.classList.add('toast', 'show');
            toast.innerText = message;
            document.body.appendChild(toast);

            // Hide the toast after 3 seconds
            setTimeout(function() {
                toast.classList.remove('show');
                setTimeout(function() {
                    toast.remove();
                }, 500); // Wait for animation to finish
            }, 3000);
        }
    </script>
</body>
</html>
