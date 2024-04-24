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
$deck_list = getAllDecks($curr_user);
$curr_date = date('Y-m-d');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['create-quiz-btn'])) {
        createQuiz($_POST['quiz-title'], $_POST['deck-select'], $_POST['quiz-size'], $curr_date, $curr_user);
        //formQuestions("1", "1", "1");
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

    <title>Create Quiz</title>
    
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

    <div class="mt-16">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
            <h2 class="text-2xl font-semibold text-center py-4">Create Quiz</h2>
            <div class="p-4">
                <form action="create-quiz.php" method="post">
                    <div class="mb-4">
                        <label for="quiz-title" class="block text-gray-800 font-semibold mb-2">Quiz Title</label>
                        <input type="text" id="quiz-title" name="quiz-title" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Quiz Title" required>
                    </div>
                    <div class="mb-4 create-quiz-opts text-center">
                        <label for="deck-select" class="block text-gray-800 font-semibold mb-2">Select a Deck</label>
                        <select name="deck-select" id="deck-select">
                            <option value="">Please choose a deck</option>
                            <?php foreach ($deck_list as $deck_opt): ?>
                                <option value="<?php echo $deck_opt['title'] ?>"><?php echo $deck_opt['title'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="mb-4 create-quiz-opts text-center">
                        <label for="quiz-size" class="block text-gray-800 font-semibold mb-2">Enter Quiz Size</label>
                        <input type="number" name="quiz-size" id="quiz-size"
                            class="w-1/2 px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" min="1" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <button id="back-btn" onclick="window.location.href='../home.php'" class="w-full py-2 px-4 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:bg-red-600">Back</button>
                        <input id="create-quiz-btn" name="create-quiz-btn" type="submit" value="Create" class="py-2 px-4 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:bg-green-600">
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>