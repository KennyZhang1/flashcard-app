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
    header('Location: login.php');
}

// log a user in and redirect to their home page
function login($username, $user_password) {
    global $db;
    if(!checkUsername($username)) {
        header('Location: create-account.php');
    }
    else {
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
}

// function to check if username exists in database
function checkUsername($username) {
    global $db;
    $query = "SELECT COUNT(*) FROM user_main WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    if ($result['COUNT(*)'] === 0) {
        return false;
    }
    else {
        return true;
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
    if (checkUsername($recipient)) {
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
    else {
        echo "
            <script>
                alert(\"Username does not exist!\");
            </script>
        ";
    }

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
    // First check to see if deck title already exists
    $query = "SELECT deck_id FROM deck WHERE title=:title";
    $statement = $db->prepare($query);
    $statement->bindValue(":title", $title);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    if (count($result) != 0) {
        echo "
                <script>
                    alert(\"Deck Title Already Taken!\");
                </script>
            ";
    }
    // Insert into deck table
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
function deleteDeck($deck_id, $username) {
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
    $query = "DELETE FROM tests WHERE deck_id=:deck_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->execute();
    $statement->closeCursor();
    $query = "DELETE FROM user_masters WHERE deck_id=:deck_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->execute();
    $statement->closeCursor();
    // update user's quiz rating
    updateQuizRating($username);

}
/*
*   Functions for deck/card editing/creation
*/
function getDeckTitle($deck_id) {
    global $db;
    $query = "SELECT title FROM deck WHERE deck_id=:deck_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result;
}
function getDeckDescription($deck_id) {
    global $db;
    $query = "SELECT description FROM deck WHERE deck_id=:deck_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result;
}
function getDeckSize($deck_id) {
    global $db;
    $query = "SELECT size FROM deck WHERE deck_id=:deck_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result;
}
function getDeckCards($deck_id) {
    global $db;
    $query = "SELECT * FROM card WHERE deck_id=:deck_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
}
function getMaxCNum($deck_id) {
    global $db;
    $query = "SELECT MAX(card_number) FROM card WHERE deck_id=:deck_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result;
}
function deleteCard($deck_id, $card_number) {
    global $db;
    $query = "DELETE FROM card WHERE deck_id=:deck_id AND card_number=:card_number";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->bindValue(":card_number", $card_number);
    $statement->execute();
    $statement->closeCursor();
    $query = "UPDATE deck SET size=size-1 WHERE deck_id=:deck_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->execute();
    $statement->closeCursor();
}
function updateDeckInfo($deck_id, $title, $description) {
    global $db;
    $query = "UPDATE deck SET title=:title, description=:description WHERE deck_id=:deck_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->bindValue(":title", $title);
    $statement->bindValue(":description", $description);
    $statement->execute();
    $statement->closeCursor();
}
function fetchCardEdit($card_number) {
    session_start();
    $_SESSION['curr_card'] = $card_number;
    header('Location: edit-card.php');
}
function getCurrCardInfo($deck_id, $card_number) {
    global $db;
    $query = "SELECT term, definition FROM card WHERE deck_id=:deck_id AND card_number=:card_number";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->bindValue(":card_number", $card_number);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result;
}
function updateCard($deck_id, $card_number, $term, $definition) {
    global $db;
    $query = "UPDATE card SET term=:term, definition=:definition WHERE deck_id=:deck_id AND card_number=:card_number";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->bindValue(":card_number", $card_number);
    $statement->bindValue(":term", $term);
    $statement->bindValue(":definition", $definition);
    $statement->execute();
    $statement->closeCursor();
    header('Location: edit-deck.php');
}
function returnToDeckEdit() {
    header('Location: edit-deck.php');
}
function addCard($deck_id, $term, $definition, $max_cnum) {
    global $db;
    $card_number = $max_cnum + 1;
    $query = "INSERT INTO card VALUES (:deck_id, :card_number, :term, :definition)";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->bindValue(":card_number", $card_number);
    $statement->bindValue(":term", $term);
    $statement->bindValue(":definition", $definition);
    $statement->execute();
    $statement->closeCursor();
    $query = "UPDATE deck SET size=size+1 WHERE deck_id=:deck_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->execute();
    $statement->closeCursor();
}
function searchCards($deck_id, $token) {
    global $db;
    $pattern = $token . "%";
    $query = "SELECT * FROM card WHERE deck_id=:deck_id AND term LIKE :pattern";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->bindValue(":pattern", $pattern);
    $statement->execute();
    $result = $statement->fetchAll();
    return $result;
}

// return a list of ids of all mastered decks for a user
function getMasteredDecks($username) {
    global $db;
    $query = "SELECT deck_id FROM user_masters WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    $master_list = array();
    foreach ($result as $row) {
        array_push($master_list, $row["deck_id"]);
    }
    return $master_list;
}

/*
*   Functions to handle basic quiz display and creation
*/
function getCompleteQuizzes($username) {
    global $db;
    $query = "SELECT * FROM quiz WHERE username=:username AND score IS NOT NULL";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
}
function getEmptyQuizzes($username) {
    global $db;
    $query = "SELECT * FROM quiz WHERE username=:username AND score IS NULL";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
}
/*
function getQuizTitle($quiz_id) {
    global $db;
    $query = "SELECT deck_id FROM tests WHERE quiz_id=:quiz_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":quiz_id", $quiz_id);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    $deck_id = $result["deck_id"];
    $query = "SELECT title FROM deck WHERE deck_id=:deck_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result['title'];
}
*/
function deleteQuiz($quiz_id) {
    global $db;
    $query = "DELETE FROM quiz WHERE quiz_id=:quiz_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":quiz_id", $quiz_id);
    $statement->execute();
    $statement->closeCursor();
    $query = "DELETE FROM question WHERE quiz_id=:quiz_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":quiz_id", $quiz_id);
    $statement->execute();
    $statement->closeCursor();
    $query = "DELETE FROM tests WHERE quiz_id=:quiz_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":quiz_id", $quiz_id);
    $statement->execute();
    $statement->closeCursor();
}

// function to create a quiz, create necessary database entries, and create questions
function createQuiz($q_title, $deck_title, $length, $quiz_date, $username) {
    global $db;
    // first, ensure that the quiz title is unique
    $query = "SELECT quiz_id FROM quiz WHERE q_title=:q_title";
    $statement = $db->prepare($query);
    $statement->bindValue(":q_title", $q_title);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    if (count($result) != 0) {
        //header("Location: create-quiz.php");
        echo "
                <script>
                    alert(\"Quiz Title Already Taken!\");
                </script>
            ";
    }
    else {
        // get the id and size of the target deck
        $query = "SELECT deck_id, size FROM deck WHERE title=:title AND username=:username";
        $statement = $db->prepare($query);
        $statement->bindValue(":title", $deck_title);
        $statement->bindValue(":username", $username);
        $statement->execute();
        $target_deck = $statement->fetch();
        $target_deck_id = $target_deck["deck_id"];
        $target_deck_size = $target_deck["size"];
        $statement->closeCursor();
        // make sure quiz length is within the bounds of the deck size
        if ($length > $target_deck_size) {
            //header("Location: create-quiz.php");
            echo "
                <script>
                    alert(\"Too many questions!\");
                </script>
            ";
        }
        else {
            // create database entry for quiz
            $query = "INSERT INTO quiz (quiz_date, length, score, username, q_title)
                VALUES (:quiz_date, :length, :score, :username, :q_title)";
            $statement = $db->prepare($query);
            $statement->bindValue(":quiz_date", $quiz_date);
            $statement->bindValue(":length", $length);
            $statement->bindValue(":score", NULL);
            $statement->bindValue(":username", $username);
            $statement->bindValue(":q_title", $q_title);
            $statement->execute();
            $statement->closeCursor();
            // fetch the newly inserted quiz_id
            $query = "SELECT quiz_id FROM quiz WHERE username=:username AND q_title=:q_title";
            $statement = $db->prepare($query);
            $statement->bindValue(":q_title", $q_title);
            $statement->bindValue(":username", $username);
            $statement->execute();
            $new_quiz_id = $statement->fetch()["quiz_id"];
            $statement->closeCursor();
            // insert into the tests table
            $query = "INSERT INTO tests VALUES (:quiz_id, :deck_id)";
            $statement = $db->prepare($query);
            $statement->bindValue(":quiz_id", $new_quiz_id);
            $statement->bindValue(":deck_id", $target_deck_id);
            $statement->execute();
            $statement->closeCursor();
            // call helper function to form questions
            formQuestions($target_deck_id, $new_quiz_id, $length);
            // finally, send user to quizzes page
            header('Location: quizzes.php');
        }
    }
}
// function to randomly generate a set of questions
function formQuestions($deck_id, $quiz_id, $length) {
    global $db;
    // fetch all cards associated with a deck
    $query = "SELECT * FROM card WHERE deck_id=:deck_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->execute();
    $card_pool = $statement->fetchAll();
    $card_pool_size = count($card_pool);
    $statement->closeCursor();
    // loop from 1 to length: generate a random index and fetch a card to form a question
    $used_cnums = array();
    for ($q_number = 1; $q_number <= $length; $q_number++) {
        $rand_idx = 0;
        $unique_flag = false;
        while (!$unique_flag) {
            $rand_idx = rand(0, $card_pool_size-1);
            if (!in_array($rand_idx, $used_cnums)) {
                $unique_flag = true;
            }
        }
        $query = "INSERT INTO question VALUES (:q_number, :quiz_id, :q_prompt, :q_answer, :u_answer)";
        $statement = $db->prepare($query);
        $statement->bindValue(":q_number", $q_number);
        $statement->bindValue(":quiz_id", $quiz_id);
        $statement->bindValue(":q_prompt", $card_pool[$rand_idx]["definition"]);
        $statement->bindValue(":q_answer", $card_pool[$rand_idx]["term"]);
        $statement->bindValue(":u_answer", NULL);
        $statement->execute();
        $statement->closeCursor();
        $used_cnums[] = $rand_idx;
    }
}

/*
*   Functions to handle quiz display, comletion, and grading
*/ 
function fetchReviewQuiz($quiz_id) {
    session_start();
    $_SESSION['curr_quiz'] = $quiz_id;
    header('Location: review-quiz.php');
}
function getQuizQuestions($quiz_id) {
    global $db;
    $query = 'SELECT * FROM question WHERE quiz_id=:quiz_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':quiz_id', $quiz_id);
    $statement->execute();
    $result = $statement->fetchAll();
    $statement->closeCursor();
    return $result;
}
function getQuizInfo($quiz_id) {
    global $db;
    $query = 'SELECT * FROM quiz WHERE quiz_id=:quiz_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':quiz_id', $quiz_id);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    return $result;
}
function fetchCompleteQuiz($quiz_id) {
    session_start();
    $_SESSION['curr_quiz'] = $quiz_id;
    header('Location: complete-quiz.php');
}
function submitQuiz($username, $quiz_id, $question_list, $u_answer_arr) {
    global $db;
    // add u_answers to question table and grade quiz
    $num_correct = 0;
    for ($i = 0; $i < count($u_answer_arr); $i++) {
        $q_number = $i + 1;
        $u_answer = $u_answer_arr[$i];
        // insert u_answer
        $query = "UPDATE question SET u_answer=:u_answer WHERE quiz_id=:quiz_id AND q_number=:q_number";
        $statement = $db->prepare($query);
        $statement->bindValue(":quiz_id", $quiz_id);
        $statement->bindValue(":q_number", $q_number);
        $statement->bindValue(":u_answer", $u_answer);
        $statement->execute();
        $statement->closeCursor();
        if ($u_answer == $question_list[$i]['q_answer']) {
            $num_correct++;
        }
    }
    $ratio = $num_correct / count($u_answer_arr);
    $score = round($ratio * 100, 0);
    // update the quiz score
    $query = 'UPDATE quiz SET score=:score WHERE quiz_id=:quiz_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':quiz_id', $quiz_id);
    $statement->bindValue(':score', $score);
    $statement->execute();
    $statement->closeCursor();
    // update the mastery score of the corresponding deck
    // condition 1: perfect quiz score and length of quiz >= 10
    if (($score == 100) && count($u_answer_arr) >= 10) {
        $query = 'SELECT deck_id FROM tests WHERE quiz_id=:quiz_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':quiz_id', $quiz_id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        // condition 2: the target deck has not been deleted
        if (count($result) != 0) {
            $target_deck_id = $result['deck_id'];
            // get the target deck info
            $query = "SELECT * FROM deck WHERE deck_id=:deck_id";
            $statement = $db->prepare($query);
            $statement->bindValue(":deck_id", $target_deck_id);
            $statement->execute();
            $target_deck_info = $statement->fetch();
            $statement->closeCursor();
            // calculate new deck mastery
            $deck_size = $target_deck_info["size"] + 0.0;
            $curr_mastery = $target_deck_info["mastery_score"];
            $length = count($u_answer_arr) + 0.0;
            $factor = ($length / $deck_size) + max(0.25, 0.01*$length);
            $new_mastery = (int) round($curr_mastery + 0.5*($deck_size + $length)*max(1, $factor), 0);
            // set new deck mastery
            $query = "UPDATE deck SET mastery_score=:mastery_score WHERE deck_id=:deck_id";
            $statement = $db->prepare($query);
            $statement->bindValue(":mastery_score", $new_mastery);
            $statement->bindValue(":deck_id", $target_deck_id);
            $statement->execute();
            $statement->closeCursor();
            // update master table and update quiz rating
            updateMasterTable($username, $target_deck_id, $new_mastery);
            updateQuizRating($username);
        }
    }
    // return user to quiz page
    header("Location: quizzes.php");
}
function updateMasterTable($username, $deck_id, $mastery_score) {
    global $db;
    $query = "SELECT COUNT(*) FROM user_masters WHERE username=:username AND deck_id=:deck_id";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->bindValue(":deck_id", $deck_id);
    $statement->execute();
    $result = $statement->fetch();
    $statement->closeCursor();
    if (($result['COUNT(*)'] == 0) && ($mastery_score >= 100)) {
        $query = 'INSERT INTO user_masters VALUES (:deck_id, :username)';
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':deck_id', $deck_id);
        $statement->execute();
        $statement->closeCursor();
    }
}
function updateQuizRating($username) {
    global $db;
    $query = "SELECT SUM(mastery_score) AS mastery_sum FROM deck WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->execute();
    $mastery_sum = $statement->fetch()['mastery_sum'];
    $statement->closeCursor();
    $query = 'SELECT COUNT(deck_id) FROM user_masters WHERE username=:username';
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $num_mastered = $statement->fetch()['COUNT(deck_id)'];
    $new_quiz_rating = $mastery_sum + 100*$num_mastered;
    $query = 'UPDATE user_main SET quiz_rating=:quiz_rating WHERE username=:username';
    $statement = $db->prepare($query);
    $statement->bindValue(':quiz_rating', $new_quiz_rating);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $statement->closeCursor();
}