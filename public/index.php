<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>
   Shoe Store
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet"/>
  <style>
   body {
            font-family: 'Roboto', sans-serif;
        }
  </style>
 </head>
 <body class="bg-gray-100">
   <!-- Navbar -->
   <?php
     include 'includes/navbar.php';
     ?>
  <!-- Hero Section -->
  <section class="py-12 bg-gray-200">
   <div class="container mx-auto text-center">
    <div class="flex flex-col md:flex-row items-center">
     <div class="md:w-1/2">
      <h1 class="text-4xl font-bold mb-4">
       Find Your Perfect Pair
      </h1>
      <p class="text-lg mb-6">
       Discover the latest trends in footwear and find your perfect pair of shoes. Shop now and step up your style game!
      </p>
      <a class="bg-blue-500 text-white py-2 px-4 rounded" href="#">
       Shop Now
      </a>
     </div>
     <div class="md:w-1/2 mt-8 md:mt-0">
      <img alt="A stylish pair of shoes displayed on a modern background" class="w-full h-auto" height="400" src="https://storage.googleapis.com/a1aa/image/amZaxy-Rpprjs9cOt6lhsYlZsel3zE4e_7MDfWwmPCw.jpg" width="600"/>
     </div>
    </div>
   </div>
  </section>
  <!-- Featured Products -->
  <section class="py-12">
   <div class="container mx-auto">
    <h2 class="text-center text-3xl font-bold mb-8">
     Featured Products
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
     <div class="bg-white shadow-lg rounded-lg overflow-hidden">
      <img alt="A pair of running shoes in vibrant colors" class="w-full h-48 object-cover" height="300" src="https://storage.googleapis.com/a1aa/image/SBMkUSSJlqfowGbmd_0RlObuBKsho7aFppyxpQ8c8rs.jpg" width="400"/>
      <div class="p-6 text-center">
       <h3 class="text-xl font-bold mb-2">
        Running Shoes
       </h3>
       <p class="text-gray-700 mb-4">
        $120.00
       </p>
       <a class="bg-blue-500 text-white py-2 px-4 rounded" href="#">
        Buy Now
       </a>
      </div>
     </div>
     <div class="bg-white shadow-lg rounded-lg overflow-hidden">
      <img alt="A pair of elegant leather shoes suitable for formal occasions" class="w-full h-48 object-cover" height="300" src="https://storage.googleapis.com/a1aa/image/10ibvERaabOLNyBHEiv3YCSTb8jkQO4yXF2LT3GssJ0.jpg" width="400"/>
      <div class="p-6 text-center">
       <h3 class="text-xl font-bold mb-2">
        Leather Shoes
       </h3>
       <p class="text-gray-700 mb-4">
        $150.00
       </p>
       <a class="bg-blue-500 text-white py-2 px-4 rounded" href="#">
        Buy Now
       </a>
      </div>
     </div>
     <div class="bg-white shadow-lg rounded-lg overflow-hidden">
      <img alt="A pair of casual sneakers in a trendy design" class="w-full h-48 object-cover" height="300" src="https://storage.googleapis.com/a1aa/image/tyY62ItPmVk1lvgnBUh-5J_REIGPZX8_5o4umhaBvt4.jpg" width="400"/>
      <div class="p-6 text-center">
       <h3 class="text-xl font-bold mb-2">
        Casual Sneakers
       </h3>
       <p class="text-gray-700 mb-4">
        $90.00
       </p>
       <a class="bg-blue-500 text-white py-2 px-4 rounded" href="#">
        Buy Now
       </a>
      </div>
     </div>
    </div>
   </div>
  </section>
  <!-- Mission and Vision Section -->
  <section class="py-12 bg-gray-200">
   <div class="container mx-auto">
    <h2 class="text-center text-3xl font-bold mb-8">
     Mission &amp; Vision
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
     <div class="text-center">
      <h4 class="text-xl font-bold mb-4">
       Our Mission
      </h4>
      <p>
       To provide our customers with the highest quality footwear that combines style, comfort, and affordability, while delivering exceptional customer service and fostering a sustainable future.
      </p>
     </div>
     <div class="text-center">
      <h4 class="text-xl font-bold mb-4">
       Our Vision
      </h4>
      <p>
       To be the leading shoe retailer known for innovation and quality, inspiring individuals to express their unique style through our diverse range of footwear.
      </p>
     </div>
    </div>
   </div>
  </section>
  <!-- Newsletter Section -->
  <section class="py-12 bg-blue-500 text-white text-center">
   <div class="container mx-auto">
    <h2 class="text-3xl font-bold mb-4">
     Subscribe to Our Newsletter
    </h2>
    <p class="mb-6">
     Get the latest updates on new arrivals and exclusive discounts.
    </p>
    <form class="flex justify-center">
     <input class="p-2 rounded-l-lg" placeholder="Enter your email" type="email"/>
     <button class="bg-white text-blue-500 py-2 px-4 rounded-r-lg" type="submit">
      Subscribe
     </button>
    </form>
   </div>
  </section>
  <!-- Footer -->
  <footer class="py-4 bg-gray-800 text-white text-center">
   <div class="container mx-auto">
    <p>
     © 2023 ShoeStore. All rights reserved.
    </p>
    <div class="mt-4">
     <a class="text-white mx-2" href="#">
      <i class="fab fa-facebook-f">
      </i>
     </a>
     <a class="text-white mx-2" href="#">
      <i class="fab fa-twitter">
      </i>
     </a>
     <a class="text-white mx-2" href="#">
      <i class="fab fa-instagram">
      </i>
     </a>
     <a class="text-white mx-2" href="#">
      <i class="fab fa-linkedin-in">
      </i>
     </a>
    </div>
   </div>
  </footer>
 </body>
</html>
