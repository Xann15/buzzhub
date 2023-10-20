<!-- https://getbootstrap.com/docs/4.0/components/forms/#validation -->
<?php
session_start();
require_once 'init.php';

if (!isset($_COOKIE['isOnBoarding'])) {
    require_once 'components/onboarding.php';
    exit;
}

!isset($_SESSION['myapp_login']) ? header('location: ' . BASEURL . '/login') : '';

$uuid = $_SESSION['myapp_login'];
$query = $db->query("SELECT * FROM users WHERE user_id = '$uuid'");
$getData = mysqli_fetch_assoc($query);
$theme = $getData['theme'];

$getStoryListByFollowingUser = $db->query("SELECT * FROM following WHERE user_id = '$uuid'");

$welcomeUsername = $getData['username'];

!isset($_SESSION['close_modal']) ? $_SESSION['show_modal'] = 'true' : '';

if (isset($_POST['sendTweet'])) {
    $tweet = htmlspecialchars($_POST['tweet']);
    if (sendTweet($tweet) > 0) {

        $_SESSION['success'] = "Uploaded";
        header("location: " . BASEURL);
    } else {
        $_SESSION['failed'] = "Failed Upload";
        header("location: " . BASEURL);
    }
}

isset($_GET['q']) ? $op = $_GET['q'] : $op = '';

if ($op == 'upload_post') {
    require_once 'components/upload_post.php';
    exit;
}

if (isset($_POST['likeBtn'])) {
    likePost($_POST) > 0 ? header('refresh:0;url=') : header('refresh:0;url=');
}

