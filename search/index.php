<?php
session_start();

require_once '../init.php';

!isset($_SESSION['myapp_login']) ? header('location: ' . BASEURL . '/login') : '';


$uuid = $_SESSION['myapp_login'];
$get = $db->query("SELECT * FROM users WHERE user_id = '$uuid'");
$getData = mysqli_fetch_assoc($get);
$theme = $getData['theme'];

isset($_GET['q']) ? $search = $_GET['q'] : $search = '';


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

    <title>Search | <?= TITLE_SITE ?></title>

</head>

<body class="<?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
    <div class="d-flex">
        <div class="d-none d-md-none d-lg-flex col-2">
            <?php include '../components/sidenav.php' ?>
        </div>

        <div style="width: 100vw;">
            <nav id="nav" class="navbar shadow-sm navbar-expand <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
                <div class="container">
                    <a href="javascript:history.back()" class="d-flex gap-2 nav-link mx-2 d-lg-none">
                        <i class="bi bi-arrow-left my-auto fs-4 fw-bold"></i>
                    </a>
                    <!-- Search User -->
                    <div class="col-10 col-md-12 col-lg-10 m-auto">
                        <form action="" method="post">
                            <div class="input-group">
                                <input style="border: none;" autofocus type="text" name="keywords" value="<?php if ($search != '') {
                                                                                                                echo $search;
                                                                                                            } else {
                                                                                                                echo '';
                                                                                                            }  ?>" id="keywords" class="form-control <?= $theme == 'dark' ? 'bg-dark text-white' : '' ?>" placeholder="Search..." aria-label="Search..." aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button style="border: none;" type="button" name="search_user" class="input-group-text <?= $theme == 'dark' ? 'bg-black border border-dark text-white' : '' ?>" id="basic-addon2"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </nav>

            <div class="mb-5 py-2 p-1">
                <!-- Result -->
                <div class="result-query p-2" id="result-query">

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('#keywords').keypress(
                function(event) {
                    if (event.which == '13') {
                        event.preventDefault();
                    }
                });

            if ($('#keywords') != '') {
                $.ajax({
                    url: 'logicSearch.php',
                    method: 'POST',
                    data: {
                        key: $('#keywords').val()
                    },
                    dataType: "text",
                    success: function(data) {
                        $('#result-query').html(data);
                    }
                })
            }
            $('#keywords').keyup(function() {

                $.ajax({
                    url: 'logicSearch.php',
                    method: 'POST',
                    data: {
                        key: $('#keywords').val()
                    },
                    dataType: "text",
                    success: function(data) {
                        $('#result-query').html(data);
                    }
                })
            })
        });
    </script>
</body>

</html>