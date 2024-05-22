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
$num_friends = getNumFriends($curr_user);
$friends_list = getAllFriends($curr_user);
$incoming_requests = getIncomingRequests($curr_user);
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    if (!empty($_POST['deleteFriendBtn'])) {
        deleteFriend($curr_user, $_POST['delete-friend']);
        $friends_list = getAllFriends($curr_user);
        $num_friends = getNumFriends($curr_user);
    }
    if (!empty($_POST['f-accept-btn'])) {
        acceptRequest($_POST['f-accept-name'], $curr_user);
        $friends_list = getAllFriends($curr_user);
        $incoming_requests = getIncomingRequests($curr_user);
        $num_friends = getNumFriends($curr_user);
    }
    if (!empty($_POST['f-reject-btn'])) {
        rejectRequest($_POST['f-reject-name'], $curr_user);
        $friends_list = getAllFriends($curr_user);
        $incoming_requests = getIncomingRequests($curr_user);
        $num_friends = getNumFriends($curr_user);
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

    <title>Friends</title>
    
    <!-- Include CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="static/styles.css">
    <script src="https://kit.fontawesome.com/7a937c8eef.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100">
    <header>
        <nav>
            <div class="topnav">
                <a href="home.php">Home</a>
                <a href="profile.php">Profile</a>
                <a class="active" href="friends.php">Friends</a>
                <a href="account.php">Account</a>
                <a style="float:right" class="logout" href="login.php">Logout</a>
            </div>
        </nav>
    </header>
    <div class="mt-8">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg overflow-hidden">
            <div class="flex justify-center">
                <span class="mx-4 mb-4 flex items-center" id="flist-header">
                    <img src="static/profile.png" alt="Icon" class="w-8 h-8 mr-3">
                    <p class="text-2xl font-bold mr-2"><?php echo $num_friends['COUNT(*)']; ?></p>
                    <h2 class="text-2xl font-semibold text-center py-4">Friends</h2>
                </span>
            </div>
            <div class="flex justify-center mb-4">
                <ul class="friend-list">
                    <?php foreach ($friends_list as $friend_name): ?>
                        <li>
                            <span class="fname"><?php echo $friend_name['friend_name']; ?></span>
                            <span class="frating">
                                <?php 
                                    $frating = getQuizRating($friend_name['friend_name']);
                                    echo $frating['quiz_rating'];
                                ?>
                            </span>
                            <span class="fbadge"><i class="fa-solid fa-trophy"></i></span>
                            <span class="fremove">
                                <form method="post" action="friends.php" onsubmit="return confirm('Are you sure you want to remove this friend?');">
                                    <input type="submit" id="deleteFriendBtn" name="deleteFriendBtn" value="X"
                                        class="py-0.8 px-2 bg-red-600 text-white font-semibold rounded-full hover:bg-red-700 focus:outline-none focus:bg-red-700">
                                    <input type="hidden" id="delete-friend" name="delete-friend"
                                        value="<?php echo $friend_name['friend_name']; ?>">
                                </form>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="flex justify-center">
                    <button id="add-friend-btn" onclick="window.location.href='add-friend.php'"
                        class="py-2 px-4 mb-4 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:bg-green-600">
                            Add Friend
                    </button>
            </div>
        </div>
    </div>

    <div class="mt-8">
        <div class="w-1/2 mx-auto bg-white shadow-md rounded-lg overflow-hidden">
            <h2 class="text-2xl font-semibold text-center py-4">Requests</h2>
            <div class="flex justify-center mb-4">
                <ul class="w-full requests-list">
                    <?php foreach ($incoming_requests as $incoming_req): ?>
                        <li>
                            <span class="inc-req-pic">
                                <img src="static/profile.png" alt="Icon" class="w-12 h-12 mr-6">
                            </span>
                            <span class="inc-req-text">
                                <div class="mb-1 text-lg font-semibold inc-req-sender">
                                    <p>From: <?php echo $incoming_req['username']; ?></p>
                                </div>
                                <div class="mb-1">
                                    <div class="inc-req-msg">
                                        <p><?php echo $incoming_req['message']; ?></p>
                                    </div>
                                    <div class="inc-req-date">
                                        <p><?php echo $incoming_req['request_date']; ?></p>
                                    </div>
                                </div>
                            </span>
                            <span class="inc-req-accept">
                                <form action="friends.php" method="post">
                                    <input type="submit" id="f-accept-btn" name="f-accept-btn" value="Accept"
                                        class="w-full py-2 px-4 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 focus:outline-none focus:bg-green-600">
                                    <input type="hidden" id="f-accept-name" name="f-accept-name" value="<?php echo $incoming_req['username']; ?>">
                                </form>
                            </span>
                            <span class="inc-req-reject">
                                <form action="friends.php" method="post">
                                    <input type="submit" id="f-reject-btn" name="f-reject-btn" value="Decline"
                                        class="w-full py-2 px-4 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 focus:outline-none focus:bg-red-600">
                                    <input type="hidden" id="f-reject-name" name="f-reject-name" value="<?php echo $incoming_req['username']; ?>">
                                </form>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>              
    </div>
</body>
</html>