<?php
require("../backend/connect-db.php");
require("../backend/backend-functs.php");
?>

<!-- Form Handling -->
<?php
session_start();
if (!isset($_SESSION["curr_user"])) {
    header('Location: ../login.php');
}
$curr_user = $_SESSION['curr_user'];
$curr_deck = $_SESSION['curr_deck'];
$curr_card = $_SESSION['curr_card'];
$curr_card_info = getCurrCardInfo($curr_deck, $curr_card);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['update-card-btn'])) {
        updateCard($curr_deck, $curr_card, $_POST['new-term'], $_POST['new-def']);
    }
    if (!empty($_POST['update-card-back'])) {
        returnToDeckEdit();
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

    <title>Edit Card</title>
    
    <!-- Include CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../static/styles.css">
</head>
<body class="bg-gray-100">
    <header>
        <nav>
            <div class="topnav">
                <a href="../home.php">Home</a>
                <a href="../profile.php">Profile</a>
                <a href="../friends.php">Friends</a>
                <a href="../account.php">Account</a>
                <a style="float:right" class="logout" href="../login.php">Logout</a>
            </div>
        </nav>
    </header>
    <div class="mt-16">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
        <h2 class="text-2xl font-semibold text-center py-4">Edit Card</h2>
            <div class="p-4">
                <form action="edit-card.php" method="post">
                    <div class="mb-4">
                        <label for="new-term" class="block text-gray-800 font-semibold mb-2">New Term</label>
                        <input type="text" id="new-term" name="new-term" value="<?php echo $curr_card_info['term']; ?>"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Title" required>
                    </div>
                    <div class="mb-4">
                        <label for="new-def" class="block text-gray-800 font-semibold mb-2">New Definition</label>
                        <input type="text" id="new-def" name="new-def" value="<?php echo $curr_card_info['definition']; ?>"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Description" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <input id="update-card-back" name="update-card-back" type="submit" value="Back" class="w-full py-2 px-4 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:bg-red-600">
                        <input id="update-card-btn" name="update-card-btn" type="submit" value="Confirm" class="py-2 px-4 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:bg-green-600">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>