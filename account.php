<?php
require("backend/connect-db.php");
require("backend/backend-functs.php");
?>

<!-- Form Handling -->
<?php
session_start();
$curr_user = $_SESSION['curr_user'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['change-pass-btn'])) {
        change_password($curr_user, $_POST["curr-password"], $_POST["new-password"], $_POST["conf-password"]);
    }
}
//var_dump($curr_user);
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

    <title>Account Settings</title>
    
    <!-- Include CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="static/styles.css">
</head>
<body class="bg-gray-100">
    <header>
        <nav>
            <div class="topnav">
                <a href="home.php">Home</a>
                <a href="profile.php">Profile</a>
                <a href="friends.php">Friends</a>
                <a class="active" href="account.php">Account</a>
                <a style="float:right" class="logout" href="login.php">Logout</a>
            </div>
        </nav>
    </header>
    <div class="mt-16">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <h2 class="text-2xl font-semibold text-center py-4">Change Password</h2>
            <div class="p-4">
                <form action="account.php" method="post">
                    <div class="mb-4">
                        <label for="curr-password" class="block text-gray-800 font-semibold mb-2">Current Password</label>
                        <input type="password" id="curr-password" name="curr-password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Current Password" required>
                    </div>
                    <div class="mb-4">
                        <label for="new-password" class="block text-gray-800 font-semibold mb-2">New Password</label>
                        <input type="password" id="new-password" name="new-password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="New Password" required>
                    </div>
                    <div class="mb-4">
                        <label for="conf-password" class="block text-gray-800 font-semibold mb-2">Confirm Password</label>
                        <input type="password" id="conf-password" name="conf-password" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Confirm Password" required>
                    </div>
                    <div class="grid grid-cols-1 gap-4">
                        <input id="change-pass-btn" name="change-pass-btn" type="submit" value="Change" class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>