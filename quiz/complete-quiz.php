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
$curr_quiz = $_SESSION['curr_quiz'];
$question_list = getQuizQuestions($curr_quiz);
$quiz_info = getQuizInfo($curr_quiz);
$quiz_title = $quiz_info['q_title'];
$quiz_length = $quiz_info['length'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['submit-quiz-btn'])) {
        //var_dump($_POST['u-ans']);
        submitQuiz($curr_user, $curr_quiz, $question_list, $_POST['u-ans']);
        //updateMasterTable($curr_user, "1", 113);
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

    <title>Complete Quiz</title>
    
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
        <div class="w-1/2 mx-auto bg-white shadow-md rounded-lg overflow-hidden">
            <h2 class="text-3xl font-semibold text-center py-4"><?php echo $quiz_title; ?></h2>
            <h2 class="text-2xl font-semibold text-center py-4">Questions: <?php echo $quiz_length; ?></h2>
            <div class="p-4 w-full">
                <form action="complete-quiz.php" method="post">
                    <?php foreach ($question_list as $question): ?>
                        <div class="mb-6">
                            <label for="u-ans[]" class="block text-gray-800 font-semibold mb-2">
                                <?php echo $question['q_number']; ?>: <?php echo $question['q_prompt']; ?></label>
                            <input type="text" id="u-ans[]" name="u-ans[]"
                                class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" placeholder="Enter Term" required>
                        </div>
                    <?php endforeach; ?>
                    <div class="grid grid-cols-2 gap-4">
                        <button id="back-btn" onclick="window.location.href='quizzes.php'" class="py-2 px-4 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:bg-red-600">Back</button>
                        <input id="submit-quiz-btn" name="submit-quiz-btn" type="submit" value="Submit" class="py-2 px-4 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:bg-green-600">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>