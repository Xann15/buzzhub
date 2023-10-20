<?php
session_start();
require_once '../init.php';

if (!isset($_SESSION['myapp_login'])) {
    header('location: ' . BASEURL . '/login');
    exit;
}
$userId = $_SESSION['myapp_login'];
$getProfile = $db->query("SELECT * FROM users WHERE user_id = '$userId'");
$getProfile = mysqli_fetch_assoc($getProfile);
$theme = $getProfile['theme'];

$getListDirectMessage = $db->query("SELECT DISTINCT
                                        CASE WHEN user_id < people_id THEN user_id ELSE people_id END AS user_id,
                                        CASE WHEN user_id < people_id THEN people_id ELSE user_id END AS people_id,
                                        last_message,
                                        time
                                    FROM
                                        list_direct_message
                                    WHERE
                                        user_id = '$userId' OR people_id = '$userId'
                                    ORDER BY
                                        time DESC");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Animate -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Loading Css -->
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/loading.min.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="<?= BASEURL; ?>/assets/js/bootstrap.bundle.min.js"></script>

    <!-- Favicon -->
    <link href="<?= BASEURL; ?>/assets/img/favicon.png" rel="icon">

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <title>Chat | MyApp</title>

</head>

<body class="<?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
    <nav id="nav" class="navbar py-1 fixed-top shadow-sm p-0" style="background: transparent;">
        <div class="container d-flex justify-content-between">
            <a href="javascript:history.back()" class="nav-link my-auto d-flex gap-2">
                <i class="bi bi-arrow-left fs-4 fw-bold"></i>
                <p class="fw-bold my-auto fs-5"><?= $getProfile['username'] ?></p>
            </a>
            <a href="<?= BASEURL ?>/profile">
                <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $getProfile['profile_picture'] ?>" alt="<?= $getProfile['username'] ?>" class="rounded-circle" width="45px">
            </a>
        </div>
    </nav>
    <div class="container-fluid pt-2 mt-5 d-flex p-0">

        <?php if (mysqli_num_rows($getListDirectMessage) < 1) { ?>
            <p class="text-center col-12 mt-5 px-2">
                Your direct messages will appear here, immediately <span class="fw-bold">start a conversation</span> with your fiends.
                <br>
                <span class="text-muted">to start a conversation, you can go to > another profile > click send message</span>
            </p>
        <?php exit;
        } ?>

        <div class="p-2 col-lg-10 col-md-10 col-12 mx-auto" id="bodyListDirect">
            <?php while ($fetchList = mysqli_fetch_assoc($getListDirectMessage)) :
                if ($fetchList['user_id'] == $userId) {
                    $userListId = $fetchList['people_id'];
                } else {
                    $userListId = $fetchList['user_id'];
                }
                $getUserData = $db->query("SELECT * FROM users WHERE user_id = '$userListId'");
                $getUserData = mysqli_fetch_assoc($getUserData);
            ?>
                <a href="<?= BASEURL ?>/chat/t/?direct=<?= $userListId ?>" class="list-direct-message rounded-4 nav-link shadow-sm mb-1 <?= $theme == 'dark' ? 'bg-dark text-white' : '' ?>">
                    <input type="hidden" name="peopleId" id="peopleId" value="<?= $userListId ?>">
                    <div class="room d-flex justify-content-between">
                        <div class="left d-flex gap-2 p-2">
                            <div class="profile">
                                <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $getUserData['profile_picture'] ?>" class="rounded-circle" width="50px" alt="profile picture <?= $getUserData['username'] ?>">
                            </div>
                            <div class="room-desc row">
                                <p class="display-6 fs-6 my-auto mb-0">
                                    <?php
                                    if ($getUserData['name'] != '') {
                                        echo $getUserData['name'];
                                    } else {
                                        echo $getUserData['username'];
                                    }

                                    if ($getUserData['verified'] == 1) {
                                    ?>
                                        <i class="bi bi-patch-check-fill text-info"></i>
                                    <?php } ?>
                                </p>
                                <p class="text-muted my-auto mt-0"><?= maxLength($fetchList['last_message'], 25) ?></p>
                            </div>
                        </div>
                        <div class="time d-flex p-3">
                            <p class="text-muted m-auto" style="font-size: 14px;"><?= time_elapsed_string($fetchList['time']) ?></p>
                        </div>
                    </div>
                </a>
            <?php endwhile ?>
        </div>
    </div>

    <script>
        setInterval(() => {
            $.ajax({
                url: "realTimeListDirect.php",
                method: "POST",
                data: {
                    peopleId: $('#peopleId').val()
                },
                dataType: "text",
                success: function(data) {
                    $('#bodyListDirect').html(data);
                }
            });
        }, 1000);
    </script>
</body>


</html>