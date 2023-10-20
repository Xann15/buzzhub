<?php
session_start();

require_once '../init.php';

!isset($_SESSION['myapp_login']) ? header('Location: ' . BASEURL . '/login') : '';

$currentTime = date("Y:m:d H:i:s");
isset($_COOKIE['notification']) ? setcookie('notification', $currentTime, time() + 31536000, '/myapp', '192.168.1.10') : setcookie('notification', $currentTime, time() + 31536000, '/myapp', '192.168.1.10');
if (isset($_COOKIE['notification'])) {
    $time = $_COOKIE['notification'];
    $numberofNewNotification = totalNotification($time);
}


$uuid = $_SESSION['myapp_login'];
$query = $db->query("SELECT * FROM users WHERE user_id = '$uuid'");
$getData = mysqli_fetch_assoc($query);
$theme = $getData['theme'];

$getNotification = $db->query("SELECT * FROM notification WHERE user_id = '$uuid' ORDER by id DESC");

$y = $db->query("SELECT username FROM users WHERE user_id = '$uuid'");
$x = $y->fetch_assoc();
$name = $x['username'];
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

    <title>Notification | <?= TITLE_SITE ?></title>

</head>

<body class="<?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
    <input type="hidden" id="username" value="<?= $name ?>"></input>
    <input type="hidden" id="totalNotification" value="<?= $numberofNewNotification ?>"></input>


    <div class="d-lg-none d-md-none">
        <nav id="nav" class="navbar fixed-top navbar-expand shadow-sm p-1 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
            <div class="container d-flex justify-content-start gap-4">
                <div class="back mx-1">
                    <a href="javascript:history.back()" class="nav-link my-auto">
                        <i class="bi bi-arrow-left fs-2"></i>
                    </a>
                </div>
                <p class="my-auto fs-4 fw-bold">Notification</p>
            </div>
        </nav>
        <?php require_once '../components/bottom_nav.php' ?>
    </div>

    <div class="d-flex">
        <div class="d-none d-md-flex d-lg-flex col-2">
            <?php include '../components/sidenav.php' ?>
        </div>

        <div class="app px-md-4 py-md-3 p-2 mt-lg-0 mt-md-0 mt-5" style="width: 100vw;">
            <?php if (mysqli_num_rows($getNotification) == 0) { ?>
                <p class="mt-2 fs-6 text-center text-muted">Your notification will appear here.</p>
            <?php } ?>
            <?php
            while ($fetchNotification = mysqli_fetch_assoc($getNotification)) :
                $person_id = $fetchNotification['person_id'];
                $getPerson = $db->query("SELECT * FROM users WHERE user_id = '$person_id'");
                $fetchPerson = mysqli_fetch_assoc($getPerson);

                $post_id = $fetchNotification['post_id'];
                $getPost = $db->query("SELECT type, post FROM posts WHERE id = '$post_id'");
                $fetchPost = mysqli_fetch_assoc($getPost);
            ?>
                <?php if ($fetchNotification['type'] == 'follow') { ?>

                    <div class="mb-3 d-flex gap-1 col-12">
                        <div class="d-flex gap-2 col-10">
                            <div class="iconType col-2">
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchPerson['user_id'] ?>">
                                    <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $fetchPerson['profile_picture'] ?>" alt="profile <?= $fetchPerson['username'] ?>" class="my-auto d-block mx-auto rounded-circle" style="width: 50px; height: 50px;">
                                </a>
                            </div>
                            <div class="content col-9 d-flex">
                                <p class="my-auto">
                                    <span class="fw-bold"><?= $fetchPerson['username'] ?></span>
                                    <?php if ($fetchPerson['verified'] == 1) { ?>
                                        <i class="bi bi-patch-check-fill text-info"></i>
                                    <?php } ?>
                                    started following you. <span class="text-muted"><?= time_elapsed_string($fetchNotification['time']) ?></span>
                                </p>
                            </div>
                        </div>

                    </div>

                <?php } elseif ($fetchNotification['type'] == 'mentionPost') { ?>

                    <div class="mb-3 d-flex gap-1 col-12">
                        <div class="d-flex gap-2 col-10">
                            <div class="iconType col-2">
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchPerson['user_id'] ?>">
                                    <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $fetchPerson['profile_picture'] ?>" alt="profile <?= $fetchPerson['username'] ?>" class="my-auto d-block mx-auto rounded-circle" style="width: 50px; height: 50px;">
                                </a>
                            </div>
                            <div class="content col-9 d-flex">
                                <p class="my-auto">
                                    <span class="fw-bold"><?= $fetchPerson['username'] ?></span>
                                    <?php if ($fetchPerson['verified'] == 1) { ?>
                                        <i class="bi bi-patch-check-fill text-info"></i>
                                    <?php } ?>
                                    mentioned you in a post. <span class="text-muted"><?= time_elapsed_string($fetchNotification['time']) ?></span>
                                </p>
                            </div>
                        </div>
                        <?php if ($fetchPost['type'] != 'tweet') { ?>
                            <a href="<?= BASEURL ?>/post/?p=<?= $post_id ?>" class="preview d-flex col-lg-1 col-2">
                                <?php if ($fetchPost['type'] == 'post') { ?>
                                    <img src=" <?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" alt="post" class="img-fluid m-auto" style="height: 50px;">
                                <?php } elseif ($fetchPost['type'] == 'video') { ?>
                                    <video src="<?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" class="img-fluid m-auto" style="height: 50px"></video>
                                <?php } ?>
                            </a>
                        <?php } ?>

                    </div>

                <?php } elseif ($fetchNotification['type'] == 'mentionComment') { ?>

                    <div class="mb-3 d-flex gap-1 col-12">
                        <div class="d-flex gap-2 col-10">
                            <div class="iconType col-2">
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchPerson['user_id'] ?>">
                                    <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $fetchPerson['profile_picture'] ?>" alt="profile <?= $fetchPerson['username'] ?>" class="my-auto d-block mx-auto rounded-circle" style="width: 50px; height: 50px;">
                                </a>
                            </div>
                            <div class="content col-9 d-flex">
                                <p class="my-auto">
                                    <span class="fw-bold"><?= $fetchPerson['username'] ?></span>
                                    <?php if ($fetchPerson['verified'] == 1) { ?>
                                        <i class="bi bi-patch-check-fill text-info"></i>
                                    <?php } ?>
                                    mentioned you in a comment. <span class="text-muted"><?= time_elapsed_string($fetchNotification['time']) ?></span>
                                </p>
                            </div>
                        </div>
                        <?php if ($fetchPost['type'] != 'tweet') { ?>
                            <a href="<?= BASEURL ?>/post/?p=<?= $post_id ?>" class="preview d-flex col-lg-1 col-2">
                                <?php if ($fetchPost['type'] == 'post') { ?>
                                    <img src=" <?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" alt="post" class="img-fluid m-auto" style="height: 50px;">
                                <?php } elseif ($fetchPost['type'] == 'video') { ?>
                                    <video src="<?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" class="img-fluid m-auto" style="height: 50px"></video>
                                <?php } ?>
                            </a>
                        <?php } ?>

                    </div>

                <?php } elseif ($fetchNotification['type'] == 'visitProfile') { ?>

                    <div class="mb-3 d-flex col-12">
                        <div class="d-flex gap-2 col-10">
                            <div class="iconType col-2 d-flex">
                                <i class="bi bi-eye fs-2 text-center m-auto"></i>
                            </div>
                            <div class="content col-12 d-flex">
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchPerson['user_id'] ?>" class="nav-link my-auto">
                                    <span class="fw-bold"><?= $fetchPerson['username'] ?></span>
                                    <?php if ($fetchPerson['verified'] == 1) { ?>
                                        <i class="bi bi-patch-check-fill text-info"></i>
                                    <?php } ?>
                                    visited your profile. <span class="text-muted"><?= time_elapsed_string($fetchNotification['time']) ?></span>
                                </a>
                            </div>
                        </div>

                    </div>

                <?php } elseif ($fetchNotification['type'] == 'likePost') { ?>

                    <div class="mb-3 d-flex gap-1 col-12">
                        <div class="d-flex gap-2 col-10">
                            <div class="iconType col-2">
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchPerson['user_id'] ?>">
                                    <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $fetchPerson['profile_picture'] ?>" alt="profile <?= $fetchPerson['username'] ?>" class="my-auto d-block mx-auto rounded-circle" style="width: 50px; height: 50px;">
                                </a>
                            </div>
                            <div class="content col-9 d-flex">
                                <p class="my-auto">
                                    <span class="fw-bold"><?= $fetchPerson['username'] ?></span>
                                    <?php if ($fetchPerson['verified'] == 1) { ?>
                                        <i class="bi bi-patch-check-fill text-info"></i>
                                    <?php } ?>
                                    liked your post. <span class="text-muted"><?= time_elapsed_string($fetchNotification['time']) ?></span>
                                </p>
                            </div>
                        </div>
                        <?php if ($fetchPost['type'] != 'tweet') { ?>
                            <a href="<?= BASEURL ?>/post/?p=<?= $post_id ?>" class="preview d-flex col-lg-1 col-2">
                                <?php if ($fetchPost['type'] == 'post') { ?>
                                    <img src=" <?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" alt="post" class="img-fluid m-auto" style="height: 50px;">
                                <?php } elseif ($fetchPost['type'] == 'video') { ?>
                                    <video src="<?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" class="img-fluid m-auto" style="height: 50px"></video>
                                <?php } ?>
                            </a>
                        <?php } ?>
                    </div>

                <?php } elseif ($fetchNotification['type'] == 'comment') { ?>

                    <div class="mb-3 d-flex gap-1 col-12">
                        <div class="d-flex gap-2 col-10">
                            <div class="iconType col-2">
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchPerson['user_id'] ?>">
                                    <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $fetchPerson['profile_picture'] ?>" alt="profile <?= $fetchPerson['username'] ?>" class="my-auto d-block mx-auto rounded-circle" style="width: 50px; height: 50px;">
                                </a>
                            </div>
                            <div class="content col-9 d-flex">
                                <p class="my-auto">
                                    <span class="fw-bold"><?= $fetchPerson['username'] ?></span>
                                    <?php if ($fetchPerson['verified'] == 1) { ?>
                                        <i class="bi bi-patch-check-fill text-info"></i>
                                    <?php } ?>
                                    commented on your post. <span class="text-muted"><?= time_elapsed_string($fetchNotification['time']) ?></span>
                                </p>
                            </div>
                        </div>
                        <?php if ($fetchPost['type'] != 'tweet') { ?>
                            <a href="<?= BASEURL ?>/post/?p=<?= $post_id ?>" class="preview d-flex col-lg-1 col-2">
                                <?php if ($fetchPost['type'] == 'post') { ?>
                                    <img src=" <?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" alt="post" class="img-fluid m-auto" style="height: 50px;">
                                <?php } elseif ($fetchPost['type'] == 'video') { ?>
                                    <video src="<?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" class="img-fluid m-auto" style="height: 50px"></video>
                                <?php } ?>
                            </a>
                        <?php } ?>
                    </div>

                <?php } elseif ($fetchNotification['type'] == 'likeStory') { ?>

                    <div class="mb-3 d-flex gap-1 col-12">
                        <div class="d-flex gap-2 col-10">
                            <div class="iconType col-2">
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchPerson['user_id'] ?>">
                                    <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $fetchPerson['profile_picture'] ?>" alt="profile <?= $fetchPerson['username'] ?>" class="my-auto d-block mx-auto rounded-circle" style="width: 50px; height: 50px;">
                                </a>
                            </div>
                            <div class="content d-flex">
                                <p class="my-auto">
                                    <span class="fw-bold"><?= $fetchPerson['username'] ?></span>
                                    <?php if ($fetchPerson['verified'] == 1) { ?>
                                        <i class="bi bi-patch-check-fill text-info"></i>
                                    <?php } ?>
                                    liked your story. <span class="text-muted"><?= time_elapsed_string($fetchNotification['time']) ?></span>
                                </p>
                            </div>
                        </div>
                        <?php if ($fetchPost['type'] != 'tweet') { ?>
                            <a href="<?= BASEURL ?>/post/?p=<?= $post_id ?>" class="preview d-flex col-lg-1 col-2">
                                <?php if ($fetchPost['type'] == 'post') { ?>
                                    <img src=" <?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" alt="post" class="img-fluid m-auto" style="height: 50px;">
                                <?php } ?>
                            </a>
                        <?php } ?>
                    </div>

                <?php } ?>
            <?php endwhile; ?>
        </div>
    </div>
    <script>
        setTimeout
        var utterance = new SpeechSynthesisUtterance();
        var username = $('#username').val();
        var notification = $('#totalNotification').val();


        if (notification >= 1) {
            utterance.lang = "en";
            utterance.volume = 4;
            utterance.text = "Hello" + username + ", you have " + notification + " new notifications";

            window.speechSynthesis.speak(utterance);
        }
    </script>
</body>

</html>