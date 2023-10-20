<?php
session_start();

require_once '../init.php';

if (isset($_SESSION['myapp_login'])) {
    $_SESSION['failed'] = "Oops, you can't access the page, because you're already logged in";
    header('location: ' . BASEURL);
    exit;
}

!isset($_COOKIE['chooseAlgorithm']) ? setcookie('chooseAlgorithm', 'ID*#$aisdSBSC(#$+', time() + 86400) : '';


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

    <title>Sign Up | <?= TITLE_SITE ?></title>

</head>

<body>

    <?php
    require_once '../components/header.php';
    ?>

    <div class="container pb-2 my-5">

        <?php
        if (isset($_POST['signup'])) {

            $name = htmlspecialchars($_POST['name']);
            $username = strtolower(htmlspecialchars($_POST['username']));
            $password = htmlspecialchars($_POST['password']);
            $passwordVerify = htmlspecialchars($_POST['passwordVerify']);
            $defaultPicture = "default.jpg";
            $date = date('m/Y');

            $query1 = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");
            $data = mysqli_fetch_assoc($query1);


            if ($username && $password && $passwordVerify) {
                //? jika username sudah digunakan 
                if (mysqli_num_rows($query1) == 1) {
                    $_SESSION['failed'] = "Username already exists, please try another...";
                    header('location: ./');
                    exit;
                }

                //? jika password verify tidak sama dengan password
                if ($passwordVerify != $password) {
                    $_SESSION['failed'] = "Passwords don't match";
                    header('location: ./');
                    exit;
                }


                $password = password_hash($password, PASSWORD_DEFAULT);
                $query = mysqli_query($db, "INSERT INTO users(role, profile_picture, account, name, username, password, joined) VALUES('user','$defaultPicture', 'public', '$name','$username','$password','$date')");
                $_SESSION['success'] = "Successfully created account";

                //? ambil user_id setelah dibuat akun
                $query2 = mysqli_query($db, "SELECT * FROM users WHERE username = '$username'");
                $data2 = mysqli_fetch_assoc($query2);

                $_SESSION['myapp_login'] = $data2['user_id'];

                if (isset($_COOKIE['chooseAlgorithm'])) {
                    header('location: ' . BASEURL . '/signup/next');
                    exit;
                }


                header('location: ' . BASEURL);
                exit;
            } else {
                $_SESSION['failed'] = "Please complete user data...";
                header('location: ./');
                exit;
            }
        }

        ?>

        <div class="app" id="app">
            <div class="wrapper">
                <div class="logo">
                    <img src="../assets/img/logo_transparent.png" alt="">
                </div>
                <div class="text-center mt-4 name">
                    <?= TITLE_SITE ?>
                </div>
                <form action="" method="POST" class="p-3 mt-3">
                    <div class="form-field d-flex align-items-center">
                        <span class="bi bi-person fs-5"></span>
                        <input type="text" name="name" id="name" placeholder="Name">
                    </div>
                    <div class="form-field d-flex align-items-center">
                        <span class="fs-5">@</span>
                        <input type="text" name="username" id="username" placeholder="Username">
                    </div>
                    <div class="form-field d-flex align-items-center">
                        <span class="bi bi-key fs-5"></span>
                        <input type="password" name="password" id="password" placeholder="Password">
                        <span class="show-password bi bi-eye-slash fs-5 p-3"></span>
                    </div>
                    <div class="form-field d-flex align-items-center">
                        <span class="bi bi-key fs-5"></span>
                        <input type="password" class="verify-password" name="passwordVerify" id="passwordVerify" placeholder="Verify Password">
                    </div>
                    <button id="signup" name="signup" type="submit" class="btn mt-3">Signup</button>
                </form>
                <div class="text-center fs-6">
                    <a href="<?= BASEURL; ?>/login">already have an account?</a>
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