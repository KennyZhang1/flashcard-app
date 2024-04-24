<?php
require("backend/connect-db.php");
require("backend/backend-functs.php");
?>

<?php
session_start();
if (!isset($_SESSION["curr_user"])) {
    header('Location: login.php');
}
$curr_user = $_SESSION['curr_user'];
//var_dump($curr_user);
$deck_list = getAllDecks($curr_user);
$master_list = getMasteredDecks($curr_user);
//var_dump($master_list);
$num_decks = getNumDecks($curr_user);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['deck-study-btn'])) {
        fetchDeckStudy($_POST['deck-study-id']);
    }
    if (!empty($_POST['deck-edit-btn'])) {
        fetchDeckEdit($_POST['deck-edit-id']);
    }
    if (!empty($_POST['deck-delete-btn'])) {
        deleteDeck($_POST['deck-delete-id'], $curr_user);
        $deck_list = getAllDecks($curr_user);
        $num_decks = getNumDecks($curr_user);
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

    <title>Home</title>
    
    <!-- Include CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="static/styles.css">
    <script src="https://kit.fontawesome.com/7a937c8eef.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100">
    <header>
        <nav>
            <div class="topnav">
                <a class="active" href="home.php">Home</a>
                <a href="profile.php">Profile</a>
                <a href="friends.php">Friends</a>
                <a href="account.php">Account</a>
                <a style="float:right" class="logout" href="login.php">Logout</a>
            </div>
        </nav>
    </header>

    <div class="mt-8">
        <div class="w-4/6 mx-auto bg-white shadow-md rounded-lg overflow-hidden">
            <h2 class="text-3xl font-semibold text-center py-4">Welcome, <?php echo $curr_user; ?></h2>
            <h2 class="text-2xl font-semibold text-center py-2">My Decks</h2>
            <div class="flex justify-center mb-4">
                <ul class="w-full deck-list">
                    <?php foreach ($deck_list as $deck): ?>
                        <?php
                            $determiner = "";
                            if (in_array($deck["deck_id"], $master_list)) {
                                $determiner = "deck-item-mastered";
                            }
                        ?>
                        <li class="<?php echo $determiner ?>">
                            <span class="deck-icon content-center">
                                <?php if ($determiner === "deck-item-mastered"): ?>
                                    <img src="static/master-deck.png" alt="Icon" class="w-12 h-12 mr-3">
                                <?php else: ?>
                                    <img src="static/deck.png" alt="Icon" class="w-12 h-12 mr-3">
                                <?php endif; ?>
                            </span>
                            <span class="deck-text">
                                <div class="mb-1 text-lg font-semibold deck-title">
                                    <p><?php echo $deck['title']; ?></p>
                                </div>
                                <div class="mb-1">
                                    <div class="deck-desc">
                                        <p><?php echo $deck['description']; ?></p>
                                    </div>
                                    <div class="deck-date">
                                        <p><?php echo $deck['creation_date']; ?></p>
                                    </div>
                                </div>
                            </span>
                            <span class="deck-size">
                                <p><?php echo $deck['size']; ?></p>
                            </span>
                            <span class="flashcard-icon">
                                <i class="fa-solid fa-note-sticky"></i>
                            </span>
                            <span class="deck-mastery">
                                <p><?php echo $deck['mastery_score']; ?></p>
                            </span>
                            <span class="mastery-icon">
                                <?php if ($determiner === "deck-item-mastered"):?>
                                    <span class="diamond-icon">
                                        <i class="fa-solid fa-gem"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="star-icon">
                                        <i class="fa-solid fa-star"></i>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <span class="study-deck">
                                <form action="home.php" method="post">
                                    <input type="submit" id="deck-study-btn" name="deck-study-btn" value="Study"
                                        class="w-full py-2 px-4 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:bg-green-600">
                                    <input type="hidden" id="deck-study-id" name="deck-study-id" value="<?php echo $deck['deck_id']; ?>">
                                </form>
                            </span>
                            <span class="edit-deck">
                                <form action="home.php" method="post">
                                    <input type="submit" id="deck-edit-btn" name="deck-edit-btn" value="Edit"
                                        class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                                    <input type="hidden" id="deck-edit-id" name="deck-edit-id" value="<?php echo $deck['deck_id']; ?>">
                                </form>
                            </span>
                            <span class="delete-deck">
                                <form action="home.php" method="post" onsubmit="return confirm('Are you sure you want to delete this deck?');">
                                    <input type="submit" id="deck-delete-btn" name="deck-delete-btn" value="Delete"
                                        class="w-full py-2 px-4 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:bg-red-600">
                                    <input type="hidden" id="deck-delete-id" name="deck-delete-id" value="<?php echo $deck['deck_id']; ?>">
                                </form>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="flex justify-center mb-4">
                <h2 class="text-2xl font-semibold text-center py-2">Total: <?php echo $num_decks['COUNT(*)']; ?></h2>
            </div>
            <div class="flex justify-center mb-4">
                <div class="grid grid-cols-3 gap-4">
                    <button id="create-deck-btn" onclick="window.location.href='deck/create-deck.php'"
                        class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                        Create Deck
                    </button>
                    <button id="create-quiz-btn" onclick="window.location.href='quiz/create-quiz.php'"
                        class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                        Create Quiz
                    </button>
                    <button id="view-quizzes-btn" onclick="window.location.href='quiz/quizzes.php'"
                        class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                        View Quizzes
                    </button> 
                </div>
            </div>
        </div>
    </div>
</body>
</html>