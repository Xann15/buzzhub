<?php
session_start();

require_once '../../init.php';

if (!isset($_SESSION['myapp_login'])) {
    header('location: ' . BASEURL . '/login');
    exit;
}
$userId = $_SESSION['myapp_login'];
$peopleId = $_GET['direct'];

$getFollowers = $db->query("SELECT * FROM followers WHERE user_id = '$peopleId'");
$getPosts = $db->query("SELECT * FROM posts WHERE user_id = '$peopleId'");

$followers = mysqli_num_rows($getFollowers);
$posts = mysqli_num_rows($getPosts);

$getListDirectMessage = $db->query("SELECT * FROM direct_message WHERE user_id = '$userId' AND people_id = '$peopleId'");
$fetchDirect = mysqli_fetch_assoc($getListDirectMessage);

$getListDirect = $db->query("SELECT * FROM direct_message WHERE(user_id = '$userId' AND people_id = '$peopleId') OR (user_id = '$peopleId' AND people_id = '$userId')");


$getUserDirect = $db->query("SELECT * FROM users WHERE user_id = '$peopleId'");
$getUserDirect = mysqli_fetch_assoc($getUserDirect);


$getData = $db->query("SELECT * FROM users WHERE user_id = '$userId'");
$getData = $getData->fetch_assoc();

$theme = $getData['theme'];
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

    <title>Chat | MyApp</title>

</head>

<body class="<?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
    <?php if (isset($_SESSION['myapp_login'])) { ?>
        <nav id="nav" class="navbar fixed-top shadow-sm p-2" style="background: transparent;">
            <div class="container">
                <a href="javascript:history.back()" class="d-flex gap-2 nav-link">
                    <i class="bi bi-arrow-left my-auto fs-4 fw-bold"></i>
                    <div class="d-flex gap-2 fs-5">
                        <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $getUserDirect['profile_picture'] ?>" class="rounded-circle" width="40px" alt="<?= $getUserDirect['username'] ?>">
                        <p class="my-auto">
                            <span class="fw-bold">
                                <?= $getUserDirect['name']; ?>
                            </span>
                            <?php
                            if ($getUserDirect['verified'] == 1) {
                            ?>
                                <i class="bi bi-patch-check-fill fs-6 text-info"></i>
                            <?php } ?>
                        </p>
                    </div>
                </a>
            </div>
        </nav>
    <?php } ?>
    <div class="container pt-2 mt-5 d-flex p-0">

        <div class="direct-message col-12 mt-3">
            <input type="hidden" name="userId" id="userId" value="<?= $userId ?>">
            <input type="hidden" name="peopleId" id="peopleId" value="<?= $peopleId ?>">
            <a href="<?= BASEURL ?>/profile/user/?id=<?= $getUserDirect['user_id'] ?>" class="nav-link">
                <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $getUserDirect['profile_picture'] ?>" class="rounded-circle mx-auto d-block mb-3" width="100px" alt="<?= $getUserDirect['username'] ?>">
                <p class="text-center mb-0 fw-bold fs-4">
                    <?= $getUserDirect['name']; ?>
                </p>
                <p class="text-center text-muted mb-0">@<?= $getUserDirect['username'] ?></p>
                <p class="text-center text-muted mb-0" style="transform: translateY(-15%)"><?= $followers ?> followers • <?= $posts ?> posts</p>
            </a>

            <div class="message mt-3 mb-5 pb-5" id="bodyMessage">
                <?php while ($fetch = mysqli_fetch_assoc($getListDirect)) :
                ?>

                    <?php if ($fetch['user_id'] == $_SESSION['myapp_login']) {
                    ?>
                        <div class="d-flex justify-content-end m-2">
                            <p class="shadow-sm text-light my-auto p-2 rounded <?= $theme == 'dark' ? 'bg-dark text-white' : 'bg-info' ?>" style="max-width: 90vw;"><?= $fetch['message'] ?></p>
                        </div>
                    <?php
                    } else { ?>
                        <div class="d-flex justify-content-start m-2">
                            <p class="shadow-sm my-auto p-2 rounded <?= $theme == 'dark' ? 'bg-black border border-dark text-white' : '' ?>" style="max-width: 90vw;"><?= $fetch['message'] ?></p>
                        </div>
                    <?php } ?>

                <?php endwhile; ?>
            </div>

            <div class="footer fixed-bottom" style="transform: translateY(20%);">
                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input name="message" id="message" style="border: none;" class="form-control <?= $theme == 'dark' ? 'bg-black border border-dark text-white' : '' ?>"></inp>
                        <div class="input-group-append">
                            <button type="button" name="sendMessage" id="sendMessage" class="input-group-text shadow-sm <?= $theme == 'dark' ? 'border border-dark bg-black text-white' : '' ?>" style="border: none;" id="basic-addon2"><i class="bi bi-send fs-4"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>


    <script>
        $(document).ready(function() {
            $('#sendMessage').click(function() {
                $.ajax({
                    url: "sendMessage.php",
                    method: "POST",
                    data: {
                        userId: $('#userId').val(),
                        peopleId: $('#peopleId').val(),
                        message: $('#message').val()
                    },
                    dataType: "text",
                    success: function(data) {
                        $('#message').val("");
                    }
                })
            });




            setInterval(() => {
                $.ajax({
                    url: "realTimeDirect.php",
                    method: "POST",
                    data: {
                        userId: $('#userId').val(),
                        peopleId: $('#peopleId').val()
                    },
                    dataType: "text",
                    success: function(data) {
                        $('#bodyMessage').html(data);
                    }
                });
            }, 700);
        });
    </script>
</body>


</html>