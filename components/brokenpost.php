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
     
    <title>MyApp</title>

</head>
<body>
    
<?php if(!isset($_SESSION['myapp_login'])) { ?>
    <nav id="nav" class="navbar fixed-top shadow-sm navbar-expand p-0">
        <div class="container">
            <a class="navbar-brand d-flex gap-2" href="<?= BASEURL; ?>">
                <img class="rounded-circle" src="<?= BASEURL; ?>/assets/img/logo.png" alt="icon" style="width: 65px; height: 65px;">
                <p class="nav nav-title my-auto fw-bold"><?= TITLE_SITE; ?></p>
            </a>
            <a class="nav-link nav-title mx-2" aria-current="page" href="<?= BASEURL; ?>/login"><i class="fs-4 bi bi-person-circle"></i></a>
        </div>
    </nav>
    <?php } else { ?>
    <nav id="nav" class="navbar fixed-top shadow-sm p-0 py-1">
        <div class="container">
            <a class="navbar-brand d-flex gap-2" href="<?= BASEURL; ?>/profile">
                <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $getData['profile_picture'] ?>" alt="<?= $getData['username'] ?>" class="rounded-circle" style="width: 45px; height: 45px;">
            </a>
            <a href="<?= BASEURL; ?>" class="nav-title nav-link mx-auto fw-bold my-auto"><?= TITLE_SITE; ?></a>
            <a class="nav-title nav-link mx-2 fw-bold" aria-current="page" href="<?= BASEURL; ?>/reels">Reels</a>
        </div>
    </nav>
    <?php } ?>



    <div class="container py-5 my-5">
        <h1 class="display-6">Oops, this url is broken or the post has been removed. <a href="<?= BASEURL ?>">back to home</a></h1>
    </div>
</body>