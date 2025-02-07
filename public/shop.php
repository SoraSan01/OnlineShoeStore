<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Fetch brands and categories for the filter
$brandSql = "SELECT * FROM brands";
$categorySql = "SELECT * FROM categories";

$brandResult = $conn->query($brandSql);
$categoryResult = $conn->query($categorySql);

function addToCart($productId) {
    global $conn;
    if (!isset($_SESSION['user_id'])) {
        return json_encode(['success' => false, 'message' => 'You must log in to add items to the cart.']);
    }
    
    $userId = $_SESSION['user_id'];
    $productId = (int)$productId;
    
    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $userId, $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $newQuantity = $row['quantity'] + 1;
        $updateStmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?");
        $updateStmt->bind_param("iii", $newQuantity, $userId, $productId);
        $updateStmt->execute();
        return json_encode(['success' => true, 'message' => 'Product quantity updated in your cart!']);
    } else {
        $price = getProductPrice($productId);
        if (!$price) {
            return json_encode(['success' => false, 'message' => 'Error fetching product price.']);
        }
        
        $insertStmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, price) VALUES (?, ?, 1, ?)");
        $insertStmt->bind_param("iid", $userId, $productId, $price);
        $insertStmt->execute();
        return json_encode(['success' => true, 'message' => 'Product added to cart!']);
    }
}

function getProductPrice($productId) {
    global $conn;
    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    return ($result->num_rows > 0) ? $result->fetch_assoc()['price'] : false;
}

if (isset($_GET['add_to_cart'])) {
    echo addToCart((int)$_GET['add_to_cart']);
    exit;
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>
<body class="bg-gray-100">
    <?php include "includes/navbar.php"; ?>

    <div class="container mx-auto mt-10">
        <!-- Search and Filter Section -->
        <div class="mb-6 flex flex-wrap gap-4 items-center">
            <input type="text" id="searchInput" placeholder="Search products..." class="px-4 py-2 border border-gray-300 rounded w-1/2">
            
            <select id="priceFilter" class="px-4 py-2 border border-gray-300 rounded">
                <option value="">All Prices</option>
                <option value="0-1000">₱0 - ₱1,000</option>
                <option value="1000-3000">₱1,000 - ₱3,000</option>
                <option value="3000-5000">₱3,000 - ₱5,000</option>
                <option value="5000-10000">₱5,000 - ₱10,000</option>
            </select>

            <!-- Brand Filter -->
            <select id="brandFilter" class="px-4 py-2 border border-gray-300 rounded">
                <option value="">All Brands</option>
                <?php while ($brand = $brandResult->fetch_assoc()): ?>
                    <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name'], ENT_QUOTES) ?></option>
                <?php endwhile; ?>
            </select>

            <!-- Category Filter -->
            <select id="categoryFilter" class="px-4 py-2 border border-gray-300 rounded">
                <option value="">All Categories</option>
                <?php while ($category = $categoryResult->fetch_assoc()): ?>
                    <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name'], ENT_QUOTES) ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- Product Grid -->
        <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="product-card bg-white p-6 rounded-lg shadow-lg text-center transform transition-transform hover:scale-105" 
                    data-name="<?= htmlspecialchars(strtolower($row['name'])) ?>" 
                    data-price="<?= $row['price'] ?>" 
                    data-brand-id="<?= $row['brand_id'] ?>" 
                    data-category-id="<?= $row['category_id'] ?>">

                    <img src="<?= htmlspecialchars($row['image'] ? "/Projects/OnlineShoeStore/uploads/" . $row['image'] : '/default-image.jpg') ?>" 
                        alt="<?= htmlspecialchars($row['description'], ENT_QUOTES) ?>" 
                        class="w-full h-48 object-cover rounded-lg mb-4">

                    <h5 class="text-xl font-semibold mb-2"><?= htmlspecialchars($row['name'], ENT_QUOTES) ?></h5>
                    <p class="text-gray-700 mb-4">₱<?= number_format($row['price'], 2) ?></p>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition-all add-to-cart" data-product-id="<?= $row['id'] ?>">Add to Cart</button>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
    // Handle Add to Cart Button
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            fetch("?add_to_cart=" + this.dataset.productId)
                .then(response => response.json())
                .then(data => {
                    showToastNotification(data.message, data.success ? "success" : "warning");

                    // Reload the page after 1.5 seconds if adding to cart is successful
                    if (data.success) {
                        setTimeout(() => {
                            location.reload();
                        }, 1500); 
                    }
                });
        });
    });

    // Search and Filter Functionality
    document.getElementById('searchInput').addEventListener('input', filterProducts);
    document.getElementById('priceFilter').addEventListener('change', filterProducts);
    document.getElementById('brandFilter').addEventListener('change', filterProducts);
    document.getElementById('categoryFilter').addEventListener('change', filterProducts);

    function filterProducts() {
        let searchValue = document.getElementById('searchInput').value.toLowerCase();
        let priceRange = document.getElementById('priceFilter').value;
        let brandId = document.getElementById('brandFilter').value;
        let categoryId = document.getElementById('categoryFilter').value;

        document.querySelectorAll('.product-card').forEach(card => {
            let name = card.dataset.name;
            let price = parseFloat(card.dataset.price);
            let productBrandId = card.dataset.brandId;
            let productCategoryId = card.dataset.categoryId;

            let matchesSearch = name.includes(searchValue);
            let matchesPrice = !priceRange || (price >= priceRange.split('-')[0] && price <= priceRange.split('-')[1]);
            let matchesBrand = !brandId || productBrandId == brandId;
            let matchesCategory = !categoryId || productCategoryId == categoryId;

            card.style.display = (matchesSearch && matchesPrice && matchesBrand && matchesCategory) ? 'block' : 'none';
        });
    }

    // Toast Notification
    function showToastNotification(message, type) {
        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: type === "success" ? "green" : "red",
        }).showToast();
    }
    </script>
</body>
</html>
