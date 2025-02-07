<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Shoe Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    
    <!-- Add Toastify.js library for toast notifications -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <style>
        body { font-family: 'Roboto', sans-serif; }
        .card { cursor: pointer; transition: transform 0.3s, box-shadow 0.3s; }
        .card.selected { transform: scale(1.05); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); border: 2px solid #007bff; }
        .item-card { padding: 20px; overflow: auto; max-height: 100vh; height: 70vh; }
        .cart-footer { position: fixed; bottom: 0; left: 0; right: 0; background-color: #fff; padding: 10px 20px; box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1); }
        .quantity-btn { background-color: #ddd; padding: 5px 10px; border: none; cursor: pointer; }
    </style>
</head>
<body class="bg-gray-100">
<?php include "includes/navbar.php"; ?>

<?php
// Start the session to track user
// Start the session to track user
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: /Projects/OnlineShoeStore/views/account/login.php');
    exit();
}

// Database connection
include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

// Error handling
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user_id from the session (assuming you store user_id in the session after login)
$user_id = $_SESSION['user_id'];

// Fetch cart items for the logged-in user (modify the query to include user_id)
$sql = "SELECT p.name, p.image, p.price, c.quantity, c.product_id 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = $user_id";  // Make sure to filter by user_id
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    echo "Error: " . $conn->error;
}
?>

<main class="container mx-auto px-4 py-8">
    <div class="item-card bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Your Pre-Loved Shoes</h2>
        <form id="cart-form">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Extract values for product details
                        $name = htmlspecialchars($row['name']);
                        $imageUrl = "/Projects/OnlineShoeStore/uploads/" . htmlspecialchars($row['image']);
                        $price = htmlspecialchars($row['price']);
                        $quantity = htmlspecialchars($row['quantity']);
                        
                        echo '<div class="card bg-gray-100 p-4 rounded-lg shadow-md hover:shadow-lg transition duration-300" data-price="' . $price . '" data-value="' . $name . '" data-id="' . $row['product_id'] . '" onclick="toggleSelection(this)">';
                        echo '<label class="text-lg font-semibold text-gray-800">' . $name . '</label>';
                        echo '<img alt="Image of ' . $name . '" class="w-full h-48 object-cover rounded-lg mb-4" height="200" src="' . $imageUrl . '" width="300"/>';
                        echo '<p class="text-gray-600">Price: ₱' . $price . '</p>';
                        echo '<div class="flex justify-between items-center mt-4">';
                        echo '<button type="button" class="quantity-btn" onclick="changeQuantity(event, this, \'decrease\')">-</button>';
                        echo '<span id="quantity-' . $row['product_id'] . '">' . $quantity . '</span>';
                        echo '<button type="button" class="quantity-btn" onclick="changeQuantity(event, this, \'increase\')">+</button>';
                        echo '<button type="button" class="text-red-600 hover:text-red-800" onclick="removeItem(event, this)" aria-label="Remove ' . $name . ' from cart"><i class="fas fa-trash"></i> Remove</button>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="text-gray-600">No items in your cart.</p>';
                }
                ?>
            </div>
        </form>
    </div>
</main>

<!-- Cart Footer -->
<div class="cart-footer flex justify-between items-center">
    <div>
        <p class="text-lg text-gray-800" id="selected-items">Selected Items: 0</p>
        <p class="text-xl font-semibold text-blue-600" id="total-price">₱0.00</p>
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700" id="checkout-btn">Checkout</button>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
// Toggle selection for the cart items
function toggleSelection(element) {
    element.classList.toggle('selected');
    updateCartDetails();
}

function changeQuantity(event, button, action) {
    event.stopPropagation();
    const card = button.closest('.card');
    const productId = card.getAttribute('data-id');
    let quantityElement = document.getElementById('quantity-' + productId);
    let quantity = parseInt(quantityElement.innerText);

    if (action === 'increase') {
        quantity++;
    } else if (action === 'decrease') {
        if (quantity > 1) {
            quantity--;
        } else {
            showToastNotification('⚠️ Minimum quantity is 1.', 'warning');
            return;
        }
    }

    // Update quantity in UI
    quantityElement.innerText = quantity;

    // Update quantity in database
    updateQuantityInDatabase(productId, quantity);
    updateCartDetails();
}

function updateQuantityInDatabase(productId, quantity) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/Projects/OnlineShoeStore/public/api/update_quantity.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.send(`product_id=${productId}&quantity=${quantity}`);

    xhr.onload = function () {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
            console.log("Quantity updated successfully.");
        } else {
            showToastNotification("⚠️ Error updating quantity!", "error");
        }
    };
}

// Remove item from cart with event prevention
function removeItem(event, button) {
    event.stopPropagation();
    if (!confirm("Are you sure you want to remove this item from your cart?")) {
        return;
    }

    const card = button.closest('.card');
    const productId = card.getAttribute('data-id');

    removeItemFromDatabase(productId);
    card.remove();
    
    updateCartDetails();
    showToastNotification('🗑️ Item removed from your cart.', 'success');
}

// Function to remove item from the database
function removeItemFromDatabase(productId) {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/Projects/OnlineShoeStore/public/api/remove_item.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.send(`product_id=${productId}`);

    xhr.onload = function () {
        const response = JSON.parse(xhr.responseText);
        if (!response.success) {
            showToastNotification("⚠️ Error removing item!", "error");
        }
    };
}

// Update cart details
function updateCartDetails() {
    const selectedItems = document.querySelectorAll('.card.selected').length;
    const totalPrice = Array.from(document.querySelectorAll('.card.selected')).reduce((total, card) => {
        const quantity = parseInt(document.getElementById('quantity-' + card.getAttribute('data-id')).innerText);
        return total + (parseFloat(card.getAttribute('data-price')) * quantity);
    }, 0);

    document.getElementById('selected-items').innerText = `Selected Items: ${selectedItems}`;
    document.getElementById('total-price').innerText = `₱${totalPrice.toFixed(2)}`;
}

// Checkout with confirmation
document.getElementById("checkout-btn").addEventListener("click", function () {
    const selectedItems = document.querySelectorAll('.card.selected').length;
    if (selectedItems === 0) {
        showToastNotification("⚠️ Please select at least one item before checkout!", "warning");
        return;
    }

    if (confirm("Proceed to checkout?")) {
        showToastNotification("🛒 Redirecting to checkout...", "info");
        setTimeout(() => {
            window.location.href = "/Projects/OnlineShoeStore/views/checkout.php";
        }, 1500);
    }
});

// Function to show error notifications with Toastify
function showToastNotification(message, type) {
    let bgColor;
    if (type === "success") {
        bgColor = "linear-gradient(to right, #00b09b, #96c93d)";
    } else if (type === "error") {
        bgColor = "linear-gradient(to right, #ff416c, #ff4b2b)";
    } else if (type === "warning") {
        bgColor = "linear-gradient(to right, #ff9f00, #ff6f00)";
    } else {
        bgColor = "#333";
    }

    Toastify({
        text: message,
        duration: 3000,
        close: true,
        gravity: "top",
        position: "right",
        backgroundColor: bgColor,
    }).showToast();
}
</script>

</body>
</html>