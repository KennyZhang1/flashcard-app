<?php
require("../backend/connect-db.php");
require("../backend/backend-functs.php");
?>

<?php
session_start();
$curr_user = $_SESSION['curr_user'];
$curr_deck = $_SESSION['curr_deck'];
//var_dump($curr_user);
//var_dump($curr_deck);