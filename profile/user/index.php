<?php
session_start();
require_once '../../init.php';

$uuid = $_GET['id'];

$getUserInformation = $db->query("SELECT * FROM users WHERE user_id = '$uuid'");

$getAllLikes = $db->query("SELECT * FROM likes WHERE posted_id = '$uuid'");
$getTheUserPosts = $db->query("SELECT * FROM posts WHERE user_id = '$uuid' AND type IN ('post', 'video') ORDER BY id DESC");
$getTheUserReels = $db->query("SELECT * FROM posts WHERE user_id = '$uuid' AND type = 'reels' ORDER BY id DESC");
$getTheUserTweet = $db->query("SELECT * FROM posts WHERE user_id = '$uuid' AND type = 'tweet' ORDER BY id DESC");
$getTheUserStory = $db->query("SELECT * FROM story WHERE user_id = '$uuid' AND time > DATE_SUB(CURDATE(), INTERVAL 1 DAY) GROUP BY user_id ORDER BY time DESC");


$getFollowers = $db->query("SELECT * FROM followers WHERE user_id = '$uuid'");
$getFollowing = $db->query("SELECT * FROM following WHERE user_id = '$uuid'");


$followers = mysqli_num_rows($getFollowers);
$following = mysqli_num_rows($getFollowing);
$user = mysqli_fetch_assoc($getUserInformation);

$userLikes = mysqli_num_rows($getAllLikes);

isset($_GET['show']) ? $show = $_GET['show'] : $show = 'post';

$userNotFound = mysqli_num_rows($getUserInformation);

if ($userNotFound === 0) {
    header("location: " . BASEURL . "/error");
    exit;
}

