<?php
require("backend/connect-db.php");
require("backend/backend-functs.php");
?>

<!-- Form Handling -->
<?php
session_start();
$curr_user = $_SESSION['curr_user'];
$curr_date = date('Y-m-d');
//echo $curr_date;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['send-req-btn'])) {
        sendRequest($_POST['recipient'], $_POST['message'], $curr_date, $curr_user);
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

    <title>Add Friend</title>
    
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
                <a href="account.php">Account</a>
                <a style="float:right" class="logout" href="login.php">Logout</a>
            </div>
        </nav>
    </header>
    <div class="mt-16">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <h2 class="text-2xl font-semibold text-center py-4">Send Friend Request</h2>
            <div class="p-4">
                <form action="add-friend.php" method="post">
                    <div class="mb-4">
                        <label for="recipient" class="block text-gray-800 font-semibold mb-2">Recipient Username</label>
                        <input type="text" id="recipient" name="recipient" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Username" required>
                    </div>
                    <div class="mb-4">
                        <label for="message" class="block text-gray-800 font-semibold mb-2">Message</label>
                        <input type="text" id="message" name="message" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Message" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <button id="back-btn" onclick="window.location.href='friends.php'" class="w-full py-2 px-4 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:bg-red-600">Back</button>
                        <input id="send-req-btn" name="send-req-btn" type="submit" value="Send" class="py-2 px-4 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:bg-green-600">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>