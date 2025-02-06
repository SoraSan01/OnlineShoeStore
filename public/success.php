<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Payment Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-green-100 flex items-center justify-center min-h-screen">
    <nav class="bg-white shadow-md w-full fixed top-0 left-0">
        <?php include "includes/navbar.php"; ?>
    </nav>
    <div class="text-center bg-white p-10 rounded-lg shadow-lg mt-20">
        <i class="fas fa-check-circle text-green-600 text-6xl mb-4"></i>
        <h1 class="text-3xl font-bold text-green-600 mb-4">Payment Successful</h1>
        <p class="text-lg text-gray-700 mb-6">Your payment was processed successfully. Thank you for your purchase!</p>
        <a class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-300" href="/Projects/OnlineShoeStore/public/cart.php">Continue Shopping</a>
    </div>
</body>
</html>