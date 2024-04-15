<?php
/*
*   User Account Interactions
*/

// create a user account by adding it to the users database
function addAccount($username, $pass, $pass_confirm) {
    global $db;
    if ($pass === $pass_confirm) {
        $query = "INSERT INTO user_main VALUES (:username, :user_password, :quiz_rating)";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':user_password', $pass);
        $statement->bindValue(':quiz_rating', 0);
        $statement->execute();
        $statement->closeCursor();
    }
}

// log a user in and redirect to their home page
function login($username, $user_password) {
    global $db;
    $query = "SELECT user_password FROM user_main WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();

    if ($user_password === $result['user_password']) {
        session_start();
        $_SESSION['curr_user'] = $username;
        header('Location: home.php');
    }
    else {
        echo "
            <script>
                alert(\"Passwords do not match!\");
            </script>
        ";
    }
}

// change a given user's password
function change_password($curr_user, $curr_pass, $new_pass, $new_pass_conf) {
    global $db;
    $query = "SELECT user_password FROM user_main WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $curr_user);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    //var_dump($result);
    if ($curr_pass === $result['user_password']) {
        if ($new_pass === $new_pass_conf) {
            $query = "UPDATE user_main SET user_password=:user_password WHERE username=:username";
            $statement = $db->prepare($query);
            $statement->bindValue(':username', $curr_user);
            $statement->bindValue(':user_password', $new_pass);
            $statement->execute();
            $statement->closeCursor();
        }
        else {
            echo "
                <script>
                    alert(\"Passwords do not match!\");
                </script>
            ";
        }
    }
    else {
        echo "
            <script>
                alert(\"Incorrect Current Password!\");
            </script>
        ";
    }
}

/*
*   User Nickname Functionality
*/

// get all of a user's nicknames
function getAllNicknames($curr_user) {
    global $db;
    $query = "SELECT nickname FROM user_nicknames WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $curr_user);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
}

// delete a given nickname for a user
function deleteNickname($curr_user, $nickname) {
    global $db;
    $query = "DELETE FROM user_nicknames WHERE username=:username AND nickname=:nickname";
    $statement = $db->prepare($query);
    $statement->bindValue(":nickname", $nickname);
    $statement->bindValue(":username", $curr_user);
    $statement->execute();
    $statement->closeCursor();
}

// add a given nickname for a user
function addNickname($curr_user, $nickname) {
    global $db;
    $query = "INSERT INTO user_nicknames VALUES (:username, :nickname)";
    $statement = $db->prepare($query);
    $statement->bindValue(":nickname", $nickname);
    $statement->bindValue(":username", $curr_user);
    $statement->execute();
    $statement->closeCursor();
}

/*
*   functions to return some stats for the profile tab
*/
function getQuizRating($curr_user) {
    global $db;
    $query = "SELECT quiz_rating FROM user_main WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $curr_user);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result;
}
function getNumDecks($curr_user) {
    global $db;
    $query = "SELECT COUNT(*) FROM deck WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $curr_user);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result;
}
function getNumDecksMastered($curr_user) {
    global $db;
    $query = "SELECT COUNT(*) FROM user_masters WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $curr_user);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result;
}

/*
*   Friend list functionality
*/

// function to get a list of all friends
function getAllFriends($curr_user) {
    global $db;
    $query = "SELECT friend_name FROM user_friend_names WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $curr_user);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
}

// function to get the number of friends a user has
function getNumFriends($curr_user) {
    global $db;
    $query = "SELECT COUNT(*) FROM user_friend_names WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $curr_user);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result;
}

// delete a friend through the friends list
function deleteFriend($curr_user, $friend_to_delete) {
    global $db;
    $query = "DELETE FROM user_friend_names WHERE username=:username AND friend_name=:friend_name";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $curr_user);
    $statement->bindValue(":friend_name", $friend_to_delete);
    $statement->execute();
    $statement->closeCursor();
    $query = "DELETE FROM user_friend_names WHERE username=:username AND friend_name=:friend_name";
    $statement = $db->prepare($query);
    $statement->bindValue(":friend_name", $curr_user);
    $statement->bindValue(":username", $friend_to_delete);
    $statement->execute();
    $statement->closeCursor();
}

/*
*   Functions to handle friend requests
*/

// send a friend request
function sendRequest($recipient, $message, $request_date, $username) {
    global $db;
    $query = "INSERT INTO request (recipient, message, request_date, username) VALUES (:recipient, :message, :request_date, :username)";
    $statement = $db->prepare($query);
    $statement->bindValue(":recipient", $recipient);
    $statement->bindValue(":message", $message);
    $statement->bindValue(":request_date", $request_date);
    $statement->bindValue(":username", $username);
    $statement->execute();
    $statement->closeCursor();
    header('Location: friends.php');
}

// functions to fetch friend requests where user is the sender and recipient
function getIncomingRequests($recipient) {
    global $db;
    $query = "SELECT * FROM request WHERE recipient=:recipient";
    $statement = $db->prepare($query);
    $statement->bindValue(":recipient", $recipient);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
}
function getOutgoingRequests($username) {
    global $db;
    $query = "SELECT * FROM request WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
}
function acceptRequest($sender, $recipient) {
    global $db;
    $query = "INSERT INTO user_friend_names VALUES (:username, :friend_name)";
    $statement = $db->prepare($query);
    $statement->bindValue(":friend_name", $sender);
    $statement->bindValue(":username", $recipient);
    $statement->execute();
    $statement->closeCursor();
    $query = "INSERT INTO user_friend_names VALUES (:username, :friend_name)";
    $statement = $db->prepare($query);
    $statement->bindValue(":friend_name", $recipient);
    $statement->bindValue(":username", $sender);
    $statement->execute();
    $statement->closeCursor();
    $query = "DELETE FROM request WHERE recipient=:recipient AND username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $sender);
    $statement->bindValue(":recipient", $recipient);
    $statement->execute();
    $statement->closeCursor();
}
function rejectRequest($sender, $recipient) {
    global $db;
    $query = "DELETE FROM request WHERE recipient=:recipient AND username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $sender);
    $statement->bindValue(":recipient", $recipient);
    $statement->execute();
    $statement->closeCursor();
}
/*
*   Functions for basic deck functionality
*/
function getAllDecks($username) {
    global $db;
    $query = "SELECT * FROM deck WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
}
function fetchDeckStudy($deck_id) {
    session_start();
    $_SESSION['curr_deck'] = $deck_id;
    header('Location: deck/study.php');
}
function fetchDeckEdit($deck_id) {
    session_start();
    $_SESSION['curr_deck'] = $deck_id;
    header('Location: deck/edit-deck.php');
}
function createDeck($title, $description, $creation_date, $username) {
    global $db;
    $query = "INSERT INTO deck (title, description, mastery_score, creation_date, size, username) VALUES
                (:title, :description, :mastery_score, :creation_date, :size, :username)";
    $statement = $db->prepare($query);
    $statement->bindValue(":title", $title);
    $statement->bindValue(":description", $description);
    $statement->bindValue(":mastery_score", 0);
    $statement->bindValue(":creation_date", $creation_date);
    $statement->bindValue(":size", 0);
    $statement->bindValue(":username", $username);
    $statement->execute();
    $statement->closeCursor();
    header('Location: ../home.php');
}
function deleteDeck($deck_id) {
    global $db;
    $query = "DELETE FROM deck WHERE deck_id=:deck_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->execute();
    $statement->closeCursor();
    $query = "DELETE FROM card WHERE deck_id=:deck_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->execute();
    $statement->closeCursor();
}