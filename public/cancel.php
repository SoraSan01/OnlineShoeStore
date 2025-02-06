<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>
   Payment Canceled
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
 <body class="bg-red-100 flex flex-col min-h-screen">
  <!-- Navbar -->
  <nav class="bg-white shadow-md w-full">
    <?php include "includes/navbar.php"; ?>
  </nav>
  <!-- Main Content -->
  <div class="flex-grow flex items-center justify-center">
   <div class="text-center p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold text-red-600 mb-4">
     Payment Canceled
    </h1>
    <p class="text-gray-700 mb-6">
     Your payment has been canceled. If you believe this is an error, please try again or contact support.
    </p>
    <a class="inline-block bg-red-600 text-white px-6 py-2 rounded-full hover:bg-red-700 transition duration-300" href="/Projects/OnlineShoeStore/public/cart.php">
     <i class="fas fa-arrow-left mr-2">
     </i>
     Go Back to Shop
    </a>
   </div>
  </div>
 </body>
</html>
