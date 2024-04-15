<?php
require("backend/connect-db.php");
require("backend/backend-functs.php");
?>

<!-- Form Handling -->
<?php
//echo $_SERVER['REQUEST_METHOD'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['login-btn'])) {
        login($_POST['username'], $_POST['password']);
    }
}
?>

<!-- Page Structure -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Kenny Zhang">
    <meta name="description" content="Flashcard App Project for CS 4750">

    <title>QuizIt Login</title>
    
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">QuizIt Login</h2>
        <form method="post" action="login.php">
            <div class="mb-4">
                <label for="username" class="block text-gray-800 font-semibold mb-2">Username</label>
                <input type="text" id="username" name="username" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Username" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-800 font-semibold mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Password" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <input id="login-btn" name="login-btn" type="submit" value="Login" class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                <button id="create-acc-btn" onclick="window.location.href='create-account.php'" class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Create Account</button>
            </div>
        </form>
    </div>
</body>
</html>