<?php
require("backend/connect-db.php");
require("backend/backend-functs.php");
?>

<!-- Form Handling -->
<?php
session_start();
$curr_user = $_SESSION['curr_user'];
$list_of_nicknames = getAllNicknames($curr_user);
$quiz_rating = getQuizRating($curr_user);
$num_decks = getNumDecks($curr_user);
$num_mastered = getNumDecksMastered($curr_user);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['deleteBtn'])) {
        deleteNickname($curr_user, $_POST['delete-nick']);
        $list_of_nicknames = getAllNicknames($curr_user);
    }
    if (!empty($_POST['addBtn'])) {
        addNickname($curr_user, $_POST['new-nick']);
        $list_of_nicknames = getAllNicknames($curr_user);
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

    <title>Profile</title>
    
    <!-- Include CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="static/styles.css">
</head>
<body class="bg-gray-100">
    <header>
        <nav>
            <div class="topnav">
                <a href="home.php">Home</a>
                <a class="active" href="profile.php">Profile</a>
                <a href="friends.php">Friends</a>
                <a href="account.php">Account</a>
                <a style="float:right" class="logout" href="login.php">Logout</a>
            </div>
        </nav>
    </header>
    <div class="mt-8">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
            <h2 class="text-2xl font-semibold text-center py-4">Nicknames</h2>
            <div class="flex justify-center">
                <div class="w-full sm:w-auto">
                    <table>
                        <?php foreach ($list_of_nicknames as $nick_entry): ?>
                            <tr>
                                <td class="pr-6 py-3"><?php echo $nick_entry['nickname']; ?></td>
                                <td class="pl-20">
                                    <form action="profile.php" method="post">
                                        <input type="submit" id="deleteBtn" name="deleteBtn" value="X"
                                            class="py-0.8 px-2 bg-red-600 text-white font-semibold rounded-full hover:bg-red-700 focus:outline-none focus:bg-red-700">
                                        <input type="hidden" id="delete-nick" name="delete-nick"
                                            value="<?php echo $nick_entry['nickname']; ?>">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="2" class="py-4">
                                <form action="profile.php" method="post" class="flex items-center justify-center">
                                    <input type="text" id="new-nick" name="new-nick"
                                        class="w-full sm:w-auto px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
                                        placeholder="Nickname" required>
                                    <input type="submit" id="addBtn" name="addBtn" value="Add"
                                        class="py-2 px-4 ml-4 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:bg-green-600">
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>   
    </div>
    <div class="mt-8">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
            <h2 class="text-2xl font-semibold text-center py-4">Stats</h2>
            <div class="flex justify-center">
                <span class="mx-4 mb-6 flex items-center">
                    <img src="static/trophy.png" alt="Icon" class="w-12 h-12 mr-4">
                    <div>
                        <p class="text-3xl font-bold"><?php echo $quiz_rating['quiz_rating']; ?></p>
                        <p class="text-gray-600">Rating</p>
                    </div>
                </span>
                <span class="mx-4 mb-6 flex items-center">
                    <img src="static/deck.png" alt="Icon" class="w-12 h-12 mr-4">
                    <div>
                        <p class="text-3xl font-bold"><?php echo $num_decks['COUNT(*)']; ?></p>
                        <p class="text-gray-600">Decks</p>
                    </div>
                </span>
                <span class="mx-4 mb-6 flex items-center">
                    <img src="static/medal.png" alt="Icon" class="w-12 h-12 mr-4">
                    <div>
                        <p class="text-3xl font-bold"><?php echo $num_mastered['COUNT(*)']; ?></p>
                        <p class="text-gray-600">Mastered</p>
                    </div>
                </span>
            </div>
        </div>
    </div>
</body>
</html>