<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "shoe_store";
$con = mysqli_connect($servername, $username, $password, $dbname);

if (mysqli_connect_errno()) {
    exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

$user_id = $_SESSION['user_id'];
$stmt = $con->prepare('SELECT username, email, phone_number, address FROM users WHERE id = ?');
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $phone_number, $address);
$stmt->fetch();
$stmt->close();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $new_phone_number = filter_var(trim($_POST['phone_number']), FILTER_SANITIZE_STRING);
    $new_address = filter_var(trim($_POST['address']), FILTER_SANITIZE_STRING);

    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        $update_stmt = $con->prepare('UPDATE users SET email = ?, phone_number = ?, address = ? WHERE id = ?');
        $update_stmt->bind_param('sssi', $new_email, $new_phone_number, $new_address, $user_id);
        if ($update_stmt->execute()) {
            $message = 'Profile updated successfully!';
        } else {
            $error = 'Error updating profile. Please try again.';
        }
        $update_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
</head>
<body class="bg-gray-100 font-roboto">
    <?php include "../../public/includes/navbar.php"; ?>

    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-lg w-full">
            <h1 class="text-3xl font-bold text-center mb-6 text-indigo-600">User Profile</h1>

            <?php if ($message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <p><?php echo htmlspecialchars($message); ?></p>
                </div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" value="<?php echo htmlspecialchars($username); ?>" disabled class="w-full px-4 py-2 border rounded bg-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required class="w-full px-4 py-2 border rounded focus:ring focus:ring-indigo-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="text" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" class="w-full px-4 py-2 border rounded focus:ring focus:ring-indigo-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <textarea name="address" required class="w-full px-4 py-2 border rounded focus:ring focus:ring-indigo-300"><?php echo htmlspecialchars($address); ?></textarea>
                </div>
                <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">Update Profile</button>
            </form>
        </div>
    </div>
</body>
</html>