<?php
require("backend/connect-db.php");
require("backend/backend-functs.php");
?>

<!-- Form handling -->
<?php
//echo $_SERVER['REQUEST_METHOD'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['create-btn'])) {
        addAccount($_POST['username'], $_POST['password'], $_POST['conf-password']);
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

    <title>Create Account</title>
    
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Create Account</h2>
        <form method="post" action="create-account.php">
            <div class="mb-4">
                <label for="username" class="block text-gray-800 font-semibold mb-2">Username</label>
                <input type="text" id="username" name="username" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Username" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-800 font-semibold mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Password" required>
            </div>
            <div class="mb-4">
                <label for="conf-password" class="block text-gray-800 font-semibold mb-2">Confirm Password</label>
                <input type="password" id="conf-password" name="conf-password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Password" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <button id="back-btn" onclick="window.location.href='index.html'" class="w-full py-2 px-4 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:bg-red-600">Back</button>
                <input type="submit" id="create-btn" name="create-btn" value="Create" class="w-full py-2 px-4 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:bg-green-600">
            </div>
        </form>
    </div>
    <script>
        const form = document.querySelector('form'); 
        form.addEventListener('submit', function(event) { 
            let pass = document.getElementById("password").value;
            let conf_pass = document.getElementById("conf-password").value;
            if (pass !== conf_pass) {
                alert("Passwords do not match!");
            }
            else {
                alert("Account created");
            }
        }); 
    </script>
</body>
</html>