<?php
session_start();

require_once '../init.php';

if (!isset($_SESSION['myapp_login'])) {
    $_SESSION['failed'] = "Please log in first..";
    header('location: ' . BASEURL);
    exit;
}

$uuid = $_SESSION['myapp_login'];

$getData = $db->query("SELECT * FROM users WHERE user_id = '$uuid'");
$getData = $getData->fetch_assoc();
$theme = $getData['theme'];

if (isset($_POST['dark-mode'])) {
    $db->query("UPDATE users SET theme = 'dark' WHERE user_id = '$uuid'");
    header('refresh:0;url=');
}


if (isset($_POST['light-mode'])) {
    $db->query("UPDATE users SET theme = 'light' WHERE user_id = '$uuid'");
    header('refresh:0;url=');
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

    <title>MyApp</title>

</head>

<body class="<?= $theme == 'dark' ? 'bg-black text-white' : 'bg-white' ?>">

    <?php

    if (isset($_POST['logout'])) {
        // var_dump($_SESSION);exit;
        session_unset();
        $_SESSION['success'] = "Logged out successfully";
        header('location: ' . BASEURL);
        exit;
    }
    ?>

    <nav id="nav" class="navbar fixed-top navbar-expand shadow-sm p-1 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
        <div class="container d-flex justify-content-start gap-4">
            <div class="back mx-1">
                <a href="<?= BASEURL ?>/profile" class="nav-link my-auto">
                    <i class="bi bi-arrow-left fs-2"></i>
                </a>
            </div>
            <p class="my-auto fs-4 fw-bold">Back</p>
        </div>
    </nav>

    <div class="container py-5 my-5">
        <div class="app" id="app">
            <form action="" method="post">
                <input type="submit" name="logout" class="btn btn-primary px-3 py-0 rounded-pill" value="Logout">
            </form>
            <h1 class="display-6 animate__animated animate__fadeInUp">Settings</h1>
        </div>

        <div class="col-12 col-md-8 col-lg-6">
            <div class="theme d-flex justify-content-between p-2 rounded-4 shadow-sm <?= $theme == 'dark' ? 'bg-dark text-white' : 'bg-white' ?>">
                <p class="fw-bold fs-4 my-auto">Theme</p>
                <div class="dropdown">
                    <button class="btn dropdown-toggle <?= $theme == 'dark' ? 'text-white' : '' ?>" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Select Theme
                    </button>
                    <ul class="dropdown-menu <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
                        <li>
                            <form action="" method="post">
                                <button type="submit" class="btn dropdown-item rounded-0 <?= $theme == 'dark' ? 'text-white' : '' ?>" name="dark-mode">
                                    <i class="bi bi-moon"></i>
                                    Dark Mode
                                </button>
                            </form>
                        </li>
                        <li>
                            <form action="" method="post">
                                <button type="submit" class="btn dropdown-item rounded-0 <?= $theme == 'dark' ? 'text-white' : '' ?>" name="light-mode">
                                    <i class="bi bi-sun"></i>
                                    Light Mode
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <script src="../assets/js/bootstrap.bundle.min.js"></script>

</body>

</html>