if (isset($_POST['follow'])) {
    if (follow($_POST) > 0) {
        header('refresh:0;url=');
        exit;
    } else {
        header('refresh:0;url=');
        exit;
    }
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
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/loading.min.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="<?= BASEURL; ?>/assets/js/bootstrap.bundle.min.js"></script>

    <!-- Splide Js -->
    <link rel="stylesheet" href="assets/splide/css/splide.min.css">

    <!-- Favicon -->
    <link href="<?= BASEURL; ?>/assets/img/favicon.png" rel="icon">

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <title><?= TITLE_SITE ?></title>

</head>

<body class="<?= $theme == 'dark' ? 'bg-black text-white' : 'bg-white' ?>">
    <input type="hidden" id="welcome" value="Hello <?= $welcomeUsername ?>, Welcome back to <?= TITLE_SITE ?>">
    <div class="d-lg-none d-md-none">
        <?php
        require_once 'components/header.php';
        require_once 'components/bottom_nav.php';
        ?>
    </div>



    <div class="d-flex">
        <div class="d-none d-md-none d-lg-flex col-2">
            <?php include 'components/sidenav.php' ?>
        </div>
        <?php $getPost = $db->query("SELECT p.id, p.user_id, p.type, p.post, p.tweet, p.caption, p.createdAt, p.show_comment, u.user_id, u.profile_picture, u.verified, u.username, u.name FROM posts as p JOIN users AS u ON p.user_id = u.user_id ORDER BY p.id DESC");

        ?>

        <div class="app mb-5" id="app" style="width: 100vw">
            <div class="row m-0 pb-5">
                <div class="col-12 m-0 p-0">
                    <?php if (isset($_SESSION['messageSignup'])) { ?>
                        <div class="alert alert-info alert-dismissible animate__animated animate__fadeInUp animate__delay-1s">
                            <p class="mb-0"><?= $_SESSION['messageSignup'] ?> <a href="signup/">create an account</a></p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php }
                    unset($_SESSION['messageSignup']); ?>

                    <div class="story col-xl-10 col-lg-10 col-md-10 col-12 col-sm-11 d-flex gap-2 mt-lg-5 mt-md-2 mt-2 mt-sm-3 mx-auto px-2" id="story">
                        <?php
                        $getOwnStory = $db->query("SELECT * FROM story WHERE user_id = '$uuid' AND time > DATE_SUB(CURDATE(), INTERVAL 1 DAY) GROUP BY user_id ORDER BY time DESC");
                        if (mysqli_num_rows($getOwnStory) > 0) {
                            while ($fetchOwnStory = $getOwnStory->fetch_assoc()) : ?>
                                <a href="<?= BASEURL ?>/story/?sid=<?= $fetchOwnStory['user_id'] ?>" class="nav-link">
                                    <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $getData['profile_picture'] ?>" alt="<?= $getData['username'] ?>" class="rounded-circle p-s" style="border: 2px solid #15AAEE; width: 65px">
                                    <p class="mb-0 text-center" style="font-size: 14px;"><?= maxLength($getData['username'], 9) ?></p>
                                </a>
                            <?php endwhile;
                        } else { ?>
                            <a href="<?= BASEURL ?>/upload/story" class="nav-link position-relative">
                                <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $getData['profile_picture'] ?>" alt="<?= $getData['username'] ?>" class="rounded-circle p-s" style="width: 65px">
                                <p class="mb-0 text-center" style="font-size: 14px;"><?= maxLength($getData['username'], 9) ?></p>
                                <div class="plus position-absolute bottom-0 end-0 mb-4 d-flex rounded-circle p-0">
                                    <span class="bi bi-plus-lg text-white fs-5 text-center bg-info rounded-circle" style="height: 25px; width: 25px;"></span>
                                </div>
                            </a>
                        <?php }
                        ?>
                        <?php $getStoryListByFollowingUser = $db->query("SELECT * FROM following WHERE user_id = '$uuid'");
                        while ($fetchGetListStory = mysqli_fetch_assoc($getStoryListByFollowingUser)) :
                            $listStoryUserId = $fetchGetListStory['following'];
                            $getStory = $db->query("SELECT * FROM story WHERE user_id = '$listStoryUserId' AND time > DATE_SUB(CURDATE(), INTERVAL 1 DAY) GROUP BY user_id ORDER BY time DESC");
                        ?>


                            <?php while ($fetchStory = mysqli_fetch_assoc($getStory)) :
                                $userIdStory = $fetchStory['user_id'];
                                $getProfile = $db->query("SELECT * FROM users WHERE user_id = '$userIdStory'");
                                $fetchGetProfile = mysqli_fetch_assoc($getProfile);
                            ?>
                                <a href="<?= BASEURL ?>/story/?sid=<?= $fetchGetProfile['user_id'] ?>" class="nav-link">
                                    <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $fetchGetProfile['profile_picture'] ?>" alt="<?= $fetchGetProfile['username'] ?>" class="rounded-circle p-s" style="border: 2px solid #15AAEE" width="65px">
                                    <p class="mb-0 text-center" style="font-size: 14px;"><?= maxLength($fetchGetProfile['username'], 9) ?></p>
                                </a>
                            <?php endwhile; ?>

                        <?php endwhile;
                        ?>
                    </div>
                </div>
            </div>

            <div class="createTweet mx-auto col-lg-10 col-md-8 col-11">
                <button class="btn btn-dark px-3 py-1 rounded-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                    <i class="bi bi-pencil"></i> Tweet
                </button>

                <div class="collapse mt-1" id="collapseExample">
                    <form action="" method="post">
                        <textarea class="form-control <?= $theme == 'dark' ? 'bg-black text-white' : 'bg-white' ?>" id="tweet" name="tweet" rows="3"></textarea>
                        <button type="submit" class="btn btn-dark rounded-4 border border-0 px-3 py-1 mt-1" id="sendTweet" name="sendTweet"><i class="bi bi-send"></i> Send</button>
                    </form>
                </div>
            </div>

            <div class="m-0 p-0">
                <?php while ($fetchPost = mysqli_fetch_assoc($getPost)) :
                    $type = $fetchPost['type'];
                ?>

                    <?php if ($type == 'tweet') { ?>
                        <div class="wrapper-content shadow-sm rounded col-12 col-sm-10 col-md-10 col-lg-10 col-xl-10 mx-auto <?= $theme == 'dark' ? 'bg-black text-white' : 'bg-white' ?>">
                        <?php } elseif ($type == 'post') { ?>
                            <div class="wrapper-content shadow-sm rounded my-1 col-sm-8 col-12 col-md-6 col-lg-6 col-xl-5 mx-auto <?= $theme == 'dark' ? 'bg-black text-white' : 'bg-white' ?>">
                            <?php } elseif ($type == 'video') { ?>
                                <div class="wrapper-content shadow-sm rounded my-1 col-sm-8 col-12 col-md-6 col-lg-6 col-xl-5 mx-auto <?= $theme == 'dark' ? 'bg-black text-white' : 'bg-white' ?>">
                                <?php } ?>
                                <div class="header d-flex justify-content-between p-2">
                                    <div class="userData d-flex gap-2">
                                        <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchPost['user_id'] ?>">
                                            <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $fetchPost['profile_picture'] ?>" alt="profile <?= $fetchPost['username'] ?>" class="my-auto rounded-circle" style="width: 45px; height: 45px;">
                                        </a>
                                        <div class="postebBy">
                                            <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchPost['user_id'] ?>" class="d-flex gap-1 nav-link m-0 p-0 fw-bold">
                                                <p class="my-auto"><?= $fetchPost['name'] ?></p>
                                                <?php
                                                if ($fetchPost['verified'] == 1) {
                                                ?>
                                                    <i class="bi bi-patch-check-fill text-info"></i>
                                                    <!-- <img src="<?= BASEURL ?>/assets/verified.png" alt="verified badge" width="25px" class="pb-1"> -->
                                                <?php } ?>
                                            </a>
                                            <p class="text-muted my-auto" style="font-size: 14px;transform:translateY(-30%)">@<?= $fetchPost['username'] ?></p>
                                        </div>
                                    </div>

                                    <button type="button" style="border:none" class="btn option" data-bs-toggle="offcanvas" data-bs-target="#offcanvas<?= $fetchPost['id'] ?>" aria-controls="offcanvasBottom">
                                        <i class="bi bi-three-dots fs-6 fw-bold text-muted"></i>
                                    </button>
                                </div>

                                <?php if ($type == 'tweet') { ?>
                                    <a href="<?= BASEURL ?>/post/?p=<?= $fetchPost['id'] ?>" class="content p-0 nav-link" style="max-height: 60vh; overflow-wrap: break-word;">
                                    <?php } else { ?>
                                        <a href="<?= BASEURL ?>/post/?p=<?= $fetchPost['id'] ?>" class="content p-0 nav-link" style="max-height: 60vh; overflow: hidden; overflow-wrap: break-word;">
                                        <?php }
                                    if ($type == 'tweet') { ?>
                                            <p class="<?= $theme == 'dark' ? 'bg-black text-white' : 'bg-white' ?> px-2 mb-0 pb-3"><?= convertHashtoLink(htmlspecialchars_decode($fetchPost['tweet'])) ?></p>

                                        <?php } elseif ($type == 'post') { ?>

                                            <img class="p-0 img-thumbnail d-block mx-auto" src="<?= BASEURL; ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" alt="post" style="max-height: 60vh; border: none; border-radius: 0px;">

                                        <?php } elseif ($type == 'video') { ?>
                                            <video controls class="img-thumbnail bg-black d-block mx-auto p-0" style="max-height: 60vh; border-radius:0px;border:none">
                                                <source src="<?= BASEURL; ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" type="video/mp4" />
                                            </video>

                                        <?php } ?>
                                        </a>

                                        <?php if ($type != 'tweet') { ?>
                                            <div class="caption px-2 pt-2">
                                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchPost['user_id'] ?>" class="fw-bold nav-link"><?= $fetchPost['username'] ?></a>
                                                <?php if ($fetchPost['caption'] != '') { ?>
                                                    <p class="mb-0"><?= convertHashtoLink(htmlspecialchars_decode($fetchPost['caption']))  ?></p>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>

                                        <a href="<?= BASEURL ?>/post/?p=<?= $fetchPost['id'] ?>" class="time my-0 nav-link px-2 pt-2">
                                            <?php
                                            $pid = $fetchPost['id'];
                                            $getView = $db->query("SELECT * FROM views WHERE post_id = '$pid'");
                                            $view = mysqli_num_rows($getView);
                                            ?>
                                            <p class="text-muted my-auto" style="font-size: 12px"><?= time_elapsed_string($fetchPost['createdAt']) ?> ago from Mars <span style="font-size: 10px;">•</span> <span class="fw-bold"><?= $view ?></span> Views</p>
                                        </a>

                                        <div class="footer px-2">
                                            <?php
                                            $getLikes = $db->query("SELECT * FROM likes WHERE post_id = '$pid' ORDER BY id DESC");
                                            $like = mysqli_num_rows($getLikes);
                                            ?>
                                            <button type="button" class="btn my-auto fw-bolder fs-6 px-0 <?= $theme == 'dark' ? 'text-white' : '' ?>" data-bs-toggle="modal" data-bs-target="#likeModal<?= $fetchPost['id'] ?>" style="border: none"><span class="total-likes" id="total-likes"><?= $like ?></span> suka</button>
                                            <form action="" method="post" class="d-flex justify-content-between">
                                                <div class="cta d-flex gap-2">
                                                    <div class="likes-tweet d-flex">
                                                        <input type="hidden" name="postId" value="<?= $fetchPost['id'] ?>">
                                                        <?php
                                                        $postId = $fetchPost['id'];
                                                        $isLikedByUser = $db->query("SELECT * FROM likes WHERE user_id = '$uuid' AND post_id = '$postId'");
                                                        $isLiked = mysqli_num_rows($isLikedByUser);
                                                        if ($isLiked == 1) { ?>
                                                            <button type="submit" id="likeBtn" name="likeBtn" class="btn px-0 my-auto" style="border: none"><i class="bi bi-heart-fill text-danger fs-4"></i></button>
                                                        <?php } else { ?>
                                                            <button type="submit" id="likeBtn" name="likeBtn" class="btn px-0 my-auto" style="border: none"><i class="bi bi-heart fs-4 <?= $theme == 'dark' ? 'text-white' : '' ?>"></i></button>
                                                        <?php }
                                                        ?>
                                                    </div>
                                                    <?php if ($fetchPost['show_comment'] == 'true') { ?>
                                                        <div class="comment d-flex">
                                                            <a href="<?= BASEURL ?>/post/?p=<?= $fetchPost['id'] ?>" type="button" name="comment" class="btn p-1 my-auto <?= $theme == 'dark' ? 'text-white' : '' ?>" style="border: none"><i class="bi bi-chat fs-4"></i></a>
                                                        </div>
                                                    <?php } else {
                                                        '';
                                                    } ?>
                                                    <div class="share d-flex">
                                                        <button type="button" class="btn p-1 mt-1 my-auto" data-bs-toggle="modal" data-bs-target="#shareModal<?= $fetchPost['id'] ?>" style="border: none">
                                                            <i class="bi bi-share fs-4 <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="bookmark">
                                                    <a href="#" class="btn d-flex gap-1" style="border: none">
                                                        <i class="bi bi-bookmark fs-4 my-auto <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                                                    </a>
                                                </div>
                                            </form>
                                        </div>
                                </div>

                                <!-- Modal Share -->
                                <div class="modal fade" id="shareModal<?= $fetchPost['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content <?= $theme == 'dark' ? 'bg-black shadow-dark-mode' : '' ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Share it</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <p id="copyLink" class="my-auto" id="link"><?= BASEURL ?>/posts/?p=<?= $fetchPost['id'] ?></p>
                                                    <button onclick="copy('<?= BASEURL ?>/posts/?p=<?= $fetchPost['id'] ?>')" class="btn">
                                                        <i class="my-auto bi bi-link-45deg fs-3 <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                                                    </button>
                                                </div>
                                                <div class="list d-flex justify-content-center">
                                                    <a href="#" class="btn">
                                                        <i class="bi bi-whatsapp fs-4 <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                                                    </a>
                                                    <a href="#" class="btn">
                                                        <i class="bi bi-instagram fs-4 <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                                                    </a>
                                                    <a href="#" class="btn">
                                                        <i class="bi bi-facebook fs-4 <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                                                    </a>
                                                    <a href="#" class="btn">
                                                        <i class="bi bi-tiktok fs-4 <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                                                    </a>
                                                </div>
                                                <p class="display-6 fs-6 text-center"><?= TITLE_SITE ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Like -->
                                <div class="modal fade" id="likeModal<?= $fetchPost['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content <?= $theme == 'dark' ? 'bg-black shadow-dark-mode text-white' : '' ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Like</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <?php
                                                while ($fetchLike = mysqli_fetch_assoc($getLikes)) :
                                                    $x = $fetchLike['user_id'];
                                                    $getUser = $db->query("SELECT name, username, verified, profile_picture FROM users WHERE user_id = $x");
                                                    $getUser = mysqli_fetch_assoc($getUser);
                                                ?>
                                                    <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchLike['user_id'] ?>" class="user nav-link d-flex justify-content-between shadow-sm rounded mb-2 p-2">
                                                        <div class="left d-flex gap-2">
                                                            <div class="img my-auto">
                                                                <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $getUser['profile_picture'] ?>" alt="<?= $getUser['username'] ?>" width="40px" class="rounded-circle">
                                                            </div>
                                                            <div class="username my-auto">
                                                                <div class="uname d-flex gap-1">
                                                                    <p class="fw-bold mb-0"><?= $getUser['name'] ?></p>
                                                                    <?php
                                                                    if ($getUser['verified'] == 1) {
                                                                    ?>
                                                                        <i class="bi bi-patch-check-fill text-info"></i>
                                                                    <?php } ?>
                                                                </div>

                                                                <p class="text-muted mb-0" style="font-size: 12px;"><?= $getUser['username'] ?></p>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        $uuid = $_SESSION['myapp_login'];
                                                        $person = $fetchLike['user_id'];
                                                        $isFollow = $db->query("SELECT * FROM following WHERE user_id = '$uuid' AND following = '$person'");
                                                        $isFollow = mysqli_num_rows($isFollow);
                                                        if ($uuid != $person) {
                                                        ?>
                                                            <form action="" method="post" class="my-auto">
                                                                <input type="hidden" name="uid" value="<?= $person ?>">
                                                                <?php if ($isFollow == 1) { ?>
                                                                    <button type="submit" name="follow" id="follow" class="btn py-0 px-3 text-white shadow-sm my auto" style="background-color: #FF3B5C;"><i class="bi bi-person-check-fill"></i> Friends</button>
                                                                <?php } else { ?>
                                                                    <button type="submit" name="follow" id="follow" class="btn text-white shadow-sm py-0 px-3 my auto" style="background-color: #FF3B5C;">Follow</button>
                                                                <?php } ?>
                                                            </form>
                                                        <?php
                                                        } ?>
                                                    </a>
                                                <?php endwhile; ?>
                                                <p class="display-6 fs-6 mt-3 mb-0 fw-bold text-center"><?= TITLE_SITE ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Offcanvas -->
                                <div class="offcanvas col-lg-8 col-md-6 col-12 mx-auto offcanvas-bottom" tabindex="-1" id="offcanvas<?= $fetchPost['id'] ?>" aria-labelledby="offcanvasBottomLabel">
                                    <div class="offcanvas-header">
                                        <h5 class="offcanvas-title" id="offcanvasBottomLabel">Responsive offcanvas</h5>
                                        <button type="button" class="btn btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvasBottom" aria-label="Close"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        <p class="mb-0"> <code><?= $fetchPost['id'] ?></code> This is content within an <code>htmlspecialchars($y)</code>.</p>
                                    </div>
                                </div>
                            <?php
                        endwhile; ?>

                            </div>
                        </div>

                        <?php if (isset($_SESSION['close_modal'])) { ?>

                        <?php exit;
                        }
                        if (isset($_SESSION['show_modal']) && isset($_SESSION['myapp_login'])) {
                            $getUname = $db->query("SELECT username FROM users WHERE user_id = '$uuid'");
                            $getName = mysqli_fetch_assoc($getUname);
                        ?>
                            <script type="text/javascript">
                                $(window).on('load', function() {
                                    setTimeout(() => {
                                        $('#populerPostModal').modal('show')
                                    }, 1500)
                                });
                            </script>
                            <!-- Modal -->
                            <div class="modal fade" id="populerPostModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold" id="exampleModalLabel"><?= TITLE_SITE ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-muted">
                                                <span class="mb-0"> hi <span class="fw-bold"><?= ucwords($getName['username']) ?></span> welcome back 👋,</span>
                                                <br>
                                                <span class="message">here are the 3 most popular posts of the week.</span>
                                            </p>
                                            <div id="carouselExampleControls" class="carousel carousel-dark" data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    <div class="carousel-item active">
                                                        <div class="card mx-auto col-10 col-lg-8">
                                                            <div class="img-wrapper"><img src="<?= BASEURL ?>/assets/user_profile_picture/profil.jpg" class="rounded w-100" style="height: 250px" alt="..."> </div>
                                                            <div class="card-body">
                                                                <h5 class="card-title">1. @Yogi</h5>
                                                                <p class="card-text mb-1">Some quick example text to build on the card title and make up the bulk of the
                                                                    card's content.</p>
                                                                <div class="view-more">
                                                                    <a href="<?= BASEURL ?>/post/?p=<?= $fetchPost['id'] ?>" class="p-0 btn d-flex gap-1" style="border: none">
                                                                        <i class="bi bi-eye fs-3 my-auto "></i>
                                                                        <p class=" my-auto">View Post</p>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="carousel-item">
                                                        <div class="card mx-auto col-10 col-lg-8">
                                                            <div class="img-wrapper"><img src="<?= BASEURL ?>/assets/user_profile_picture/profil.jpg" class="rounded w-100" style="height: 250px" alt="..."> </div>
                                                            <div class="card-body">
                                                                <h5 class="card-title">2. @Mark</h5>
                                                                <p class="card-text mb-1">Some quick example text to build on the card title and make up the bulk of the
                                                                    card's content.</p>
                                                                <div class="view-more">
                                                                    <a href="<?= BASEURL ?>/post/?p=<?= $fetchPost['id'] ?>" class="p-0 btn d-flex gap-1" style="border: none">
                                                                        <i class="bi bi-eye fs-3 my-auto "></i>
                                                                        <p class=" my-auto">View Post</p>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="carousel-item">
                                                        <div class="card mx-auto col-10 col-lg-8">
                                                            <div class="img-wrapper"><img src="<?= BASEURL ?>/assets/user_profile_picture/profil.jpg" class="rounded w-100" style="height: 250px" alt="..."> </div>
                                                            <div class="card-body">
                                                                <h5 class="card-title">3. @Admin</h5>
                                                                <p class="card-text mb-1">Some quick example text to build on the card title and make up the bulk of the
                                                                    card's content.</p>
                                                                <div class="view-more">
                                                                    <a href="<?= BASEURL ?>/post/?p=<?= $fetchPost['id'] ?>" class="p-0 btn d-flex gap-1" style="border: none">
                                                                        <i class="bi bi-eye fs-3 my-auto "></i>
                                                                        <p class=" my-auto">View Post</p>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Next</span>
                                                </button>
                                            </div>
                                            <div class="list d-flex justify-content-center">
                                                <a href="https://wa.me/?text=Hey%20look,%20let's%20join%20this%20app%20<?= BASEURL ?>%20and%20start%20uploading%20things%20you%20like" class="btn">
                                                    <i class="bi bi-whatsapp fs-4"></i>
                                                </a>
                                                <a href="#" class="btn">
                                                    <i class="bi bi-instagram fs-4"></i>
                                                </a>
                                                <a href="#" class="btn">
                                                    <i class="bi bi-facebook fs-4"></i>
                                                </a>
                                                <a href="#" class="btn">
                                                    <i class="bi bi-tiktok fs-4"></i>
                                                </a>
                                            </div>
                                            <p class="display-6 fs-6 text-center"><?= TITLE_SITE ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $_SESSION['close_modal'] = 'true';
                        }
                        unset($_SESSION['show_modal']); ?>

            </div>
        </div>

        <div class="tweet bg-info col-12 text-danger">

        </div>
    </div>

    <script>
        $(document).ready(function() {

            $(this).prop({
                controls: true,
                controlslist: "nodownload"
            });
            const observer = new window.IntersectionObserver(
                ([entry]) => {
                    if (entry.isIntersecting) {
                        if (this.paused) {
                            $(this).prop("muted", true);
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

            let text = document.getElementById("welcome");
            $('#getSpeech').on('click', function() {
                let utterance = new SpeechSynthesisUtterance(text.value);
                speechSynthesis.speak(utterance);
                return true;
            })

            $('#sendTweet').css('display', 'none');
            $('#sendTweet').css('opacity', '0');

            if ($('#btn').on('click', function() {
                    if ($('body').hasClass('bg-light')) {
                        $('body').removeClass('bg-light');
                        $('body').removeClass('text-black');
                        $('body').addClass('bg-dark');
                        $('body').addClass('btn-secondary');
                    } else {
                        $('body').removeClass('bg-dark');
                        $('body').removeClass('btn-secondary');
                        $('body').addClass('bg-light');
                        $('body').addClass('text-black');
                    }
                }))

                if ($('#tweet').on('keyup', function() {
                        $('#sendTweet').css('display', '');
                        $('#sendTweet').css('opacity', '1');
                    }));

            var multipleCardCarousel = document.querySelector(
                "#carouselExampleControls"
            );
            if (window.matchMedia("(min-width: 768px)").matches) {
                var carousel = new bootstrap.Carousel(multipleCardCarousel, {
                    interval: false,
                });
                var carouselWidth = $(".carousel-inner")[0].scrollWidth;
                var cardWidth = $(".carousel-item").width();
                var scrollPosition = 0;
                $("#carouselExampleControls .carousel-control-next").on("click", function() {
                    if (scrollPosition < carouselWidth - cardWidth * 4) {
                        scrollPosition += cardWidth;
                        $("#carouselExampleControls .carousel-inner").animate({
                                scrollLeft: scrollPosition
                            },
                            600
                        );
                    }
                });
                $("#carouselExampleControls .carousel-control-prev").on("click", function() {
                    if (scrollPosition > 0) {
                        scrollPosition -= cardWidth;
                        $("#carouselExampleControls .carousel-inner").animate({
                                scrollLeft: scrollPosition
                            },
                            600
                        );
                    }
                });
            } else {
                $(multipleCardCarousel).addClass("slide");
            }
        });
    </script>

    <?php require_once 'components/footer.php'; ?>