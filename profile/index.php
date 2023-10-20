<!-- https://getbootstrap.com/docs/4.0/components/forms/#validation -->
<?php
session_start();

require_once '../init.php';

if (!isset($_SESSION['myapp_login'])) {
    $_SESSION['failed'] = "Oops, you have to login first.";
    header('location: ' . BASEURL . '/login');
    exit;
}

$uuid = $_SESSION['myapp_login'];
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
$getDataLikes = mysqli_num_rows($getAllLikes);
$getData = mysqli_fetch_assoc($getUserInformation);
$theme = $getData['theme'];

// $getRecommendedUser = $db->query("SELECT u.user_id, u.username, u.name, u.verified, f.user_id, f.following FROM users AS u JOIN following AS f ON u.user_id = '$uuid' AND   u.user_id != f.user_id");
// $getRecommendedUser = $db->query("SELECT * FROM following WHERE user_id != '$uuid' AND following != '$uuid'");
$getRecommendedUser = $db->query("SELECT * FROM users ORDER by user_id DESC LIMIT 8");


// var_dump($getRecommendedUser);
// exit;

isset($_GET['show']) ? $show = $_GET['show'] : $show = 'post';

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

    <title>Profile | <?= TITLE_SITE ?></title>

</head>

<body class="<?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
    <div class="d-flex">
        <div class="d-none d-md-none d-lg-flex col-2">
            <?php isset($_SESSION['myapp_login']) ? include '../components/sidenav.php' : '' ?>
        </div>

        <div class="d-lg-none">
            <?php
            require_once '../components/bottom_nav.php';
            ?>
        </div>

        <div class="app position-relative mb-5 pb-3" id="app" style="width: 100vw;">
            <nav id="nav" class="navbar position-fixed shadow-sm col-lg-10 col-12 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
                <div class=" container">
                    <div class="d-flex gap-2">
                        <a class="fs-5 fw-bold nav-link my-auto d-lg-none" href="<?= BASEURL ?>"><i class="bi bi-arrow-left"></i></a>
                        <div class="fs-5 fw-bold nav-link my-auto">
                            <i class="bi bi-lock-fill"></i>
                            <?php
                            if ($getData['name'] != '') {
                                echo $getData['name'];
                            } else {
                                echo $getData['username'];
                            }
                            ?>
                        </div>
                    </div>
                    <div class="d-flex gap-3">
                        <a class="my-auto fs-5 nav-link mx-2 fw-bold" aria-current="page" href="<?= BASEURL; ?>/settings"><i class="bi bi-gear"></i></a>
                        <button type="button" class="btn p-0" data-bs-toggle="modal" data-bs-target="#qrModal" style="border: none">
                            <i class="bi bi-qr-code fs-5 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>"></i>
                        </button>
                    </div>
                </div>
            </nav>

            <div class="col-4 img-profile mx-auto mt-5 pt-2">

                <?php if (mysqli_num_rows($getTheUserStory) > 0) {
                    while ($fetchStory = $getTheUserStory->fetch_assoc()) : ?>
                        <a href="<?= BASEURL ?>/story/?sid=<?= $fetchStory['user_id'] ?>" class="nav-link">
                            <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $getData['profile_picture'] ?>" alt="<?= $getData['username'] ?>" class="rounded-circle d-block mx-auto p-1" style="border: 2px solid #15AAEE;"></a>
                        </a>
                    <?php endwhile;
                } else { ?>
                    <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $getData['profile_picture'] ?>" alt="<?= $getData['username'] ?>" class="rounded-circle p-1 d-block mx-auto">
                <?php } ?>

            </div>

            <div class="col-12 mx-auto">
                <div class="d-flex mb-2 mb-lg-3 justify-content-center gap-1">
                    <p class="my-auto">
                        <span class="fw-bold fs-6">
                            @<?php echo $getData['username'];
                                if ($getData['verified'] == 1) {
                                ?>
                        </span>
                        <i class="bi bi-patch-check-fill text-info"></i>
                    <?php } ?>
                    </p>
                    <div class="qrcode d-flex">
                        <button type="button" class="btn p-0 my-auto" data-bs-toggle="modal" data-bs-target="#qrModal" style="border: none">
                            <i class="bi bi-qr-code fs-6 <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex account-info gap-3 justify-content-center">
                    <div class="d-lg-flex gap-1" style="width: 100px">
                        <p class="fw-bold text-center mb-0" style="font-size: 15px;"><?= number_format_short($following) ?></p>
                        <p class="mb-0 text-center" style="font-size: 15px;">Following</p>
                    </div>
                    <div class="d-lg-flex gap-1">
                        <p class="fw-bold text-center mb-0" style="font-size: 15px;"><?= $getDataLikes ?></p>
                        <p class="mb-0" style="font-size: 15px;">Likes</p>
                    </div>
                    <div class="d-lg-flex gap-1" style="width: 100px">
                        <p class="fw-bold text-center mb-0" style="font-size: 15px;"><?= number_format_short($followers) ?></p>
                        <p class="mb-0 text-center" style="font-size: 15px;">Followers</p>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-2 gap-1">
                    <div class="bg-muted" style="border-radius: 8px"><a href="<?= BASEURL ?>/profile/edit" class="btn py-2 px-3 <?= $theme == 'dark' ? 'text-white' : '' ?>">Edit profile</a></div>
                    <div class="bg-muted" style="border-radius: 8px"><a href="<?= BASEURL ?>/profile/edit" class="btn py-2 px-3 <?= $theme == 'dark' ? 'text-white' : '' ?>" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Add friends</a></div>
                </div>

                <div class="collapse mt-2" id="collapseExample">
                    <div class="list-recomend-user col-lg-8 col-md-10 col-12 gap-1 d-flex mx-auto">
                        <?php while ($fetchRecommendedUser  = mysqli_fetch_assoc($getRecommendedUser)) :
                            $feRU = $fetchRecommendedUser['user_id'];
                            $getIsFollowing = $db->query("SELECT * FROM following WHERE user_id = '$feRU' AND following = '$uuid'");
                            // $getIsFollowers = $db->query("SELECT * FROM followers WHERE user_id = '$uuid' AND followers = '$uuid'");
                            // $fetchIsFollowers = mysqli_fetch_assoc($getIsFollowers);
                            // $feIF = $fetchIsFollowers['user_id'];
                            if ($fetchRecommendedUser['user_id'] != $uuid) {
                        ?>

                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchRecommendedUser['user_id'] ?>" class="nav-link card col-lg-3 col-md-4 col-5 shadow-sm p-2 <?= $theme == 'dark' ? 'bg-dark text-white' : '' ?>">
                                    <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $fetchRecommendedUser['profile_picture'] ?>" class="card-img-top d-block mx-auto" alt="..." style="width: 110px; border-radius: 50%">
                                    <div class="card-body p-0 py-2">
                                        <h5 class="card-title fs-6 text-center">
                                            <?= $fetchRecommendedUser['username'] ?>
                                            <?php if ($fetchRecommendedUser['verified'] == 1) { ?>
                                                <i class="bi bi-patch-check-fill text-info"></i>
                                            <?php } ?>
                                        </h5>
                                        <p class="card-text text-center text-muted mb-2" style="font-size: 12px;height: 50px">You may know <?= $fetchRecommendedUser['username'] ?></p>
                                        <?php
                                        $uuid = $_SESSION['myapp_login'];
                                        if (mysqli_num_rows($getIsFollowing) > 0) { ?>
                                            <button type="button" name="follow" class="btn btn-info text-white col-12" style="border-radius: 3px">Follow Back</button>
                                        <?php } else { ?>
                                            <button type="button" name="follow" class="btn btn-info text-white col-12" style="border-radius: 3px">Follow</button>
                                        <?php } ?>
                                    </div>
                                </a>
                        <?php }
                        endwhile; ?>
                    </div>
                </div>
            </div>

            <div class="mx-auto" style="width: 400px">
                <?php if ($getData['bio'] != '') { ?>
                    <div class="col-12 mt-2 mb-0 justify-content-center">
                        <p class="my-auto text-center text-muted" style="font-size: 15px;"><?= $getData['bio'] ?></p>
                    </div>
                <?php } else { ?>
                    <div class="mt-2 col-12 mb-0 justify-content-center">
                        <p class="my-auto text-center text-muted" style="font-size: 15px;">no bio yet.</p>
                    </div>
                <?php } ?>
                <div class="col-12 my-0 justify-content-center">
                    <p class="my-auto text-center text-muted" style="font-size: 15px;"><i class="bi bi-calendar-event"></i> joined on <span class="fw-bold"><?= joinedAt($getData['joined']) ?></span></p>
                </div>
            </div>

            <div class="main">
                <hr class="mb-2">
                <div class="cta-choose d-flex gap-4 justify-content-center">
                    <?php if ($show == 'post') { ?>
                        <div class="bg-fill rounded-pill py-0 px-3"><a href="?show=post" class="nav-link text-white">Post</a></div>
                        <a href="?show=reels" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Reels</a>
                        <a href="?show=tweet" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Tweet</a>
                        <a href="?show=liked" class="border-none text-muted nav-link rounded-pill btn py-0 px-3"><i class="bi bi-heart"></i></a>
                    <?php } elseif ($show == 'reels') { ?>
                        <a href="?show=post" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Post</a>
                        <div class="bg-fill rounded-pill py-0 px-3"><a href="?show=reels" class="nav-link text-white">Reels</a></div>
                        <a href="?show=tweet" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Tweet</a>
                        <a href="?show=liked" class="border-none text-muted nav-link rounded-pill btn py-0 px-3"><i class="bi bi-heart"></i></a>
                    <?php } elseif ($show == 'tweet') { ?>
                        <a href="?show=post" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Post</a>
                        <a href="?show=reels" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Reels</a>
                        <div class="bg-fill rounded-pill py-0 px-3"><a href="?show=tweet" class="nav-link text-white">Tweet</a></div>
                        <a href="?show=liked" class="border-none text-muted nav-link rounded-pill btn py-0 px-3"><i class="bi bi-heart"></i></a>
                    <?php } elseif ($show == 'liked') { ?>
                        <a href="?show=post" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Post</a>
                        <a href="?show=reels" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Reels</a>
                        <a href="?show=tweet" class="border-none text-muted nav-link rounded-pill btn py-0 px-3">Tweet</a>
                        <div class="bg-fill rounded-pill pt-1 px-3"><a href="?show=liked" class="nav-link text-white"><i class="bi bi-heart"></i></a></div>
                    <?php } ?>
                </div>
                <hr class="mt-2">

                <div class="posts d-flex justify-content-center">
                    <div class="container">
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
                                                <video class="d-block mx-auto" src="<?= BASEURL; ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>">
                                                <?php } elseif ($fetchPost['type'] == 'post') { ?>
                                                    <img src="<?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" class="d-block mx-auto" style="height: 170px;">
                                                <?php }
                                                ?>
                                        </a>
                                    </div>


                                <?php endwhile;
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
                                    $utid = $fetchTweet['user_id'];
                                    $getU = $db->query("SELECT * FROM users WHERE user_id = '$utid'");
                                    $getU = mysqli_fetch_assoc($getU);
                                ?>
                                    <div class="wrapper-content textMessage-content shadow-sm rounded mb-3 col-12 col-md-12 col-lg-6 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
                                        <div class="header-textMessage d-flex justify-content-between px-3 py-2">
                                            <div class="userData-textMessage d-flex gap-2">
                                                <a href="<?= BASEURL ?>/profile/?q=profile&id=<?= $fetchTweet['user_id'] ?>">
                                                    <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $getU['profile_picture'] ?>" alt="profile <?= $getU['username'] ?>" class="my-auto rounded-circle" style="width: 45px; height: 45px;">
                                                </a>
                                                <div class="postebBy-textMessage">
                                                    <a href="<?= BASEURL ?>/profile/?q=profile&id=<?= $fetchTweet['user_id'] ?>" class="d-flex nav-link gap-1 mb-0 my-auto fw-bold">
                                                        <p class="my-auto"><?= ucwords($getU['name']) ?></p>
                                                        <?php
                                                        $y = $fetchTweet['user_id'];
                                                        $x = $db->query("SELECT * FROM users WHERE user_id = '$y'");
                                                        $xy = mysqli_fetch_assoc($x);
                                                        if ($xy['verified'] == 1) {
                                                        ?>
                                                            <i class="bi bi-patch-check-fill text-info"></i>
                                                        <?php } ?>
                                                    </a>
                                                    <p class="text-muted my-auto" style="font-size: 12px"><?= time_elapsed_string($fetchTweet['createdAt']) ?></p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="body-textMessage px-3 mt-0" style="overflow-wrap: break-word;">
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
                                $uuidP = $_SESSION['myapp_login'];
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
                                                    <?php } elseif ($fetchPostLiked['type'] == 'tweet') {
                                                }
                                                    ?>
                                            </a>
                                        </div>
                                <?php
                                    endwhile;
                                endwhile;
                                ?>
                            <?php } ?>
                        </div>
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
                    <h5 class="modal-title" id="exampleModalLabel">@<?= $getData['username'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img class="d-block mx-auto" src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?= BASEURL ?>/profile/user/?id=<?= $getData['user_id'] ?>%2F&choe=UTF-8" title="Link to @<?= $getData['username'] ?>'s profile" />
                    <p class="display-6 fs-6 mt-2 text-center"><?= TITLE_SITE ?></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#sendTweet').css('display', 'none');
        $('#sendTweet').css('opacity', '0');

        const tombolLike = document.getElementById('likes');
        const totalLike = document.getElementById('total-likes');

        $("#likes").on('click', function() {

            $('#likes i').removeClass('bi bi-heart');
            $('#likes i').addClass('bi bi-heart-fill');
            $('#likes').addClass('liked');
            totalLike.innerHTML = parseInt(totalLike.innerHTML) + 1;
        });

        if ($('#tweet').on('keyup', function() {
                $('#sendTweet').css('display', '');
                $('#sendTweet').css('opacity', '1');
            }));
    </script>