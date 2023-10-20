<?php
require '../init.php';
session_start();
$time = $_COOKIE['notification'];
$numberofNewNotification = getNotification($time);
?>
<span id="notification">
    <?= $numberofNewNotification ?>
    <span class="visually-hidden">unread messages</span>
</span>