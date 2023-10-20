<?php
session_start();
require_once '../../init.php';

if (!isset($_SESSION['myapp_login'])) {
    $_SESSION['failed'] = 'oops..before access the page you need login.';
    header('Location: ' . BASEURL . '/login');
    exit;
}

$uuid = $_SESSION['myapp_login'];
$query = $db->query("SELECT * FROM users WHERE user_id = '$uuid'");
$getData = mysqli_fetch_assoc($query);

$theme = $getData['theme'];


if (isset($_POST['upload'])) {
    if (uploadPost($_POST) > 0) {
        $_SESSION['success'] = "Uploaded";
        header('location: ' . BASEURL);
        exit;
    } else {
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

    <!-- Favicon -->
    <link href="<?= BASEURL; ?>/assets/img/favicon.png" rel="icon">

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.js"></script>

    <title>Upload Post | <?= TITLE_SITE ?></title>

</head>

<body class="<?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
    <nav id="nav" class="navbar fixed-top shadow-sm <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
        <div class="container">
            <a href="<?= BASEURL ?>" class="nav-title nav-link fw-bold d-flex gap-2">
                <i class="bi bi-arrow-left fs-4 my-auto"></i>
                <p class="my-auto fs-5"><?= TITLE_SITE; ?></p>
            </a>
        </div>
    </nav>

    <?php if (isset($_SESSION['myapp_login'])) {
        require_once '../../components/bottom_nav.php';
    } else {
        require_once '../../components/bottom_nav_guest.php';
    } ?>

    <div class="container mt-5 py-5">
        <!-- Alert -->
        <?php if (isset($_SESSION['success'])) { ?>
            <div class="alert alert-success alert-dismissible animate__animated animate__fadeInUp animate__delay-1s">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php }
        unset($_SESSION['success']); ?>


        <!-- Alert -->
        <?php if (isset($_SESSION['failed'])) { ?>
            <div class="alert alert-danger alert-dismissible animate__animated animate__fadeInUp animate__delay-1s">
                <?= $_SESSION['failed'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php }
        unset($_SESSION['failed']); ?>
    </div>

    <!-- Upload Post Images -->
    <div class="upload-img mb-5 pb-3" id="upload-content">
        <p class="display-6 text-center mb-0">Upload Something</p>
        <p class="text-muted text-center">upload a photo or video less than 25MB.</p>
        <form action="" method="post" enctype="multipart/form-data">
            <img src="" id="prev-image" class="d-block w-100 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>" alt="">
            <video src="" id="prev-media" autoplay loop class="img-thumbnail border border-0 d-block mx-auto <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>"></video>

            <div class="container">
                <input type="file" onchange="readURL(this)" name="media" id="media" class="form-control mt-2 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">

                <div class="body-content my-4">

                    <div class="commnet mb-3">
                        <div class="form-check px-2">
                            <input class="form-check-input" type="checkbox" name="allow-comment" id="allow-comment">
                            <label for="allow-comment" class="checkbox <?= $theme == 'dark' ? 'bg-black text-white' : 'text-black' ?>">
                                allow comment
                            </label>
                        </div>
                    </div>

                    <div class="caption mb-3">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Caption</label>
                            <textarea name="caption" id="caption" class="form-control <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>" id="exampleFormControlTextarea1" rows="2"></textarea>
                        </div>
                    </div>

                    <div class="upload col-12 d-flex">
                        <button type="submit" name="upload" id="upload" class="btn btn-dark px-3 py-1 rounded-pill mx-auto">
                            <i class="bi bi-upload"></i> Upload
                        </button>
                    </div>

                </div>
            </div>
    </div>

    </form>
    </div>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#prev-image')
                        .attr('src', e.target.result);
                    $('#upload-content')
                        .addClass('preview-img');
                    $('#prev-media')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>