<?php
// Start session only if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the session is set before accessing it
$isLoggedIn = isset($_SESSION['user_id']);
?>

<nav class="bg-gray-900 p-4 shadow-md">
  <div class="container mx-auto flex justify-between items-center">
    <a class="text-white text-lg font-bold" href="index.php">StepNStyle</a>
    
    <!-- Navbar Links Section -->
    <div id="navbar-links" class="space-x-4 hidden md:flex">
      <a class="text-gray-300 hover:text-white transition duration-300" href="/Projects/OnlineShoeStore/public/index.php">Home</a>
      <a class="text-gray-300 hover:text-white transition duration-300" href="/Projects/OnlineShoeStore/public/shop.php">Shop</a>
      <a class="text-gray-300 hover:text-white transition duration-300" href="#">Contact</a>
      
      <!-- Cart Link with Icon -->
      <a class="text-gray-300 hover:text-white relative" href="/Projects/OnlineShoeStore/public/cart.php" aria-label="Shopping Cart">
        <i class="fas fa-shopping-cart text-xl"></i>
        <!-- Cart Item Count -->
        <span id="cart-item-count" class="absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center hidden">
            0
        </span>
      </a>

      <!-- Profile Link -->
      <?php if ($isLoggedIn): ?>
        <a class="text-gray-300 hover:text-white transition duration-300" href="/Projects/OnlineShoeStore/views/account/profile.php">Profile</a>
      <?php else: ?>
        <a class="text-gray-300 hover:text-white transition duration-300" href="/Projects/OnlineShoeStore/views/account/login.php" onclick="alert('Please log in to access your profile.'); return false;">Profile</a>
      <?php endif; ?>

      <!-- Show Login or Logout Link -->
      <?php if ($isLoggedIn): ?>
        <a class="text-gray-300 hover:text-white transition duration-300" href="/Projects/OnlineShoeStore/views/account/logout.php">Logout</a>
      <?php else: ?>
        <a class="text-gray-300 hover:text-white transition duration-300" href="/Projects/OnlineShoeStore/views/account/login.php">Login</a>
      <?php endif; ?>
    </div>

    <!-- Mobile Navbar Toggle -->
    <div class="md:hidden flex items-center">
      <button id="navbar-toggle" class="text-white focus:outline-none" aria-label="Toggle Navigation">
        <i class="fas fa-bars text-2xl"></i>
      </button>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div id="mobile-menu" class="hidden md:hidden bg-gray-800 p-4 transition-all duration-300 ease-in-out">
    <a class="block text-gray-300 hover:text-white py-2" href="/Projects/OnlineShoeStore/public/index.php">Home</a>
    <a class="block text-gray-300 hover:text-white py-2" href="/Projects/OnlineShoeStore/public/shop.php">Shop</a>
    <a class="block text-gray-300 hover:text-white py-2" href="#">Contact</a>
    <a class="block text-gray-300 hover:text-white py-2" href="/Projects/OnlineShoeStore/public/cart.php">Cart</a>
    
    <!-- Profile Link -->
    <?php if ($isLoggedIn): ?>
      <a class="block text-gray-300 hover:text-white py-2" href="/Projects/OnlineShoeStore/views/account/profile.php">Profile</a>
    <?php else: ?>
      <a class="block text-gray-300 hover:text-white py-2" href="/Projects/OnlineShoeStore/views/account/login.php" onclick="alert('Please log in to access your profile.'); return false;">Profile</a>
    <?php endif; ?>

    <?php if ($isLoggedIn): ?>
      <a class="block text-gray-300 hover:text-white py-2" href="/Projects/OnlineShoeStore/views/account/logout.php">Logout</a>
    <?php else: ?>
      <a class="block text-gray-300 hover:text-white py-2" href="/Projects/OnlineShoeStore/views/account/login.php">Login</a>
    <?php endif; ?>
  </div>
</nav>

<!-- Add JavaScript for Mobile Toggle and Cart Item Count -->
<script>
  // Toggle the visibility of navbar links on mobile
  document.getElementById('navbar-toggle').addEventListener('click', function() {
    var mobileMenu = document.getElementById('mobile-menu');
    mobileMenu.classList.toggle('hidden');
  });

  // Update Cart Item Count from PHP
  function updateCartItemCount() {
    fetch('/Projects/OnlineShoeStore/public/api/cart_count.php')
      .then(response => response.text())
      .then(data => {
        let cartCountElement = document.getElementById('cart-item-count');

        if (data.trim() === '' || data.trim() === '0') {
          cartCountElement.style.display = 'none'; // Hide cart count if user is not logged in
        } else {
          cartCountElement.textContent = data;
          cartCountElement.style.display = 'flex'; // Show cart count if logged in
        }
      })
      .catch(error => {
        console.error('Error fetching cart count:', error);
        document.getElementById('cart-item-count').style.display = 'none'; // Hide on error
      });
  }

  // Call the function to update the count on page load
  updateCartItemCount();
</script>