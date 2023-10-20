<?php
require_once '../init.php';
session_start();

isset($_GET['p']) ? $show = $_GET['p'] : $show = '';

if (isset($_SESSION['myapp_login'])) {

    $uuid = $_SESSION['myapp_login'];
    $getUserData = $db->query("SELECT * FROM users WHERE user_id = '$uuid'");
    $getData = mysqli_fetch_assoc($getUserData);
    $theme = $getData['theme'];

    $profile = $getData['profile_picture'];
    $username = $getData['username'];
    $datetime = date("Y:m:d H:i:s");

    if (isset($_POST['sendComment'])) {
        $postId = $show;
        $getUserPosted = $db->query("SELECT u.user_id, p.user_id, p.id FROM users AS u JOIN posts AS p ON p.id = '$postId'");
        $fetchUserPosted = mysqli_fetch_assoc($getUserPosted);
        $postedBy = $fetchUserPosted['user_id'];

        if ($_POST['comment'] == '') {
            header('refresh:0; url=');
            exit;
        }
        $comment = $_POST['comment'];

        $db->query("INSERT INTO comment(comment_id, user_id, comment, createdAt) VALUES('$show', '$uuid', '$comment', '$datetime')");

        if ($uuid != $postedBy) {
            $db->query("INSERT INTO notification(user_id, person_id, post_id, type, time) VALUES('$postedBy','$uuid','$postId', 'comment', '$datetime')");
        }

        header('refresh:0; url=');
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
}

$query = $db->query("SELECT * FROM posts WHERE id = '$show'");

if (mysqli_num_rows($query) == 0) {
    require_once '../components/brokenpost.php';
    exit;
}

isset($_SESSION['myapp_login']) ? addViewPost() > 0 ? '' : '' : '';


if ($show != '') {
    $getComment = $db->query("SELECT * FROM comment WHERE comment_id = '$show' ORDER BY id DESC");

    $getPost = $db->query("SELECT * FROM posts WHERE id = '$show'");

    $fetch = mysqli_fetch_assoc($getPost);

    // var_dump($fetch);
    // exit;

    $postedBy = $fetch['user_id'];

    $getUser = $db->query("SELECT username, name, profile_picture, verified FROM users WHERE user_id = '$postedBy'");
    $getUser = mysqli_fetch_assoc($getUser);

    $getLikes = $db->query("SELECT * FROM likes WHERE post_id = '$show' ORDER BY id DESC");
    $like = mysqli_num_rows($getLikes);

    $getView = $db->query("SELECT * FROM views WHERE post_id = '$show'");
    $view = mysqli_num_rows($getView);
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

    <!-- Favicon -->
    <link href="<?= BASEURL; ?>/assets/img/favicon.png" rel="icon">

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <title>Post | <?= TITLE_SITE ?></title>

</head>

<body class="<?= $theme == 'dark' ? 'bg-black' : 'bg-light' ?>">

    <input type="text" name="link" id="link-post" value="<?= BASEURL ?>/post/?p=<?= $show ?>" style="opacity: 0">


    <?php if (isset($_SESSION['myapp_login'])) { ?>
        <nav id="nav" class="navbar fixed-top shadow-sm <?= $theme == 'dark' ? 'bg-black text-white' : 'bg-light' ?>">
            <div class="container">
                <a href="javascript:history.back()" class="d-flex gap-2 nav-link">
                    <i class="bi bi-arrow-left my-auto fs-4 fw-bold"></i>
                    <p class="my-auto fs-4 fw-bold">Back</p>
                </a>
                <a href="<?= BASEURL; ?>/chat" class="nav-title nav-link fw-bold my-auto">
                    <i class="bi bi-chat fs-3"></i>
                </a>
            </div>
        </nav>
    <?php } else { ?>
        <nav id="nav" class="navbar fixed-top shadow-sm navbar-expand p-2">
            <div class="container">
                <a href="javascript:history.back()" class="d-flex gap-2 nav-link">
                    <i class="bi bi-arrow-left my-auto fs-4 fw-bold"></i>
                    <p class="my-auto fs-4 fw-bold">Back</p>
                </a>
                <a class="nav-link nav-title mx-2" aria-current="page" href="<?= BASEURL; ?>/login"><i class="fs-4 bi bi-box-arrow-in-right"></i></a>
            </div>
        </nav>
    <?php } ?>


    <div class="container mt-2 p-0 pt-1 pb-3 mb-5">

        <!-- Content -->
        <?php $type = $fetch['type']; ?>
        <?php if ($type == 'tweet') { ?>
            <div class="col-12 col-lg-8 col-md-8 rounded mt-4 pb-1 mt-lg-2 mt-md-2 mx-auto <?= $theme == 'dark' ? 'shadow-dark-mode' : '' ?>">
            <?php } elseif ($type == 'post') { ?>
                <div class="col-12 col-lg-5 col-md-8 rounded mt-4 pb-1 mt-lg-2 mt-md-2 mx-auto <?= $theme == 'dark' ? 'shadow-dark-mode' : '' ?>">
                <?php } elseif ($type == 'video') { ?>
                    <div class="col-12 col-lg-5 col-md-8 rounded pb-1 mt-4 mt-lg-2 mt-md-2 mx-auto <?= $theme == 'dark' ? 'shadow-dark-mode' : '' ?>">
                    <?php } ?>
                    <div class="wrapper-content shadow-sm rounded my-3 m-2 <?= $theme == 'dark' ? 'bg-black text-white' : 'bg-light' ?>">
                        <div class="header d-flex justify-content-between pt-2 p-1">
                            <div class="userData d-flex gap-2">
                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetch['user_id'] ?>">
                                    <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $getUser['profile_picture'] ?>" alt="profile <?= $getUser['username'] ?>" class="my-auto rounded-circle" style="width: 45px; height: 45px;">
                                </a>
                                <div class="postedBy">
                                    <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetch['user_id'] ?>" class="d-flex nav-link mb-0 my-auto fw-bold gap-1">
                                        <p class="my-auto"><?= ucwords($getUser['name']) ?></p>
                                        <?php
                                        if ($getUser['verified'] == 1) {
                                        ?>
                                            <i class="bi bi-patch-check-fill text-info"></i>
                                        <?php } ?>
                                    </a>
                                    <p class="text-muted mb-0" style="font-size: 13px; transform: translateY(-5%)">
                                        @<?= $getUser['username'] ?>
                                    </p>
                                </div>
                            </div>

                            <div class="follow d-flex" id="followBtn">
                                <?php if (isset($_SESSION['myapp_login'])) {
                                    $uid = $_SESSION['myapp_login'];
                                    $uids = $fetch['user_id'];
                                    $isFollow = $db->query("SELECT * FROM following WHERE user_id = '$uid' AND following = '$uids'");
                                    $isFollow = mysqli_num_rows($isFollow);
                                    if ($uid != $uids) {
                                ?>
                                        <form action="" method="post" class="my-auto">
                                            <input type="hidden" name="uid" value="<?= $uids ?>">
                                            <?php if ($isFollow == 1) { ?>
                                                <button type="submit" name="follow" class="btn py-0 px-3 text-white shadow-sm my auto" style="background-color: #FF3B5C;"><i class="bi bi-person-check-fill"></i> Friends</button>
                                            <?php } else { ?>
                                                <button type="submit" name="follow" class="btn text-white shadow-sm py-0 px-3 my auto" style="background-color: #FF3B5C;">Follow</button>
                                            <?php } ?>
                                        </form>
                                <?php }
                                } ?>
                            </div>
                        </div>

                        <?php if ($type == 'tweet') { ?>
                            <div class="body p-0 mt-2 m-0 <?= $theme == 'dark' ? 'bg-black text-white' : 'bg-light' ?>" style="max-height: 60vh; overflow-wrap: break-word;">
                            <?php } else { ?>
                                <div class="body p-0 mt-2 w-100 <?= $theme == 'dark' ? 'bg-black' : '' ?>" style="max-height: 70vh; overflow: hidden">
                                <?php } ?>
                                <?php if ($type == 'tweet') { ?>
                                    <p class="mx-2"><?= convertHashtoLink(htmlspecialchars_decode($fetch['tweet'])) ?></p>
                                <?php } elseif ($type == 'post') { ?>
                                    <img class="post-height p-0 img-thumbnail d-block mx-auto" src="<?= BASEURL; ?>/assets/uploads/posts/<?= $fetch['post'] ?>" alt="post" style="width: auto; border: none; border-radius: 0px;">
                                <?php } elseif ($type == 'video') { ?>
                                    <video class="post-height img-thumbnail d-block mx-auto p-0" style="width: auto; border-radius:0px;border:none" src="<?= BASEURL; ?>/assets/uploads/posts/<?= $fetch['post'] ?>" loop autoplay>
                                    <?php } ?>
                                </div>

                                <?php if ($type != 'tweet') { ?>
                                    <div class="caption px-2 pt-2">
                                        <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetch['user_id'] ?>" class="fw-bold nav-link"><?= $getUser['username'] ?></a>
                                        <?php if ($fetch['caption'] != '') { ?>
                                            <p class="mb-0"><?= convertHashtoLink(htmlspecialchars_decode($fetch['caption'])) ?></p>
                                        <?php } ?>
                                    </div>
                                <?php } ?>

                                <a class="time my-0 nav-link px-2 pt-2">
                                    <p class="text-muted my-auto" style="font-size: 12px"><?= time_elapsed_string($fetch['createdAt']) ?> ago from Mars <span style="font-size: 10px;">•</span> <span class="fw-bold"><?= $view ?></span> Views</p>
                                </a>

                                <div class="footer px-2">
                                    <button type="button" class="btn my-auto fw-bolder fs-6 px-1 <?= $theme == 'dark' ? 'text-light' : 'text-dark' ?>" data-bs-toggle="modal" data-bs-target="#likeModal<?= $fetch['id'] ?>" style="border: none"><span class="total-likes" id="total-likes"><?= $like ?></span> suka</button>
                                    <form id="likePost" action="" method="post" class="d-flex justify-content-between">
                                        <div class="cta d-flex gap-2">
                                            <div class="likes-tweet d-flex">
                                                <input type="hidden" name="postId" value="<?= $fetch['id'] ?>">
                                                <?php if (isset($_SESSION['myapp_login'])) {
                                                    $postId = $fetch['id'];
                                                    $isLikedByUser = $db->query("SELECT * FROM likes WHERE user_id = '$uuid' AND post_id = '$postId'");
                                                    $isLiked = mysqli_num_rows($isLikedByUser);
                                                    if ($isLiked == 1) { ?>
                                                        <button type="button" id="likeBtn" name="likeBtn" class="btn p-1 mt-1 my-auto" style="border: none"><i class="bi bi-heart-fill fs-4 <?= $theme == 'dark' ? 'text-danger' : 'text-danger' ?>"></i></button>
                                                    <?php } else { ?>
                                                        <button type="button" id="likeBtn" name="likeBtn" class="btn p-1 mt-1 my-auto" style="border: none"><i class="bi bi-heart fs-4 <?= $theme == 'dark' ? 'text-light' : 'text-dark' ?>"></i></button>
                                                <?php }
                                                }
                                                ?>
                                            </div>
                                            <div class="share d-flex">
                                                <button type="button" class="btn p-1 mt-1 my-auto" data-bs-toggle="modal" data-bs-target="#shareModal<?= $fetch['id'] ?>" style="border: none">
                                                    <i class="bi bi-share fs-4 <?= $theme == 'dark' ? 'text-light' : 'text-dark' ?>"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Comment -->
                            <div class="wrapper-content comment-content shadow-sm rounded mb-3 m-2 <?= $theme == 'dark' ? 'bg-black text-white' : 'bg-white' ?>">

                                <div class="header-comment d-flex gap-2 justify-content-between p-2">
                                    <?php if (isset($_SESSION['myapp_login'])) {
                                        if ($fetch['show_comment'] == 'false') {
                                    ?>
                                            <p class="text-muted mx-auto mb-0">This post does not allow comments.</p>
                                        <?php exit;
                                        } ?>
                                        <a class="d-flex" href="<?= BASEURL; ?>/profile">
                                            <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $getData['profile_picture'] ?>" alt="<?= $getData['username'] ?>" class="my-auto rounded-circle" style="width: 35px; height: 35px;">
                                        </a>
                                        <form id="formComment" action="" method="post" class="w-100 my-auto d-flex gap-2" onkeydown="return event.key != 'Enter';">
                                            <input type="text" name="comment" id="comment" class="form-control <?= $theme == 'dark' ? 'bg-dark text-white' : '' ?>" placeholder="add a comment..." style="border: none">
                                            <button type="button" name="sendComment" id="sendComment" class="btn text-white shadow-sm py-0 px-4 rounded-5" style="background-color: #FF3B5C;">
                                                <i class="bi bi-send fs-5"></i>
                                            </button>
                                        </form>
                                    <?php } else { ?>
                                        <p class="mx-auto text-muted mb-0">Login to be able to comment, <a href="<?= BASEURL ?>/login">login now.</a></p>
                                    <?php } ?>
                                </div>
                                <div class="body-comment p-1 rounded-4 mt-2 <?= $theme == 'dark' ? 'bg-dark text-white' : 'bg-white' ?>" id="listComment">
                                    <?php while ($fetchComment = mysqli_fetch_assoc($getComment)) :
                                        $userComment = $fetchComment['user_id'];
                                        $getUserComment = $db->query("SELECT * FROM users WHERE user_id = '$userComment'");
                                        $fetchUserComment = mysqli_fetch_assoc($getUserComment);
                                    ?>
                                        <div class="user mb-2 p-1 rounded">
                                            <div class="body-user d-flex gap-2">
                                                <a class="d-flex my-auto" href="<?= BASEURL; ?>/profile/user/?id=<?= $fetchUserComment['user_id'] ?>">
                                                    <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $fetchUserComment['profile_picture'] ?>" alt="<?= $getData['username'] ?>" class="my-auto rounded-circle" style="width: 40px; height: 40px;">
                                                </a>
                                                <div class="info-user">
                                                    <p class="fw-bold mb-0">
                                                        <?= $fetchUserComment['name'] ?>
                                                        <?php if ($fetchUserComment['verified'] == 1) { ?>
                                                            <i class="bi bi-patch-check-fill text-info"></i>
                                                        <?php } ?>
                                                        <span class="text-muted" style="font-size: 11px">• <?= time_elapsed_string($fetchComment['createdAt']) ?></span>
                                                    </p>
                                                    <p class="text-muted mb-0" style="font-size: 12px; transform: translateY(-20%)">
                                                        @<?= $fetchUserComment['username'] ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <p class="text my-auto" style="margin-left: 45px; transform: translateY(-15%)"><?= $fetchComment['comment'] ?></p>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                            </div>

                            <!-- Modal Share -->
                            <div class="modal fade" id="shareModal<?= $fetch['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content <?= $theme == 'dark' ? 'bg-black shadow-dark-mode text-white' : 'bg-white' ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Share it</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="d-flex justify-content-center gap-2">
                                                <?php if ($type == 'tweet') { ?>
                                                    <p id="copyLink" class="my-auto" id="link"><?= BASEURL ?>/posts/?post=<?= $fetch['id'] ?></p>
                                                    <button onclick="copy('<?= BASEURL ?>/posts/?post=<?= $fetch['id'] ?>')" class="btn">
                                                        <i class="my-auto bi bi-link-45deg fs-3"></i>
                                                    </button>
                                                <?php } elseif ($type == 'post') { ?>
                                                    <img class="p-0 img-thumbnail" src="<?= BASEURL; ?>/assets/uploads/posts/<?= $fetch['post'] ?>" alt="post" style="width: 200px; border: none; border-radius: 0px;">
                                                <?php } elseif ($type == 'video') { ?>
                                                    <video class="img-thumbnail p-0" style="width: 200px;border-radius:0px;border:none" src="<?= BASEURL; ?>/assets/uploads/posts/<?= $fetch['post'] ?>" loop muted autoplay>
                                                    <?php } ?>
                                            </div>
                                            <div class="list d-flex justify-content-center">
                                                <a href="#" class="btn">
                                                    <i class="bi bi-whatsapp fs-4 <?= $theme == 'dark' ? 'text-white' : 'text-dark' ?>"></i>
                                                </a>
                                                <a href="#" class="btn">
                                                    <i class="bi bi-instagram fs-4 <?= $theme == 'dark' ? 'text-white' : 'text-dark' ?>"></i>
                                                </a>
                                                <button type="button" class="btn my-auto" data-bs-toggle="modal" data-bs-target="#qrModal<?= $fetch['id'] ?>" style="border: none">
                                                    <i class="bi bi-qr-code fs-4 <?= $theme == 'dark' ? 'text-white' : 'text-dark' ?>"></i>
                                                </button>
                                                <a href="#" class="btn">
                                                    <i class="bi bi-facebook fs-4 <?= $theme == 'dark' ? 'text-white' : 'text-dark' ?>"></i>
                                                </a>
                                                <a href="#" class="btn">
                                                    <i class="bi bi-tiktok fs-4 <?= $theme == 'dark' ? 'text-white' : 'text-dark' ?>"></i>
                                                </a>
                                                <a href="#" class="btn">
                                                    <button type="button" class="dropdown-item" id="copy-button">
                                                        <i class=" bi bi-link-45deg fs-4 my-auto dropwdown-text <?= $theme == 'dark' ? 'text-white' : 'text-dark' ?>"></i>
                                                    </button>
                                                </a>
                                            </div>
                                            <p class="display-6 fs-6 text-center"><?= TITLE_SITE ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Like -->
                            <div class="modal fade" id="likeModal<?= $fetch['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content <?= $theme == 'dark' ? 'bg-black shadow-dark-mode text-white' : 'bg-white' ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Like</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" id="modalLikeBody">
                                            <?php
                                            while ($fetchLike = mysqli_fetch_assoc($getLikes)) :
                                                $x = $fetchLike['user_id'];
                                                $getName = $db->query("SELECT name, username, profile_picture FROM users WHERE user_id = $x");
                                                $getName = mysqli_fetch_assoc($getName);
                                            ?>
                                                <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchLike['user_id'] ?>" class="user nav-link d-flex justify-content-between shadow-sm rounded mb-2 px-0 py-1">
                                                    <div class="left d-flex gap-2">
                                                        <div class="img my-auto">
                                                            <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $getName['profile_picture'] ?>" alt="<?= $getName['username'] ?>" width="40px" class="rounded-circle">
                                                        </div>
                                                        <div class="username my-auto">
                                                            <div class="uname d-flex gap-1">
                                                                <p class="fw-bold mb-0"><?= $getName['name'] ?></p>
                                                                <?php
                                                                $y = $fetchLike['user_id'];
                                                                $x = $db->query("SELECT * FROM users WHERE user_id = '$y'");
                                                                $xy = mysqli_fetch_assoc($x);
                                                                if ($xy['verified'] == 1) {
                                                                ?>
                                                                    <i class="bi bi-patch-check-fill text-info"></i>
                                                                <?php } ?>
                                                            </div>

                                                            <p class="text-muted mb-0" style="font-size: 12px;">@<?= $getName['username'] ?></p>
                                                        </div>
                                                    </div>
                                                    <?php if (isset($_SESSION['myapp_login'])) {
                                                        $uuid = $_SESSION['myapp_login'];
                                                        $person = $fetchLike['user_id'];
                                                        $isFollow = isFollow($uuid, $person);
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
                                                    <?php }
                                                    } ?>
                                                </a>
                                            <?php endwhile; ?>
                                            <p class="display-6 fs-6 mt-3 mb-0 fw-bold text-center"><?= TITLE_SITE ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- QR Modal -->
                            <div class="modal fade" id="qrModal<?= $fetch['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content <?= $theme == 'dark' ? 'bg-black shadow-dark-mode text-white' : 'bg-white' ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">QR Code</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="text-center fw-bold mb-0 fs-4">SCAN ME!</p>
                                            <img class="d-block mt-2 mx-auto" src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?= BASEURL ?>/post/?p=<?= $fetch['id'] ?>&choe=UTF-8" title="the link goes to the post posted by <?= $getUser['username'] ?>" />
                                            <p class="display-6 fs-6 mt-2 text-center"><?= TITLE_SITE ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </div>


                    <script type="text/javascript">
                        $('#likeBtn').on('click', function() {
                            var data = $('#likePost').serialize() + '&likeBtn=likeBtn';
                            $.ajax({
                                url: '',
                                type: "POST",
                                data: data,

                            });

                            $("#likePost")[0].reset();
                        });

                        $('#sendComment').on('click', function() {
                            var data = $('#comment').serialize() + '&sendComment=sendComment';
                            $.ajax({
                                url: '',
                                type: "POST",
                                data: data,

                            });

                            $("#formComment")[0].reset();
                        });

                        let postId = new URLSearchParams(window.location.search)
                        let param = postId.get('p')

                        // console.log(param)

                        function load() {
                            $('#total-likes').load('../app/loadlikePost.php', {
                                'param': param
                            });
                            $('#likeBtn').load('../app/loadBtnPost.php', {
                                'param': param
                            });
                            $('#modalLikeBody').load('../app/loadModalPost.php', {
                                'param': param
                            });
                            $('#listComment').load('../app/loadCommentPost.php', {
                                'param': param
                            });
                        }

                        setInterval(function() {
                            load();
                        }, 1000);


                        document.getElementById("copy-button").addEventListener("click", function() {
                            copyToClipboard(document.getElementById("link-post"));
                        });

                        function copyToClipboard(elem) {
                            // create hidden text element, if it doesn't already exist
                            var targetId = "_hiddenCopyText_";
                            var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
                            var origSelectionStart, origSelectionEnd;
                            if (isInput) {
                                // can just use the original source element for the selection and copy
                                target = elem;
                                origSelectionStart = elem.selectionStart;
                                origSelectionEnd = elem.selectionEnd;
                            } else {
                                // must use a temporary form element for the selection and copy
                                target = document.getElementById(targetId);
                                if (!target) {
                                    var target = document.createElement("textarea");
                                    target.style.position = "absolute";
                                    target.style.left = "-9999px";
                                    target.style.top = "0";
                                    target.id = targetId;
                                    document.body.appendChild(target);
                                }
                                target.textContent = elem.textContent;
                            }
                            // select the content
                            var currentFocus = document.activeElement;
                            target.focus();
                            target.setSelectionRange(0, target.value.length);

                            // copy the selection
                            var succeed;
                            try {
                                succeed = document.execCommand("copy");
                                alert('copied')
                            } catch (e) {
                                succeed = false;
                            }
                            // restore original focus
                            if (currentFocus && typeof currentFocus.focus === "function") {
                                currentFocus.focus();
                            }

                            if (isInput) {
                                // restore prior selection
                                elem.setSelectionRange(origSelectionStart, origSelectionEnd);
                            } else {
                                // clear temporary content
                                target.textContent = "";
                            }
                            return succeed;
                        }
                    </script>
                </div>
            </div>
    </div>
</body>

</html>