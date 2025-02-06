<nav class="bg-gray-800 p-4">
  <div class="container mx-auto flex justify-between items-center">
    <a class="text-white text-lg font-bold" href="index.php">Shoe Store</a>
    
    <!-- Navbar Links Section -->
    <div id="navbar-links" class="space-x-4 hidden md:flex">
      <a class="text-gray-300 hover:text-white" href="index.php">Home</a>
      <a class="text-gray-300 hover:text-white" href="shop.php">Shop</a>
      <a class="text-gray-300 hover:text-white" href="#">Contact</a>
      
      <!-- Cart Link with Icon -->
      <a class="text-gray-300 hover:text-white relative" href="cart.php">
        <i class="fas fa-shopping-cart"></i>
        <!-- Cart Item Count -->
        <span id="cart-item-count" class="absolute top-0 right-0 bg-red-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
            0
        </span>
      </a>

      <!-- Logout Link -->
      <a class="text-gray-300 hover:text-white" href="/Projects/OnlineShoeStore/views/account/logout.php">Logout</a>
    </div>

    <!-- Mobile Navbar Toggle -->
    <div class="md:hidden flex items-center">
      <button id="navbar-toggle" class="text-white">
        <i class="fas fa-bars"></i>
      </button>
    </div>
  </div>
</nav>

<!-- Add JavaScript for Mobile Toggle and Cart Item Count -->
<script>
  // Toggle the visibility of navbar links on mobile
  document.getElementById('navbar-toggle').addEventListener('click', function() {
    var navbarLinks = document.getElementById('navbar-links');
    navbarLinks.classList.toggle('hidden');
  });

  // Update Cart Item Count from PHP
  function updateCartItemCount() {
    // Fetch the cart count from the PHP backend
    fetch('/Projects/OnlineShoeStore/public/api/cart_count.php')
      .then(response => response.text())
      .then(data => {
        // Update the cart item count span with the fetched data
        document.getElementById('cart-item-count').textContent = data;
      })
      .catch(error => {
        console.error('Error fetching cart count:', error);
        document.getElementById('cart-item-count').textContent = 0;  // Default to 0 if there's an error
      });
  }

  // Call the function to update the count on page load
  updateCartItemCount();
</script>
