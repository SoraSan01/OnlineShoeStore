<?php

include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // Modify the query to omit 'id' since it's auto-incremented
        $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fullname, $username, $email, $hashed_password);
        
        if ($stmt->execute()) {
            header("Location: login.php?success=registered");
            exit();
        } else {
            $error_message = "Registration failed. Try again.";
        }
    }
}
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | Pamela Mabulay Shoe Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-gray-800 via-gray-900 to-black min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md">
        <h3 class="text-2xl font-bold text-center text-white mb-4">Create an Account</h3>
        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <input type="text" class="w-full p-3 border border-gray-600 rounded bg-gray-700 text-white" name="fullname" placeholder="Fullname" required>
            </div>
            <div class="mb-4">
                <input type="text" class="w-full p-3 border border-gray-600 rounded bg-gray-700 text-white" name="username" placeholder="Username" required>
            </div>
            <div class="mb-4">
                <input type="email" class="w-full p-3 border border-gray-600 rounded bg-gray-700 text-white" name="email" placeholder="Email" required>
            </div>
            <div class="mb-4">
                <input type="password" class="w-full p-3 border border-gray-600 rounded bg-gray-700 text-white" id="inputPassword" name="password" placeholder="Password" required>
            </div>
            <div class="mb-4">
                <input type="password" class="w-full p-3 border border-gray-600 rounded bg-gray-700 text-white" id="confirmPassword" name="confirm_password" placeholder="Confirm Password" required>
            </div>
            <div class="flex items-center mb-4">
                <input class="mr-2" type="checkbox" id="togglePassword" onclick="togglePass()">
                <label for="togglePassword" class="text-sm text-gray-400">Show Password</label>
            </div>
            <div class="mb-4">
                <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded hover:bg-blue-700 transition">Register</button>
            </div>
            <div class="text-center">
                <a href="login.php" class="text-sm text-blue-400 hover:underline">Already have an account? Login</a>
            </div>
        </form>
    </div>

    <script>
        function togglePass() {
            var passwordField = document.getElementById("inputPassword");
            var confirmPasswordField = document.getElementById("confirmPassword");
            var isChecked = document.getElementById("togglePassword").checked;
            passwordField.type = isChecked ? "text" : "password";
            confirmPasswordField.type = isChecked ? "text" : "password";
        }
    </script>
</body>
</html>
