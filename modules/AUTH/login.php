<?php
session_start();
require_once '../../includes/db.php';

// 🔐 TEMPORARY: Display hashed password for manual SQL insert
// Replace "admin123" with your desired password
//echo "Hashed password: " . password_hash("admin123", PASSWORD_DEFAULT);
//exit;
//email:admin@spa.com
//password:admin123

// 🔐 LOGIN LOGIC
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM staff WHERE email = ? AND is_active = 1 LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['staff_id'] = $user['id'];
        $_SESSION['staff_name'] = $user['full_name'];
        $_SESSION['role_id'] = $user['role_id'];

        $conn->query("UPDATE staff SET last_login = NOW() WHERE id = {$user['id']}");

        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-sm">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Staff Login</h2>

        <?php if (isset($error)): ?>
            <p class="text-red-600 bg-red-100 border border-red-300 px-3 py-2 rounded mb-4 text-sm"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
                <label class="block text-gray-700 text-sm mb-1" for="email">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 text-sm mb-1" for="password">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-200">
                Login
            </button>
        </form>
    </div>
</body>
</html>
