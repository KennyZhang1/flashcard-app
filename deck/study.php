<?php
require("../backend/connect-db.php");
require("../backend/backend-functs.php");
?>

<!-- Fetch values for script -->
<?php
session_start();
if (!isset($_SESSION["curr_user"])) {
    header('Location: ../login.php');
}
$curr_user = $_SESSION['curr_user'];
$curr_deck = $_SESSION['curr_deck'];
$curr_deck_title = getDeckTitle($curr_deck);
$cards_list = getDeckCards($curr_deck);
$master_list = getMasteredDecks($curr_user);
if (in_array($curr_deck, $master_list)) {
    $is_mastered = true;
}
else {
    $is_mastered = false;
}
$num_cards = getDeckSize($curr_deck)['size'];
//var_dump($cards_list[0]);
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
            <div class="study-deck-head">
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
            <h2 class="text-2xl font-semibold text-center py-2">Study Deck</h2>
            <div class="card-scroll-container w-full">
                <div class="mt-8">
                    <div class="flex justify-center">
                        <h2 class="text-xl font-semibold text-center py-2">Number of cards: <?php echo $num_cards; ?></h2>
                    </div>
                    <div class="outer-container">
                        <div class="centered-content">
                            <div class="scroll-item prev-button-container mr-4">
                                <button id="prev-card-btn" class="prev-button">
                                    <i class="fa-solid fa-arrow-left fa-2xl scroll-btn"></i>
                                </button>
                            </div>
                            <div class="scroll-item center-card-container">
                                <div class="flex justify-center items-center">
                                    <?php if ($is_mastered): ?>
                                        <div class="rounded-xl shadow-md bg-purple-300 p-4 w-98 h-66 content-center">
                                            <div class="rounded-lg shadow-md bg-purple-200 p-6 w-96 h-64 content-center">
                                                <h2 class="text-xl font-semibold text-center" id="core-text"></h2>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="rounded-xl shadow-md bg-blue-300 p-4 w-98 h-66 content-center">
                                            <div class="rounded-lg shadow-md bg-blue-200 p-6 w-96 h-64 content-center">
                                                <h2 class="text-xl font-semibold text-center" id="core-text"></h2>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="scroll-item next-button-container ml-4">
                                <button id="next-card-btn" class="next-button">
                                    <i class="fa-solid fa-arrow-right fa-2xl scroll-btn"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full rotate-btn-container">
                <div class="mt-4 mb-4">
                    <div class="flex justify-center items-center">
                        <button id="flip-btn" class="flip-button">
                            <i class="fa-solid fa-rotate fa-2xl"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="flex justify-center mt-8 mb-4">
                <button id="back-to-home-btn" onclick="window.location.href='../home.php'"
                    class="py-2 px-6 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:bg-red-600">
                    Back
                </button>
            </div>
        </div>
    </div>
    <script>
        let card_idx = 0;
        let txt_display = "term";
        const num_cards = <?php echo json_encode($num_cards) ?>;
        const max_idx = num_cards - 1;
        const cardArr = <?php echo json_encode($cards_list) ?>;
        const nextButton = document.getElementById('next-card-btn');
        const prevButton = document.getElementById('prev-card-btn');
        const flipButton = document.getElementById('flip-btn');
        console.log(cardArr);
        fillCard(card_idx, txt_display, cardArr);

        nextButton.addEventListener('click', () => {
            if (card_idx === max_idx) {
                card_idx = 0;
            }
            else {
                card_idx++;
            }
            txt_display = "term";
            //console.log(card_idx);
            fillCard(card_idx, txt_display, cardArr);
        });
        
        prevButton.addEventListener('click', () => {
            if (card_idx === 0) {
                card_idx = max_idx;
            }
            else {
                card_idx--;
            }
            //console.log(card_idx);
            txt_display = "term";
            fillCard(card_idx, txt_display, cardArr);
        });
        flipButton.addEventListener('click', () => {
            if (txt_display === "term") {
                txt_display = "definition";
            }
            else {
                txt_display = "term";
            }
            fillCard(card_idx, txt_display, cardArr);
        })

        function fillCard(card_idx, txt_display, cardArr) {
            const card_box = document.getElementById('core-text');
            const cnum = card_idx + 1;
            const cnum_str = cnum.toString();
            if (txt_display === "term") {
                card_box.textContent = cnum_str + ": " + cardArr[card_idx][txt_display];
            }
            else {
                card_box.textContent = cardArr[card_idx][txt_display];
            }
        }

    </script>
</body>
</html>