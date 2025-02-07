<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="Discover the latest trends in footwear at Shoe Store. Shop stylish and affordable shoes for every occasion.">
    <meta name="keywords" content="shoes, footwear, sneakers, leather shoes, running shoes, casual shoes">
    <title>Shoe Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body { font-family: 'Roboto', sans-serif; }
        .product-card:hover { transform: scale(1.05); }
    </style>
</head>
<body class="bg-gray-100">
    
    <!-- Navbar -->
    <?php include 'includes/navbar.php'; ?>

<!-- Hero Section -->
<section class="relative py-16 text-white text-center">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://storage.googleapis.com/a1aa/image/amZaxy-Rpprjs9cOt6lhsYlZsel3zE4e_7MDfWwmPCw.jpg');">
        <div class="bg-gradient-to-r from-blue-800 to-indigo-800 opacity-75 inset-0 absolute"></div>
    </div>
    <div class="container mx-auto flex flex-col md:flex-row items-center relative z-10">
        <div class="md:w-1/2 text-center md:text-left">
            <h1 class="text-5xl font-bold mb-4 drop-shadow-lg animate__animated animate__fadeInLeft">Find Your Perfect Pair</h1>
            <p class="text-lg mb-6 drop-shadow-md animate__animated animate__fadeInLeft animate__delay-1s">Discover the latest trends in footwear and step up your style game!</p>
            <a href="/projects/OnlineShoeStore/public/shop.php" class="bg-blue-500 text-white py-2 px-6 rounded-lg transition duration-300 hover:bg-blue-600 shadow-lg transform hover:scale-105">Shop Now</a>
        </div>
        <div class="md:w-1/2 mt-8 md:mt-0">
            <img class="w-full h-auto rounded-lg shadow-lg" src="https://storage.googleapis.com/a1aa/image/amZaxy-Rpprjs9cOt6lhsYlZsel3zE4e_7MDfWwmPCw.jpg" alt="A stylish pair of shoes"/>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-12">
    <div class="container mx-auto">
        <h2 class="text-center text-4xl font-bold mb-10">Featured Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php include __DIR__ . '/api/products.php'; ?>
            <?php foreach ($products as $product): ?>
                <div class="bg-white shadow-lg rounded-lg overflow-hidden transform transition-all duration-300 product-card hover:shadow-xl hover:scale-105">
                    <img class="w-full h-56 object-cover" src="/Projects/OnlineShoeStore/uploads/<?= $product['image']; ?>" alt="<?= $product['name']; ?>"/>
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold"><?= $product['name']; ?></h3>
                        <p class="text-gray-700 my-3">₱<?= $product['price']; ?></p>
                        <a href="#" class="bg-blue-500 text-white py-2 px-4 rounded-lg transition duration-300 hover:bg-blue-600">Buy Now</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Mission and Vision -->
<section class="py-12 bg-blue-50">
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 text-center">
        <div>
            <h2 class="text-4xl font-bold text-gray-800 mb-6">Our Mission</h2>
            <p class="text-lg text-gray-700 mb-6 flex items-center justify-center">
                <i class="fas fa-shoe-prints mr-2 text-blue-600"></i>
                To provide high-quality, affordable, and stylish footwear that allows our customers to express their unique styles with comfort and confidence.
            </p>
        </div>
        <div>
            <h2 class="text-4xl font-bold text-gray-800 mb-6">Our Vision</h2>
            <p class="text-lg text-gray-700 flex items-center justify-center">
                <i class="fas fa-eye mr-2 text-blue-600"></i>
                To be the leading online shoe store, offering a wide range of footwear for all ages and preferences, and to become a trusted brand known for quality and excellent customer service.
            </p>
        </div>
    </div>
</section>

<!-- Contact and Store Location -->
<section class="py-12 bg-white">
    <div class="container mx-auto text-center">
        <h2 class="text-4xl font-bold text-gray-800 mb-6">Contact Us</h2>
        <p class="text-lg text-gray-700 mb-4">Have questions? We're here to help!</p>
        <div class="mb-6">
            <p class="text-gray-700"><i class="fas fa-phone-alt mr-2 text-blue-600"></i> Phone: <a href="tel:+1234567890" class="text-blue-500">+1 (234) 567-890</a></p>
            <p class="text-gray-700"><i class="fas fa-envelope mr-2 text-blue-600"></i> Email: <a href="mailto:info@shoestore.com" class="text-blue-500">info@shoestore.com</a></p>
            <p class="text-gray-700"><i class="fas fa-map-marker-alt mr-2 text-blue-600"></i> Address: 123 Shoe St, Fashion City, FC 12345</p>
        </div>
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Store Location</h3>
        <div class="w-full h-64 mb-4">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509123!2d144.9537353153163!3d-37.81627997975157!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f0f0f0f%3A0x0!2sShoe%20Store!5e0!3m2!1sen!2sus!4v1631234567890!5m2!1sen!2sus" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

</body>
</html>
