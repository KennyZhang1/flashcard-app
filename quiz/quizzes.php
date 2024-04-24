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
$complete_quizzes = getCompleteQuizzes($curr_user);
$num_complete = count($complete_quizzes);
$empty_quizzes = getEmptyQuizzes($curr_user);
$num_empty = count($empty_quizzes);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['delete-cquiz-btn'])) {
        deleteQuiz($_POST['delete-cquiz-id']);
        $complete_quizzes = getCompleteQuizzes($curr_user);
        $num_complete = count($complete_quizzes);
    }
    if (!empty($_POST['delete-equiz-btn'])) {
        deleteQuiz($_POST['delete-equiz-id']);
        $empty_quizzes = getEmptyQuizzes($curr_user);
        $num_empty = count($empty_quizzes);
    }
    if (!empty($_POST['review-quiz-btn'])) {
        fetchReviewQuiz($_POST['review-quiz-id']);
    }
    if (!empty($_POST['do-quiz-btn'])) {
        fetchCompleteQuiz($_POST['do-quiz-id']);
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

    <title>Quizzes</title>
    
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
            <h2 class="text-3xl font-semibold text-center py-4">My Quizzes</h2>
            <h2 class="text-2xl font-semibold text-center py-4">Completed: <?php echo $num_complete; ?></h2>
            <div class="flex justify-center mb-4">
                <ul class="w-full complete-quiz-list">
                    <?php foreach ($complete_quizzes as $c_quiz): ?>
                        <li>
                            <span class="quiz-icon">
                                <img src="../static/quiz.png" alt="Icon" class="w-12 h-12 mr-3">
                            </span>
                            <span class="quiz-text">
                                <div class="mb-1 text-lg font-semibold quiz-title">
                                    <p><?php echo $c_quiz['q_title']; ?></p>
                                </div>
                                <div class="mb-1">
                                    <div class="quiz-desc">
                                        <p>Number of questions: <?php echo $c_quiz['length']; ?></p>
                                    </div>
                                    <div class="quiz-date">
                                        <p><?php echo $c_quiz['quiz_date']; ?></p>
                                    </div>
                                </div>
                            </span>
                            <span class="quiz-score">
                                <p><?php 
                                    $q_score = $c_quiz['score'];
                                    echo $q_score;
                                ?></p>
                            </span>
                            <span class="score-icon">
                                <?php if ($q_score === 100.0): ?>
                                    <span class="ribbon-icon">
                                        <i class="fa-solid fa-ribbon fa-xl"></i>
                                    </span>
                                <?php elseif ($q_score >= 64.0): ?>
                                    <span class="check-icon">
                                        <i class="fa-solid fa-check fa-xl"></i>
                                    </span>
                                <?php else: ?>
                                    <span class="x-icon">
                                        <i class="fa-solid fa-x fa-xl"></i>
                                    </span>
                                <?php endif; ?>
                            </span>
                            <span class="review-cquiz">
                                <form action="quizzes.php" method="post">
                                    <input type="submit" id="review-quiz-btn" name="review-quiz-btn" value="Review"
                                        class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                                    <input type="hidden" id="review-quiz-id" name="review-quiz-id" value="<?php echo $c_quiz['quiz_id']; ?>">
                                </form>
                            </span>
                            <span class="delete-cquiz">
                                <form action="quizzes.php" method="post" onsubmit="return confirm('Are you sure you want to delete this quiz?');">
                                    <input type="submit" id="delete-cquiz-btn" name="delete-cquiz-btn" value="Delete"
                                        class="w-full py-2 px-4 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:bg-red-600">
                                    <input type="hidden" id="delete-cquiz-id" name="delete-cquiz-id" value="<?php echo $c_quiz['quiz_id']; ?>">
                                </form>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <h2 class="text-2xl font-semibold text-center py-4">To-do: <?php echo $num_empty; ?></h2>
            <div class="flex justify-center mb-4">
                <ul class="w-full empty-quiz-list">
                    <?php foreach ($empty_quizzes as $e_quiz): ?>
                        <li>
                            <span class="quiz-icon">
                                <img src="../static/quiz.png" alt="Icon" class="w-12 h-12 mr-3">
                            </span>
                            <span class="quiz-text">
                                <div class="mb-1 text-lg font-semibold quiz-title">
                                    <p><?php echo $e_quiz['q_title']; ?></p>
                                </div>
                                <div class="mb-1">
                                    <div class="quiz-desc">
                                        <p>Number of questions: <?php echo $e_quiz['length']; ?></p>
                                    </div>
                                    <div class="quiz-date">
                                        <p><?php echo $e_quiz['quiz_date']; ?></p>
                                    </div>
                                </div>
                            </span>
                            <span class="quiz-score">
                                <p>N/A</p>
                            </span>
                            <span class="score-icon">
                                <i class="fa-solid fa-question fa-xl"></i>
                            </span>
                            <span class="do-equiz">
                                <form action="quizzes.php" method="post">
                                    <input type="submit" id="do-quiz-btn" name="do-quiz-btn" value="Finish"
                                        class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                                    <input type="hidden" id="do-quiz-id" name="do-quiz-id" value="<?php echo $e_quiz['quiz_id']; ?>">
                                </form>
                            </span>
                            <span class="delete-equiz">
                                <form action="quizzes.php" method="post" onsubmit="return confirm('Are you sure you want to delete this quiz?');">
                                    <input type="submit" id="delete-equiz-btn" name="delete-equiz-btn" value="Delete"
                                        class="w-full py-2 px-4 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:bg-red-600">
                                    <input type="hidden" id="delete-equiz-id" name="delete-equiz-id" value="<?php echo $e_quiz['quiz_id']; ?>">
                                </form>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="flex justify-center mb-4">
                <button id="back-to-home-btn" onclick="window.location.href='../home.php'"
                    class="py-2 px-6 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:bg-red-600">
                    Back
                </button>
            </div>
        </div>
    </div>
</body>
</html>