<?php
// Include the connection file
include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/config/connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['user_email']);
    $password = trim($_POST['user_password']);
    
    // Check if email and password are empty
    if (empty($email) || empty($password)) {
        $error_message = "Please fill in all fields.";
    } else {
        // Query to check user existence in the admins table
        $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Admin found, now check password
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Password matches, start the session and check role
                session_start();
                $_SESSION['user_id'] = $user['id']; // Assuming there's an id column
                $_SESSION['role'] = $user['role'];
                
                // Redirect based on the role
                if ($user['role'] === 'admin') {
                    header("Location: /Projects/OnlineShoeStore/views/admin/dashboard.php");
                } else {
                    header("Location: /Projects/OnlineShoeStore/views/account/login.php"); // Redirect to user dashboard
                }
                exit();
            } else {
                $error_message = "Incorrect password.";
            }
        } else {
            // Query to check user existence in the users table (without checking role)
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // User found, now check password
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Password matches, start the session
                    session_start();
                    $_SESSION['user_id'] = $user['id']; // Assuming there's an id column
                    header("Location: /Projects/OnlineShoeStore/public/shop.php"); // Redirect to user dashboard
                    exit();
                } else {
                    $error_message = "Incorrect password.";
                }
            } else {
                $error_message = "No user found with that email.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Pamela Mabulay Shoe Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #f44336;
            color: white;
            padding: 16px;
            border-radius: 5px;
            display: none;
            z-index: 1000;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-gray-800 via-gray-900 to-black min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md">
        <h3 class="text-2xl font-bold text-center text-white mb-4">Login</h3>
        <div id="toast" class="toast"></div>
        <form method="POST">
            <div class="mb-4">
                <input type="email" class="w-full p-3 border border-gray-600 rounded bg-gray-700 text-white" id="inputEmail" name="user_email" placeholder="Email" required>
            </div>
            <div class="mb-4">
                <div class="relative">
                    <input type="password" class="w-full p-3 border border-gray-600 rounded bg-gray-700 text-white" id="inputPassword" name="user_password" placeholder="Password" required>
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i id="togglePassword" class="fas fa-eye cursor-pointer text-gray-400" onclick="togglePass()"></i>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded hover:bg-blue-700 transition">Login</button>
            </div>
            <div class="text-center">
                <a href="password.html" class="text-sm text-blue-400 hover:underline">Forgot Password?</a> | <a href="register.php" class="text-sm text-blue-400 hover:underline">Register Account</a>
            </div>
        </form>
    </div>

    <script>
        function togglePass() {
            var passwordField = document.getElementById("inputPassword");
            var toggleIcon = document.getElementById("togglePassword");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }

        function showToast(message) {
            var toast = document.getElementById("toast");
            toast.innerText = message;
            toast.style.display = "block";
            setTimeout(function() {
                toast.style.display = "none";
            }, 3000);
        }

        <?php if (isset($error_message)): ?>
            echo "showToast('<?php echo addslashes($error_message); ?>');";
        <?php endif; ?>
    </script>
</body>
</html>