if (isset($_SESSION['myapp_login'])) {
    setNotificationVisitProfile($uuid) > 0 ? '' : '';


    if ($user['user_id'] == $_SESSION['myapp_login']) {
        header('location: ' . BASEURL . '/profile');
        exit;
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

    $uid = $_SESSION['myapp_login'];
    $isFollow = $db->query("SELECT * FROM following WHERE user_id = '$uid' AND following = '$uuid'");
    $isFollow = mysqli_num_rows($isFollow);
    $getProfile = $db->query("SELECT * FROM users WHERE user_id = '$uid'");
    $getData = mysqli_fetch_assoc($getProfile);
    $theme = $getData['theme'];
    $getRecommendedUser = $db->query("SELECT * FROM users WHERE user_id != '$uid' ORDER by user_id DESC LIMIT 8");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=0.95">

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

    <title>Profile | @<?= $user['username'] ?></title>

</head>

<body onload="load();" class="<?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">

    <div class="d-flex">
        <div class="d-none d-md-none d-lg-flex col-2">
            <div class="col-2">
                <?php isset($_SESSION['myapp_login']) ? include '../../components/sidenav.php' : '' ?>
            </div>
        </div>

        <div class="d-lg-none">
            <?php
            require_once '../../components/bottom_nav.php';
            ?>
        </div>

        <div class="app position-relative mb-5 pb-3" id="app" style="width: 100vw;">
            <?php if (isset($_SESSION['myapp_login'])) { ?>
                <nav id="nav" class="navbar position-fixed shadow-sm col-lg-10 col-12 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
                <?php } else { ?>
                    <nav id="nav" class="navbar position-fixed shadow-sm col-lg-12 col-12">
                    <?php } ?>
                    <div class="container d-flex">
                        <div class="back">
                            <?php if (isset($_SESSION['myapp_login'])) { ?>
                                <a href="javascript:history.back()" class="nav-link d-lg-none my-auto">
                                <?php } else { ?>
                                    <a href="<?= BASEURL ?>/login" class="nav-link my-auto">
                                    <?php } ?>
                                    <i class="bi bi-arrow-left fs-5"></i>
                                    </a>
                        </div>
                        <a href="<?= BASEURL ?>" class="name nav-link">
                            <p class="fw-bold fs-5 my-auto">
                                <?php
                                if ($user['name'] != '') {
                                    echo $user['name'];
                                } else {
                                    echo $user['username'];
                                }
                                ?>
                            </p>
                        </a>
                        <div class="dropdown">
                            <a class="btn p-0" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: none">
                                <i class="bi bi-three-dots-vertical fs-5 <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end <?= $theme == 'dark' ? 'bg-black' : '' ?>">
                                <li>
                                    <button type="button" class="dropdown-item d-flex gap-2 btn" data-bs-toggle="modal" data-bs-target="#qrModal" style="border: none">
                                        <i class="bi bi-qr-code fs-6 my-auto dropwdown-text <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                                        <p class="dropwdown-text my-auto <?= $theme == 'dark' ? 'text-white' : '' ?>">QR Code</p>
                                    </button>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex gap-2" href="#">
                                        <i class="bi bi-flag-fill fs-6 my-auto dropwdown-text <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                                        <p class="dropwdown-text my-auto <?= $theme == 'dark' ? 'text-white' : '' ?>">Report this account</p>
                                    </a>
                                </li>
                                <?php if (isset($_SESSION['myapp_login'])) { ?>
                                    <li>
                                        <a href="<?= BASEURL ?>/chat/t/?direct=<?= $user['user_id'] ?>" class="dropdown-item d-flex gap-2" href="#">
                                            <i class="bi bi-send fs-6 my-auto dropwdown-text <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                                            <p class="dropwdown-text my-auto <?= $theme == 'dark' ? 'text-white' : '' ?>">Send message</p>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>

                    </nav>

                    <div class="col-4 img-profile mt-5 pt-2 mx-auto">
                        <?php if (mysqli_num_rows($getTheUserStory) > 0) {
                            while ($fetchStory = $getTheUserStory->fetch_assoc()) : ?>
                                <a href="<?= BASEURL ?>/story/?sid=<?= $fetchStory['user_id'] ?>" class="nav-link">
                                    <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $user['profile_picture'] ?>" alt="<?= $user['username'] ?>" class="rounded-circle d-block mx-auto p-1" style="border: 2px solid #15AAEE;"></a>
                                </a>
                            <?php endwhile;
                        } else { ?>
                            <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $user['profile_picture'] ?>" alt="<?= $user['username'] ?>" class="rounded-circle p-1 d-block mx-auto">
                        <?php } ?>
                    </div>

                    <div class="col-12 mx-auto">
                        <div class="d-flex mb-2 mb-lg-3 justify-content-center gap-1">
                            <p class="my-auto">
                                <span class="fw-bold fs-6">@<?php echo $user['username']; ?></span>
                                <?php if ($user['verified'] == 1) {
                                ?>
                                    <i class="bi bi-patch-check-fill text-info"></i>
                                <?php } ?>
                            </p>
                            <div class="qrcode d-flex">
                                <button type="button" class="btn p-0 my-auto" data-bs-toggle="modal" data-bs-target="#qrModal" style="border: none">
                                    <i class="bi bi-qr-code fs-6 <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                                </button>
                            </div>
                        </div>

                        <div id="accountInfo" class="d-flex account-info gap-3 justify-content-center">
                            <div class="d-lg-flex gap-1" style="width: 100px">
                                <p class="fw-bold text-center mb-0" style="font-size: 15px;"><?= number_format_short($following) ?></p>
                                <p class="mb-0 text-center" style="font-size: 15px;">Following</p>
                            </div>
                            <div class="d-lg-flex gap-1">
                                <p class="fw-bold text-center mb-0" style="font-size: 15px;"><?= $userLikes ?></p>
                                <p class="mb-0" style="font-size: 15px;">Likes</p>
                            </div>
                            <div class="d-lg-flex gap-1" style="width: 100px">
                                <p class="fw-bold text-center mb-0" style="font-size: 15px;"><?= number_format_short($followers) ?></p>
                                <p class="mb-0 text-center" style="font-size: 15px;">Followers</p>
                            </div>
                        </div>

                        <?php if (isset($_SESSION['myapp_login'])) { ?>
                            <div class="d-flex justify-content-center mt-2 gap-1">

                                <form id="followUser" action="" method="post" class="d-flex gap-1">
                                    <input type="hidden" name="uid" value="<?= $user['user_id'] ?>">
                                    <?php if ($isFollow == 1) { ?>

                                        <button type="button" id="followBtn" name="follow" class="btn py-0 text-light" style="border-radius: 8px; border:none; background-color: #FF3B5C;">
                                            <i class="bi bi-person-check-fill text-light"></i>
                                        </button>
                                    <?php } else { ?>
                                        <button type="button" id="followBtn" name="follow" class="btn text-light py-0" style="border-radius: 8px; border:none; background-color: #FF3B5C;">Follow</button>
                                    <?php } ?>
                                    <a href="<?= BASEURL ?>/chat/t/?direct=<?= $user['user_id'] ?>" class="nav-link bg-muted py-0 px-3 d-flex fw-bold" name="message" id="message" style="border-radius: 8px;"><i class="my-auto bi bi-send"></i></a>
                                </form>

                                <div class="recommended-account bg-muted" style="border-radius: 8px;">
                                    <button id="recommended-account" class="btn py-2 px-3" style="border-radius: 8px; border:none" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                        <i class="bi bi-caret-down-fill my-auto <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                                    </button>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if (isset($_SESSION['myapp_login'])) { ?>
                            <div class="collapse mt-2" id="collapseExample">
                                <div class="list-recomend-user col-lg-8 col-md-10 col-12 gap-1 d-flex mx-auto">
                                    <?php while ($fetchRecommendedUser  = mysqli_fetch_assoc($getRecommendedUser)) :
                                    ?>

                                        <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchRecommendedUser['user_id'] ?>" class="nav-link card col-lg-3 col-md-4 col-5 shadow-sm p-2 <?= $theme == 'dark' ? 'bg-dark' : '' ?>">
                                            <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $fetchRecommendedUser['profile_picture'] ?>" class="card-img-top d-block mx-auto" alt="..." style="width: 110px; border-radius: 50%">
                                            <div class="card-body p-0 py-2">
                                                <h5 class="card-title fs-6 text-center">
                                                    <?= $fetchRecommendedUser['username'] ?>
                                                    <?php if ($fetchRecommendedUser['verified'] == 1) { ?>
                                                        <i class="bi bi-patch-check-fill text-info"></i>
                                                    <?php } ?>
                                                </h5>
                                                <p class="card-text text-center text-muted mb-2" style="font-size: 12px;height: 50px">You may know <?= $fetchRecommendedUser['username'] ?></p>
                                                <button type="button" name="follow" class="btn btn-info text-white col-12" style="border-radius: 3px">Follow</button>
                                            </div>
                                        </a>
                                    <?php
                                    endwhile; ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>


                    <div class="mx-auto" style="width: 400px;">
                        <?php if ($user['bio'] != '') { ?>
                            <div class="col-12 mt-2 mb-0 justify-content-center">
                                <p class="my-auto text-center" style="font-size: 15px;"><?= $user['bio'] ?></p>
                            </div>
                        <?php } else { ?>
                            <div class="col-12 mt-2 mb-0 justify-content-center">
                                <p class="my-auto text-center text-muted" style="font-size: 15px;">no bio yet.</p>
                            </div>
                        <?php } ?>
                        <div class="col-12 my-0 justify-content-center">
                            <p class="my-auto text-center text-muted" style="font-size: 15px;"><i class="bi bi-calendar-event"></i> joined on <span class="fw-bold"><?= joinedAt($user['joined']) ?></span></p>
                        </div>
                    </div>

                    <div class="main">
                        <hr class="mb-2">
                        <div class="cta-choose d-flex gap-4 justify-content-center">
                            <?php if ($show == 'post') { ?>
                                <div class="bg-fill rounded-pill py-0 px-3"><a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=post" class="nav-link text-white">Post</a></div>
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=reels" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Reels</a>
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=tweet" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Tweet</a>
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=liked" class="border-none text-muted nav-link rounded-pill btn py-0 px-3"><i class="bi bi-heart"></i></a>
                            <?php } elseif ($show == 'reels') { ?>
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=post" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Post</a>
                                <div class="bg-fill rounded-pill py-0 px-3"><a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=reels" class="nav-link text-white">Reels</a></div>
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=tweet" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Tweet</a>
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=liked" class="border-none text-muted nav-link rounded-pill btn py-0 px-3"><i class="bi bi-heart"></i></a>
                            <?php } elseif ($show == 'tweet') { ?>
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=post" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Post</a>
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=reels" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Reels</a>
                                <div class="bg-fill rounded-pill py-0 px-3"><a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=tweet" class="nav-link text-white">Tweet</a></div>
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=liked" class="border-none text-muted nav-link rounded-pill btn py-0 px-3"><i class="bi bi-heart"></i></a>
                            <?php } elseif ($show == 'liked') { ?>
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=post" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Post</a>
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=reels" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Reels</a>
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=tweet" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Tweet</a>
                                <div class="bg-fill rounded-pill pt-1 px-3"><a href="<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>&show=liked" class="nav-link text-white"><i class="bi bi-heart"></i></a></div>
                            <?php } ?>
                        </div>
                        <hr class="mt-2">

                        <div class="posts d-flex justify-content-center">
                            <div class="container">
                                <?php if ($user['account'] == 'public' || $user['account'] == 'private' && $isFollow == 1) { ?>
                                    <div class="row row-cols-3 row-cols-lg-5 g-1 g-lg-2">
                                        <?php
                                        if ($show == 'post') {
                                            while ($fetchPost = mysqli_fetch_assoc($getTheUserPosts)) :
                                                $postId = $fetchPost['id'];
                                                $getViews = $db->query("SELECT * FROM views WHERE post_id = '$postId'");
                                                $views = mysqli_num_rows($getViews);
                                        ?>
                                                <div class="col">
                                                    <a href="<?= BASEURL ?>/post/?p=<?= $fetchPost['id'] ?>" class="m-0 d-flex" style="height: 170px; overflow: hidden; position: relative;">
                                                        <?php if ($fetchPost['type'] == 'video') { ?>
                                                            <div class="video-overlay d-flex">
                                                                <i class="bi bi-play fs-5 text-white fw-bold"></i>
                                                                <p class="my-auto text-white" style="font-size: 14px;"><?= $views ?></p>
                                                            </div>
                                                            <video class="d-block mx-auto" style="height: 170px;" src="<?= BASEURL; ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>">
                                                            <?php } elseif ($fetchPost['type'] == 'post') { ?>
                                                                <img src="<?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" class="d-block mx-auto" style="height: 170px;">
                                                            <?php } elseif ($fetchPost['type'] == 'tweet') {
                                                        }
                                                            ?>
                                                    </a>
                                                </div>

                                            <?php
                                            endwhile;
                                        } elseif ($show == 'reels') {
                                            while ($fetchReels = mysqli_fetch_assoc($getTheUserReels)) :
                                            ?>
                                                <div class="col">
                                                    <div class="p-1 border bg-light">
                                                        <p class="text-center my-auto">reels</p>
                                                    </div>
                                                </div>
                                            <?php endwhile;
                                        } elseif ($show == 'tweet') {
                                            while ($fetchTweet = mysqli_fetch_assoc($getTheUserTweet)) :
                                            ?>
                                                <div class="wrapper-content textMessage-content shadow-sm rounded mb-3 col-12 col-md-12 col-lg-6 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
                                                    <div class="header-textMessage d-flex justify-content-between px-3 py-2">
                                                        <div class="userData-textMessage d-flex gap-2">
                                                            <a href="<?= BASEURL ?>/profile/?q=profile&id=<?= $fetchTweet['user_id'] ?>">
                                                                <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $user['profile_picture'] ?>" alt="profile <?= $user['username'] ?>" class="my-auto rounded-circle" style="width: 45px; height: 45px;">
                                                            </a>
                                                            <div class="postebBy-textMessage">
                                                                <a href="<?= BASEURL ?>/profile/?q=profile&id=<?= $fetchTweet['user_id'] ?>" class="d-flex nav-link gap-1 mb-0 my-auto fw-bold">
                                                                    <p class="my-auto"><?= ucwords($user['username']) ?></p>
                                                                    <?php
                                                                    if ($user['verified'] == 1) {
                                                                    ?>
                                                                        <i class="bi bi-patch-check-fill text-info"></i>
                                                                    <?php } ?>
                                                                </a>
                                                                <p class="text-muted my-auto" style="font-size: 12px"><?= time_elapsed_string($fetchTweet['createdAt']) ?></p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="body-textMessage px-3 mt-1" style="overflow-wrap: break-word;">
                                                        <p><?= $fetchTweet['tweet'] ?></p>
                                                    </div>

                                                    <div class="footer-textMessage px-2 py-2">
                                                        <?php
                                                        $tId = $fetchTweet['id'];
                                                        $x = $db->query("SELECT * FROM likes WHERE post_id = '$tId'");
                                                        $like = mysqli_num_rows($x);

                                                        $y = $db->query("SELECT * FROM comment WHERE comment_id = '$tId'");
                                                        $comment = mysqli_num_rows($y);

                                                        if (isset($_SESSION['myapp_login'])) {
                                                            $uuid = $_SESSION['myapp_login'];
                                                            $isLikedByUser = $db->query("SELECT * FROM likes WHERE user_id = '$uuid' AND post_id = '$tId'");
                                                            $isLiked = mysqli_num_rows($isLikedByUser);
                                                        }

                                                        ?>
                                                        <form action="" method="post" class="d-flex gap-3 justify-content-between">
                                                            <div class="d-flex gap-2">
                                                                <div class="likes-textMessage d-flex">
                                                                    <?php
                                                                    if (isset($_SESSION['myapp_login'])) {
                                                                        if ($isLiked == 1) { ?>
                                                                            <button type="button" id="likes" name="likes" class="btn p-1 my-auto" style="border: none"><i class="bi bi-heart-fill fs-5 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>"></i></button>
                                                                        <?php } else { ?>
                                                                            <button type="button" id="likes" name="likes" class="btn p-1 my-auto" style="border: none"><i class="bi bi-heart fs-5 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>"></i></button>
                                                                        <?php }
                                                                    } else { ?>
                                                                        <button type="button" id="likes" name="likes" class="btn p-1 my-auto" style="border: none"><i class="bi bi-heart fs-5 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>"></i></button>
                                                                    <?php } ?>
                                                                    <p id="total-likes" class="my-auto"><?= $like ?></p>
                                                                </div>
                                                                <div class="comment-textMessage d-flex">
                                                                    <button type="button" name="comment" class="btn p-1 my-auto" style="border: none"><i class="bi bi-chat fs-5 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>"></i></button>
                                                                    <p class="my-auto"><?= $comment ?></p>
                                                                </div>
                                                            </div>
                                                            <div class="view-more">
                                                                <a href="<?= BASEURL ?>/post/?p=<?= $fetchTweet['id'] ?>" class="btn d-flex gap-1 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>" style="border: none">
                                                                    <i class="bi bi-eye fs-5 my-auto"></i>
                                                                    <p class="my-auto">More</p>
                                                                </a>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php
                                            endwhile;
                                        } elseif ($show == 'liked') {
                                            $uuidP = $_GET['id'];
                                            $getLikedPost = $db->query("SELECT * FROM likes WHERE user_id = '$uuidP' ORDER BY createdAt DESC");
                                            $nullLikedPost = mysqli_num_rows($getLikedPost);
                                            if ($nullLikedPost == 0) {
                                            ?>
                                                <p class="display-6 fs-5 fw-bold text-center">posts that you like will appear here</p>
                                            <?php
                                            }
                                            while ($fetchLiked = mysqli_fetch_assoc($getLikedPost)) :
                                                $postLikeId = $fetchLiked['post_id'];
                                            ?>
                                                <?php
                                                $getPostLiked = $db->query("SELECT * FROM posts WHERE id = '$postLikeId' AND type != 'tweet'");
                                                while ($fetchPostLiked = mysqli_fetch_assoc($getPostLiked)) :
                                                    $postId = $fetchPostLiked['id'];
                                                    $getViews = $db->query("SELECT * FROM views WHERE post_id = '$postId'");
                                                    $views = mysqli_num_rows($getViews);
                                                ?>
                                                    <div class="col">
                                                        <a href="<?= BASEURL ?>/post/?p=<?= $fetchPostLiked['id'] ?>" class="m-0 d-flex" style="height: 170px; overflow: hidden; position: relative;">
                                                            <?php if ($fetchPostLiked['type'] == 'video') { ?>
                                                                <div class="video-overlay d-flex">
                                                                    <i class="bi bi-play fs-5 text-white fw-bold"></i>
                                                                    <p class="my-auto text-white" style="font-size: 14px;"><?= $views ?></p>
                                                                </div>
                                                                <video class="d-block mx-auto" style="height: 170px;" src="<?= BASEURL; ?>/assets/uploads/posts/<?= $fetchPostLiked['post'] ?>">
                                                                <?php } elseif ($fetchPostLiked['type'] == 'post') { ?>
                                                                    <img src="<?= BASEURL ?>/assets/uploads/posts/<?= $fetchPostLiked['post'] ?>" class="d-block mx-auto" style="height: 170px;">
                                                                <?php }
                                                                ?>
                                                        </a>
                                                    </div>

                                            <?php
                                                endwhile;
                                            endwhile;
                                            ?>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="col-12 d-flex">
                                        <i class="bi bi-shield-lock text-muted m-auto" style="font-size: 75px;"></i>
                                    </div>
                                    <div class="message" style="transform: translateY(-20%);">
                                        <p class="text-muted text-center mb-0 fs-5">Thi account is Private</p>
                                        <p class="text-muted text-center" style="font-size: 13px; transform: translateY(-20%);">Follow this account to see photos and videos.</p>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                    </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content <?= $theme == 'dark' ? 'bg-black shadow-dark-mode text-white' : '' ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@<?= $user['username'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img class="d-block mx-auto" src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?= BASEURL ?>/profile/user/?id=<?= $user['user_id'] ?>%2F&choe=UTF-8" title="Link to @<?= $user['username'] ?>'s profile" />
                    <p class="display-6 fs-6 mt-2 text-center"><?= TITLE_SITE ?></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#followBtn').on('click', function() {
            var data = $('#followUser').serialize() + '&follow=follow';
            $.ajax({
                url: '',
                type: "POST",
                data: data,

            });

            $("#followUser")[0].reset();
        });

        let uuid = new URLSearchParams(window.location.search)
        let param = uuid.get('id')

        // console.log(param)

        function load() {
            $('#followBtn').load('../../app/loadFollowBtn.php', {
                'param': param
            });
            $('#accountInfo').load('../../app/loadAccountInfo.php', {
                'param': param
            });
            // $('#modalLikeBody').load('../app/loadModalPost.php', {
            //     'param': param
            // });
            // $('#listComment').load('../app/loadCommentPost.php', {
            //     'param': param
            // });
        }

        setInterval(function() {
            load();
        }, 1000);
    </script>
</body>

</html>