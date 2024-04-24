<?php
require("../backend/connect-db.php");
require("../backend/backend-functs.php");
?>

<!-- Form handling -->
<?php
session_start();
if (!isset($_SESSION["curr_user"])) {
    header('Location: ../login.php');
}
$curr_user = $_SESSION['curr_user'];
$curr_deck = $_SESSION['curr_deck'];
$curr_deck_title = getDeckTitle($curr_deck);
$curr_deck_description = getDeckDescription($curr_deck);
$cards_list = getDeckCards($curr_deck);
$num_cards = getDeckSize($curr_deck)['size'];
$master_list = getMasteredDecks($curr_user);
$determiner = "";
$is_mastered = false;
if (in_array($curr_deck, $master_list)) {
    $determiner = "mastered-card";
    $is_mastered = true;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['card-delete-btn'])) {
        deleteCard($curr_deck, $_POST['card-delete-id'], $num_cards);
        $cards_list = getDeckCards($curr_deck);
        $num_cards = getDeckSize($curr_deck)['size'];
    }
    if (!empty($_POST['update-deck-btn'])) {
        updateDeckInfo($curr_deck, $_POST['new-deck-title'], $_POST['new-deck-description']);
        $curr_deck_title = getDeckTitle($curr_deck);
        $curr_deck_description = getDeckDescription($curr_deck);
    }
    if (!empty($_POST['card-edit-btn'])) {
        fetchCardEdit($_POST['card-edit-id']);
    }
    if (!empty($_POST['add-card-btn'])) {
        addCard($curr_deck, $_POST['new-card-term'], $_POST['new-card-def'], $num_cards);
        $cards_list = getDeckCards($curr_deck);
        $num_cards = getDeckSize($curr_deck)['size'];
        //var_dump($num_cards);
    }
    if (!empty($_POST['card-search-btn'])) {
        $cards_list = searchCards($curr_deck, $_POST['card-search-box']);
        $num_cards = count($cards_list);
    }
    if (!empty($_POST['show-all-btn'])) {
        $cards_list = getDeckCards($curr_deck);
        $num_cards = getDeckSize($curr_deck)['size'];
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

    <title>Edit Deck</title>
    
    <!-- Include CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../static/styles.css">
    <script src="https://kit.fontawesome.com/7a937c8eef.js" crossorigin="anonymous"></script>
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

    <div class="mt-8">
        <div class="w-4/6 mx-auto bg-white shadow-md rounded-lg overflow-hidden">
            <div class="edit-deck-head">
                <?php if ($is_mastered):?>
                    <div class="title-item">
                        <img src="../static/medal.png" alt="Icon" class="w-12 h-12 mr-2">
                    </div>
                <?php endif; ?>
                <div class="title-item">
                    <h2 class="text-3xl font-semibold text-center py-4"><?php echo $curr_deck_title['title']; ?></h2>
                </div>
                <?php if ($is_mastered):?>
                    <div class="title-item">
                        <img src="../static/medal.png" alt="Icon" class="w-12 h-12 ml-2">
                    </div>
                <?php endif; ?>
            </div>
            <h2 class="text-2xl font-semibold text-center py-2">Edit Contents</h2>
            <div class="flex justify-center">
                <div class="p-4">
                    <form action="edit-deck.php" method="post">
                        <div class="mb-4">
                            <input type="text" id="card-search-box" name="card-search-box"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Search by Term">
                        </div>
                        <div class="mb-2 text-center">
                            <div class="grid grid-cols-2 gap-4">
                                <input type="submit" id="card-search-btn" name="card-search-btn" value="Search"
                                    class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                                <input type="submit" id="show-all-btn" name="show-all" value="Show All"
                                    class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="flex justify-center mb-4">
                <ul class="w-full card-list">
                    <?php foreach ($cards_list as $card): ?>
                        <li class="<?php echo $determiner; ?>">
                            <span class="card-icon">
                                <?php if ($is_mastered): ?>
                                    <img src="../static/master-cards.png" alt="Icon" class="w-12 h-12 mr-3">
                                <?php else: ?>
                                    <img src="../static/card.png" alt="Icon" class="w-12 h-12 mr-3">
                                <?php endif; ?>
                            </span>
                            <span class="card-text">
                                <div class="mb-1 text-lg font-semibold card-term">
                                    <p><?php echo $card['term']; ?></p>
                                </div>
                                <div class="mb-1">
                                    <p><?php echo $card['definition']; ?></p>
                                </div>
                            </span>
                            <span class="edit-card">
                                <form action="edit-deck.php" method="post">
                                    <input type="submit" id="card-edit-btn" name="card-edit-btn" value="Edit"
                                        class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                                    <input type="hidden" id="card-edit-id" name="card-edit-id" value="<?php echo $card['card_number']; ?>">
                                </form>
                            </span>
                            <span class="delete-card">
                                <form action="edit-deck.php" method="post">
                                    <input type="submit" id="card-delete-btn" name="card-delete-btn" value="Delete"
                                        class="w-full py-2 px-4 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:bg-red-600">
                                    <input type="hidden" id="card-delete-id" name="card-delete-id" value="<?php echo $card['card_number']; ?>">
                                </form>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="flex justify-center mb-4">
                <h2 class="text-2xl font-semibold text-center py-2">Total: <?php echo $num_cards; ?></h2>
            </div>
            <div class="flex justify-center mb-4">
                <div class="grid grid-cols-2 gap-4">
                    <button id="back-to-home-btn" onclick="window.location.href='../home.php'"
                        class="w-full py-2 px-4 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:bg-red-600">
                        Back
                    </button>
                    <button id="new-card-btn"
                        class="w-full py-2 px-4 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:bg-green-600">
                        New Card
                    </button>
                </div>
            </div>
            <div class="w-full add-card-container hidden-add-card-div" id="add-card-hidden">
                <div class="w-1/2 mb-4">
                    <h2 class="text-2xl font-semibold text-center py-4">Add Card</h2>
                    <div class="p-4">
                        <form action="edit-deck.php" method="post">
                            <div class="mb-4">
                                <label for="new-card-term" class="block text-gray-800 font-semibold mb-2">Term</label>
                                <input type="text" id="new-card-term" name="new-card-term"
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Term" required>
                            </div>
                            <div class="mb-4">
                                <label for="new-card-def" class="block text-gray-800 font-semibold mb-2">Definition</label>
                                <input type="text" id="new-card-def" name="new-card-def"
                                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Definition" required>
                            </div>
                            <div class="mb-4 text-center">
                                <input type="submit" id="add-card-btn" name="add-card-btn" value="Add" 
                                    class="py-2 px-10 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:bg-green-600">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-8">
        <div class="w-2/6 mx-auto bg-white shadow-md rounded-lg overflow-hidden mb-4">
            <h2 class="text-2xl font-semibold text-center py-4">Edit Info</h2>
            <div class="p-4 mx-4">
                <form action="edit-deck.php" method="post">
                    <div class="mb-4">
                        <label for="new-deck-title" class="block text-gray-800 font-semibold mb-2">New Title</label>
                        <input type="text" id="new-deck-title" name="new-deck-title" value="<?php echo $curr_deck_title['title'];?>"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Title" required>
                    </div>
                    <div class="mb-4">
                        <label for="new-deck-description" class="block text-gray-800 font-semibold mb-2">New Description</label>
                        <input type="text" id="new-deck-description" name="new-deck-description" value="<?php echo $curr_deck_description['description'];?>"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Description" required>
                    </div>
                    <div class="mb-4 text-center">
                        <input id="update-deck-btn" name="update-deck-btn" type="submit" value="Update" class="py-2 px-6 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        const addCardRevealer = document.getElementById('new-card-btn');
        const addCardDiv = document.getElementById('add-card-hidden');

        addCardRevealer.addEventListener('click', () => {
            addCardDiv.classList.toggle('hidden-add-card-div');

        });
    </script>
</body>
</html>