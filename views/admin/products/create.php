<?php
// Database connection
function getDatabaseConnection() {
    try {
        return new PDO("mysql:host=localhost;dbname=shoe_store;charset=utf8", "root", "", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e) {
        logError($e);
        echo json_encode(["Result" => "ERROR", "Message" => "Database connection failed."]);
        exit;
    }
}

// Log error to a file
function logError($exception) {
    error_log($exception->getMessage(), 3, '/path/to/your/error.log'); // Change to your log file path
}

// Fetch categories and brands
function fetchCategoriesAndBrands($pdo) {
    $categories = $pdo->query("SELECT id, name FROM categories")->fetchAll();
    $brands = $pdo->query("SELECT id, name FROM brands")->fetchAll();
    return [$categories, $brands];
}

// Validate and sanitize input
function validateInput($data) {
    $errors = [];
    if (empty($data['name'])) {
        $errors[] = "Product name is required.";
    }
    if ($data['price'] <= 0) {
        $errors[] = "Price must be greater than 0.";
    }
    if ($data['stock'] < 0) {
        $errors[] = "Stock cannot be less than 0.";
    }
    if (empty($data['description'])) {
        $errors[] = "Description is required.";
    }
    if ($data['brand_id'] <= 0) {
        $errors[] = "Valid brand ID is required.";
    }
    if ($data['category_id'] <= 0) {
        $errors[] = "Valid category ID is required.";
    }
    return $errors;
}

// Handle image upload
function handleImageUpload($image) {
    if ($image && $image['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($image['type'], $allowedTypes)) {
            return ["error" => "Invalid image format. Only JPG, PNG, and GIF are allowed."];
        }
        if ($image['size'] > $maxSize) {
            return ["error" => "Image size exceeds 5MB."];
        }

        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/Projects/OnlineShoeStore/uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imagePath = uniqid('product_', true) . '.' . pathinfo($image['name'], PATHINFO_EXTENSION);
        $fullImagePath = $uploadDir . $imagePath;

        if (!move_uploaded_file($image['tmp_name'], $fullImagePath)) {
            return ["error" => "Failed to upload image."];
        }
        return ["path" => $imagePath];
    }
    return ["path" => null];
}

// Main logic
$pdo = getDatabaseConnection();
list($categories, $brands) = fetchCategoriesAndBrands($pdo);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = [
        'name' => trim($_POST['name']),
        'price' => floatval($_POST['price']),
        'stock' => intval($_POST['stock']),
        'description' => trim($_POST['description']),
        'brand_id' => intval($_POST['brand']),
        'category_id' => intval($_POST['category']),
        'image' => $_FILES['image'] ?? null
    ];

    $errors = validateInput($data);
    if (!empty($errors)) {
        echo json_encode(["Result" => "ERROR", "Message" => implode(" ", $errors)]);
        exit;
    }

    $imageUploadResult = handleImageUpload($data['image']);
    if (isset($imageUploadResult['error'])) {
        echo json_encode(["Result" => "ERROR", "Message" => $imageUploadResult['error']]);
        exit;
    }

    $data['image'] = $imageUploadResult['path'];

    try {
        $stmt = $pdo->prepare("INSERT INTO products (name, price, stock, description, brand_id, category_id, image) 
                                VALUES (:name, :price, :stock, :description, :brand_id, :category_id, :image)");
        $stmt->execute([
            ':name' => $data['name'],
            ':price' => $data['price'],
            ':stock' => $data['stock'],
            ':description' => $data['description'],
            ':brand_id' => $data['brand_id'],
            ':category_id' => $data['category_id'],
            ':image' => $data['image']
        ]);
        echo json_encode(["Result" => "OK", "Message" => "Product added successfully."]);
    } catch (PDOException $e) {
        logError($e);
        echo json_encode(["Result" => "ERROR", "Message" => "Database error."]);
    }
}
?>