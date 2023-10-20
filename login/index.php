<?php
session_start();

require_once '../init.php';

if (isset($_SESSION['myapp_login'])) {
    $_SESSION['failed'] = "Oops, you can't access the page, because you're already logged in";
    header('location: ' . BASEURL);
    exit;
}

if (isset($_POST['login'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    $query = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");
    $data = mysqli_fetch_assoc($query);

    if ($username && $password) {
        if (mysqli_num_rows($query) == 1) {
            if (password_verify($password, $data['password'])) {
                $_SESSION['success'] = "Successful Login";
                $_SESSION['myapp_login'] = $data['user_id'];
                $_SESSION['welcome_user'] = true;

                $currentTime = date("Y:m:d H:i:s");
                isset($_COOKIE['notification']) ? setcookie('notification', $currentTime, time() + 31536000, '/myapp', '192.168.1.10') : setcookie('notification', $currentTime, time() + 31536000, '/myapp', '192.168.1.10');
                if (isset($_COOKIE['notification'])) {
                    $time = $_COOKIE['notification'];
                    $numberofNewNotification = totalNotification($time);
                }

                if ($data['role'] == "admin") {
                    $_SESSION['myapp_admin'] = 'true';
                }

                header('location: ' . BASEURL);
                exit;
            } else {
                $_SESSION['failed'] = "Incorrect username or password!";
                header('location: ' . BASEURL . '/login');
                exit;
            }
        } else {
            $_SESSION['failed'] = "User not found...";
            header('location: ./');
            exit;
        }
    } else {
        $_SESSION['failed'] = "Please enter username and password...";
        header('location: ./');
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

    <!-- Favicon -->
    <link href="<?= BASEURL; ?>/assets/img/favicon.png" rel="icon">

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <title>Login | <?= TITLE_SITE ?></title>

</head>

<body>


    <?php
    require_once '../components/header.php';
    ?>
    <div class="app" id="app">
        <div class="wrapper">
            <div class="logo">
                <img src="../assets/img/logo_transparent.png" alt="">
            </div>
            <div class="text-center mt-4 name">
                <?= TITLE_SITE; ?>
            </div>
            <form action="" method="POST" class="p-3 mt-3">
                <div class="form-field d-flex align-items-center">
                    <span class="bi bi-person fs-5"></span>
                    <input type="text" name="username" id="username" placeholder="Username">
                </div>
                <div class="form-field d-flex align-items-center">
                    <span class="bi bi-key fs-5"></span>
                    <input type="password" name="password" id="password" placeholder="Password">
                    <span class="show-password bi bi-eye-slash fs-5 p-3"></span>
                </div>
                <div class="form-check px-2">
                    <input class="form-check-input" type="checkbox" name="rememberMe" id="rememberMe">
                    <label for="rememberMe" class="checkbox text-muted">
                        remember me
                    </label>
                </div>
                <button id="login" name="login" type="submit" class="btn mt-3">Login</button>
            </form>
            <div class="text-center fs-6">
                <a href="#">Forget password?</a> or <a href="<?= BASEURL; ?>/signup">Create an account.</a>
            </div>
        </div>
    </div>


    </div>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        $(".show-password").on("click", function(e) {
            e.preventDefault();
            $(this).toggleClass("bi-eye bi-eye-slash");
            var input = $("#password");
            input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password');
        });
    </script>
</body>

</html>