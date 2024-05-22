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
$quiz_score = $quiz_info['score'];
$is_perfect = false;
if ($quiz_score == "100") {
    $is_perfect = true;
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

    <title>Review Quiz</title>
    
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
            <h2 class="text-3xl font-semibold text-center py-4">Review Quiz: <?php echo $quiz_title; ?></h2>
            <div class="review-quiz-head">
                <?php if ($is_perfect): ?>
                    <div class="title-item">
                        <span class="ribbon-icon mr-2">
                            <i class="fa-solid fa-ribbon fa-xl"></i>
                        </span>
                    </div>
                <?php endif; ?>
                <div class="title-item">
                    <h2 class="text-2xl font-semibold text-center py-4">Score: <?php echo $quiz_score; ?></h2>
                </div>
                <?php if ($is_perfect): ?>
                    <div class="title-item">
                        <span class="ribbon-icon ml-2">
                            <i class="fa-solid fa-ribbon fa-xl"></i>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="flex justify-center mb-4">
                <ul class="w-full question-list">
                    <?php foreach ($question_list as $question): ?>
                        <?php
                            $determiner = "";
                            if ($question["q_answer"] != $question["u_answer"]) {
                                $determiner = "q-wrong";
                            }
                        ?>
                        <li class="<?php echo $determiner; ?>">
                            <span class="question-text">
                                <div class="mb-1 text-lg font-semibold question-prompt">
                                    <p><?php echo $question['q_number'] ?>: <?php echo $question['q_prompt'] ?></p>
                                </div>
                                <div class="mb-1">
                                    <div class="user-answer">
                                        <p>My Answer: <?php echo $question['u_answer'] ?></p>
                                    </div>
                                    <div class="question-answer">
                                        <p>Correct Answer: <?php echo $question['q_answer'] ?></p>
                                    </div>
                                </div>
                            </span>
                            <span class="question-icon">
                                <?php if ($determiner === "q-wrong"): ?>
                                    <span class="x-icon">
                                        <i class="fa-solid fa-x fa-xl"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="check-icon">
                                        <i class="fa-solid fa-check fa-xl"></i>
                                    </span>
                                <?php endif; ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="flex justify-center mb-4">
                <button id="back-to-quizzes-btn" onclick="window.location.href='quizzes.php'"
                    class="py-2 px-6 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:bg-red-600">
                    Back
                </button>
            </div>
        </div>
    </div>
</body>
</html>