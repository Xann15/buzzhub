<!-- https://getbootstrap.com/docs/4.0/components/forms/#validation -->
<?php
session_start();

require_once '../init.php';

if (isset($_SESSION['myapp_login'])) {
    $uuid = $_SESSION['myapp_login'];
    $getData = $db->query("SELECT * FROM users WHERE user_id = '$uuid'");
    $getData = mysqli_fetch_assoc($getData);
}
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
    <link rel="stylesheet" href="../assets/css/loading.min.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <!-- Favicon -->
    <link href="../assets/img/favicon.png" rel="icon">

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <title>Reels | MyApp</title>

</head>

<body>

    <?php

    $getPost = $db->query("SELECT * FROM posts WHERE type = 'reels'");
    ?>

    <style>
        body {
            background-color: black;
            color: white;
        }

        i {
            color: white;
        }

        video {
            width: 100%;
        }

        .reels-container {
            background-color: black;
            height: 90vh;
            width: 25vw;
            overflow-x: hidden;
            gap: 2em;
            flex-direction: column;
            scroll-snap-type: y mandatory;
        }

        .reels-container::-webkit-scrollbar {
            display: none
        }

        .reels {
            min-height: 90vh;
            min-width: 25vw;
            scroll-snap-align: start;
        }

        @media only screen and (max-width: 890px) {
            .reels-container {
                width: 50vw
            }

            .reels {
                min-width: 50vw
            }
        }

        @media only screen and (max-width: 490px) {
            .reels-container {
                height: 100vh;
                width: 100vw
            }

            .reels,
            .overlay {
                height: 93vh;
                min-width: 100vw
            }
        }
    </style>

    <div class="m-0">

        <?php $getPost = $db->query("SELECT p.id, p.user_id, p.type, p.post, p.tweet, p.caption, p.createdAt, p.show_comment, u.user_id, u.profile_picture, u.verified, u.username, u.name FROM posts as p JOIN users AS u ON p.user_id = u.user_id AND p.type = 'video' ORDER BY p.id DESC"); ?>


        <div class="app" id="app">
            <div class="reels-container mt-0 mx-auto">
                <?php while ($fetchPost = mysqli_fetch_assoc($getPost)) : ?>
                    <div class="reels d-flex position-relative">
                        <video class="m-auto" loop>
                            <source src="<?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" type="video/mp4" />
                        </video>
                        <div class="overlay position-absolute">
                            <div class="" style="height: 43vh;"></div>
                            <div class="area-cta col-12" style="height: 50vh; width: 100vw">
                                <div class="group col-2 d-flex flex-column float-end">

                                    <div class="profile mx-auto mb-4">
                                        <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $fetchPost['profile_picture'] ?>" alt="@<?= $fetchPost['username'] ?>*" width="45px" class="rounded-circle border">
                                    </div>

                                    <div class="like mx-auto">
                                        <?php
                                        $post_id = $fetchPost['id'];
                                        $getLikes = $db->query("SELECT * FROM likes WHERE post_id = '$post_id'");
                                        $totalLikes = $getLikes->num_rows;
                                        ?>
                                        <button type="button" class="btn border-0 p-0">
                                            <i class="bi bi-heart-fill fs-1"></i>
                                        </button>
                                        <p class="text-center mb-0" style="font-size: 13px; transform: translateY(-20%);"><?= number_format_short($totalLikes) ?></p>
                                    </div>

                                    <div class="like mx-auto">
                                        <?php
                                        $getComments = $db->query("SELECT * FROM comment WHERE comment_id = '$post_id'");
                                        $totalComments = $getComments->num_rows;
                                        ?>
                                        <button type="button" class="btn border-0 p-0">
                                            <i class="bi bi-chat-fill fs-1"></i>
                                        </button>
                                        <p class="text-center mb-0" style="font-size: 13px; transform: translateY(-20%);"><?= number_format_short($totalComments) ?></p>
                                    </div>

                                    <div class="bookmark mx-auto">
                                        <?php
                                        // $getComments = $db->query("SELECT * FROM comment WHERE comment_id = '$post_id'");
                                        // $totalComments = $getComments->num_rows;
                                        ?>
                                        <button type="button" class="btn border-0 p-0">
                                            <i class="bi bi-bookmark-fill fs-1"></i>
                                        </button>
                                        <p class="text-center mb-0" style="font-size: 13px; transform: translateY(-20%);"><?= 0 ?></p>
                                    </div>

                                    <div class="share mx-auto">
                                        <?php
                                        // $getComments = $db->query("SELECT * FROM comment WHERE comment_id = '$post_id'");
                                        // $totalComments = $getComments->num_rows;
                                        ?>
                                        <button type="button" class="btn border-0 p-0">
                                            <i class="bi bi-share-fill fs-2"></i>
                                        </button>
                                        <p class="text-center mb-0" style="font-size: 13px; transform: translateY(-20%);"><?= 0 ?></p>
                                    </div>
                                </div>
                                <div class="about-video col-10">
                                    <div class="" style="height: 25vh"></div>
                                    <div class="description p-3" style="height: 25vh;">
                                        <div class="d-flex gap-1">
                                            <p class="name fw-bold fs-5 mb-0 my-auto"><?= $fetchPost['name'] ?></p>
                                            <i class="bi bi-patch-check-fill text-info my-auto" style="font-size: 13px"></i>
                                        </div>

                                        <p><?= convertHashtoLinkReels(htmlspecialchars_decode($fetchPost['caption'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <script>
            $("video").each(function() {
                $(this).prop({
                    controls: false,
                    controlslist: "nodownload"
                });
                const observer = new window.IntersectionObserver(
                    ([entry]) => {
                        if (entry.isIntersecting) {
                            if (this.paused) {
                                // $(this).prop("muted", true);
                                this.currentTime = 0;
                                this.play();
                            }
                        } else {
                            this.pause();
                        }
                    }, {
                        threshold: 0.5,
                    }
                );
                observer.observe(this);
            });
        </script>

        <?php require_once '../components/footer.php'; ?>