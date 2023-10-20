<?php

require_once '../../init.php';

if(isset($_POST['skip'])) {
    setcookie('chooseAlgorithm', time() - 86400);
    header('location: ' . BASEURL);   
}

if(isset($_POST['select']))
{
    // $uuid = $_SESSION['myapp_login'];
    // $algo = $_POST['VALUE_ALGO'];
    // $db->query("UPDATE users SET algo = '$algo' WHERE user_id = '$uuid'");

    setcookie('chooseAlgorithm', time() - 86400);
    header('location: ' . BASEURL);

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link href="<?= BASEURL; ?>/assets/img/favicon.png" rel="icon">
    <title><?= TITLE_SITE ?></title>
</head>
    <body>
    pick algorithm

    -music
    -fun
    -game
    -girls
    -fitness
    -business
    -cooking
    -dance

    <form action="" method="post">
        <input type="submit" name="skip" value="> skip">
        <input type="submit" name="select" value="select">
    </form>
</body>
</html>