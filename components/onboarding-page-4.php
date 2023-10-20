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

    <title><?= TITLE_SITE ?></title>
</head>

<body>
    <div class="container">
        <div class="header mt-5">
            <lottie-player class="mx-auto mt-5 pt-5 animate__animated animate__fadeIn" src="https://assets6.lottiefiles.com/packages/lf20_f2jo61ci.json" background="transparent" speed="1" style="width: 360px; height: 360px;" loop autoplay></lottie-player>
        </div>
        <div class="main mt-5 pt-5 animate__animated animate__fadeInUp animate__delay-1s">
            <p class="display-6 fs-3 text-center fw-bold">Find more friends</p>
            <p class="mb-4 text-center">Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui quo ab vel quaerat, eveniet consequuntur?</p>
            <form action="" method="post" class="d-flex">
                <button class="col-12 col-md-8 col-lg-6 mx-auto btn text-white rounded-3 animate__animated animate__fadeInUp animate__delay-1s" type="submit" name="getCookie" style="border: none; background-color: #FF3B5C;">
                    GETTING STARTED
                </button>
            </form>
        </div>
    </div>

    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
</body>

</html>