<?php
session_start();

require_once '../init.php';

!isset($_SESSION['myapp_login']) ? header('location: ' . BASEURL . '/login') : '';


$uuid = $_SESSION['myapp_login'];
$get = $db->query("SELECT * FROM users WHERE user_id = '$uuid'");
$getData = mysqli_fetch_assoc($get);
$theme = $getData['theme'];



isset($_GET['q']) ? $search = $_GET['q'] : $search = '';

$getPost = $db->query("SELECT id, type, post FROM posts WHERE type != 'tweet' ORDER BY id DESC");

if (isset($_POST['search_user'])) {
    $keyword = $_POST['keyword'];
    header('Location: ' . BASEURL . '/search/?q=' . $keyword);
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

    <title>Explore | <?= TITLE_SITE ?></title>

</head>

<body class="<?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
    <nav id="nav" class="navbar fixed-top shadow-sm navbar-expand d-lg-none d-md-none <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
        <div class="container">
            <a href="javascript:history.back()" class="d-flex gap-2 nav-link mx-2">
                <i class="bi bi-arrow-left my-auto fs-4 fw-bold"></i>
            </a>
            <!-- Search User -->
            <div class="col-10 m-auto">
                <form action="" method="post">
                    <div class="input-group">
                        <input style="border: none;" type="text" name="keyword" id="keywords" class="form-control <?= $theme == 'dark' ? 'bg-dark text-white' : '' ?>" placeholder="Search..." aria-label="Search..." aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button style="border: none;" type="submit" name="search_user" class="input-group-text <?= $theme == 'dark' ? 'bg-black border border-dark text-white' : '' ?>" id="basic-addon2"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <div class="d-lg-none d-md-none">
        <?php
        require_once '../components/bottom_nav.php';
        ?>
    </div>

    <div class="d-flex">
        <div class="d-none d-md-none d-lg-flex col-2 shadow-dark-mode">
            <?php include '../components/sidenav.php' ?>
        </div>

        <div class="d-none d-md-flex d-lg-none col-1">
            <?php include '../components/sidenav_icon.php' ?>
        </div>

        <div class="my-5 my-lg-3 py-2 p-1 d-flex">
            <div class="col-xl-9 col-lg-11 col-md-12 p-md-2 mx-auto">
                <div class="row row-cols-3 row-cols-lg-3 g-1 g-lg-2" id="post">
                    <?php
                    while ($fetchPost = mysqli_fetch_assoc($getPost)) :
                        $postId = $fetchPost['id'];
                        $getViews = $db->query("SELECT * FROM views WHERE post_id = '$postId'");
                        $views = mysqli_num_rows($getViews);
                    ?>
                        <div class="col">
                            <a href="<?= BASEURL ?>/post/?p=<?= $fetchPost['id'] ?>" class="shadow m-0 d-flex" style="height: 200px; overflow: hidden; position: relative;">
                                <?php if ($fetchPost['type'] == 'video') { ?>
                                    <div class="video-overlay d-flex">
                                        <i class="bi bi-play fs-5 text-white fw-bold"></i>
                                        <p class="my-auto text-white" style="font-size: 14px;"><?= $views ?></p>
                                    </div>
                                    <video class="d-block mx-auto" style="height: 200px;" src="<?= BASEURL; ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" loading="lazy">
                                    <?php } elseif ($fetchPost['type'] == 'post') { ?>
                                        <img src="<?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" class="d-block mx-auto" style="height: 200px;" loading="lazy">
                                    <?php } ?>
                            </a>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#keywords').click(function() {
            window.location.href = '../search';
        })
    </script>
</body>

</html>