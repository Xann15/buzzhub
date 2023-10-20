<?php
session_start();
require_once "../../init.php";

$user = $_POST['userId'];
$people = $_POST['peopleId'];
$message = htmlspecialchars($_POST['message']);
$time = date("Y:m:d H:i:s");

if ($message == '') {
    exit;
}

$db->query("INSERT INTO direct_message(user_id, people_id, message, time) VALUES('$user', '$people', '$message', '$time')");
$isDirect = $db->query("SELECT * FROM list_direct_message WHERE user_id = '$user' AND people_id = '$people'");

// set last message & time for displaying at list chat
$db->query("UPDATE list_direct_message SET last_message = '$message' WHERE user_id = '$user' AND people_id = '$people'");
$db->query("UPDATE list_direct_message SET last_message = '$message' WHERE user_id = '$people' AND people_id = '$user'");

$db->query("UPDATE list_direct_message SET time = '$time' WHERE user_id = '$user' AND people_id = '$people'");
$db->query("UPDATE list_direct_message SET time = '$time' WHERE user_id = '$people' AND people_id = '$user'");

if (mysqli_num_rows($isDirect) > 0) {
    exit;
} else {
    $db->query("INSERT INTO list_direct_message(user_id, people_id, last_message, time) VALUES('$user', '$people', '$message', '$time')");
}
