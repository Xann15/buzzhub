<?php
session_start();
require_once '../init.php';

if (!isset($_SESSION['myapp_login'])) {
    $_SESSION['failed'] = "Oops, you must login..";
    header('Location: ' . BASEURL . '/login');
    exit;
}

isset($_GET['sid']) ? $sId = $_GET['sid'] : $sId = '';

$getStorys = $db->query("SELECT * FROM story WHERE user_id = '$sId' ORDER BY time DESC");

$fetchStory = mysqli_fetch_assoc($getStorys);

$getUser = $db->query("SELECT * FROM users WHERE user_id = '$sId'");
$fetchUser = mysqli_fetch_assoc($getUser);

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

    <!-- Splide Js -->
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/splide/css/splide.min.css">

    <!-- Favicon -->
    <link href="<?= BASEURL; ?>/assets/img/favicon.png" rel="icon">

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <title>MyApp</title>

    <style>
        img {
            max-width: 100%;
            display: block;
        }
    </style>

</head>

<body class="bg-dark">
    <div class="d-flex gap-4">
        <div data-slide="slide" class="slide m-0 mx-auto">
            <div class="slide-items d-flex bg-dark">
                <?php while ($y = mysqli_fetch_assoc($getStorys)) : ?>
                    <img src="<?= BASEURL ?>/assets/uploads/story/<?= $y['media'] ?>" alt="Img 2" class="my-auto">
                <?php endwhile; ?>
                <img src="<?= BASEURL ?>/assets/uploads/posts/ssstik.io_1687483147110.jpeg" alt="Img 2" class="my-auto">
                <img src="<?= BASEURL ?>/assets/uploads/posts/ssstik.io_1687505737436.jpeg" alt="Img 3" class="my-auto">
                <img src="<?= BASEURL ?>/assets/uploads/posts/ssstik.io_1687483147110.jpeg" alt="Img 4" class="my-auto">
            </div>
            <div class="video-overlay pt-3" style="background-color: transparent;">
                <div class="userData d-flex gap-2">
                    <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchUser['user_id'] ?>">
                        <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $fetchUser['profile_picture'] ?>" alt="profile <?= $fetchUser['username'] ?>" class="my-auto rounded-circle" style="width: 35px; height: 35px;">
                    </a>
                    <div class="postebBy my-auto">
                        <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchUser['user_id'] ?>" class="d-flex gap-1 nav-link m-0 p-0 fw-bold">
                            <p class="my-auto text-white" style="font-size: 14px;"><?= $fetchUser['username'] ?></p>
                            <?php
                            if ($fetchUser['verified'] == 1) {
                            ?>
                                <i class="bi bi-patch-check-fill text-primary" style="font-size: 14px;"></i>
                            <?php } ?>
                        </a>
                    </div>
                    <div class="time d-flex">
                        <div class="my-auto text-muted text-white" style="font-size: 14px;"> <?= time_elapsed_string($fetchStory['time']) ?></div>
                    </div>
                </div>
            </div>
            <div class="video-overlay" style="background-color: transparent; bottom: 0; width: 380px">
                <button class="btn float-end"><i class="bi bi-heart text-white fs-4"></i></button>
            </div>
            <nav class="slide-nav">
                <div class="slide-thumb"></div>
                <button class="slide-prev">Prev</button>
                <button class="slide-next">Next</button>
            </nav>
        </div>
    </div>

    <script src="<?= BASEURL ?>/assets/instagram-photo-slideshow-stories/slide-stories.js"></script>
</body>

</html>