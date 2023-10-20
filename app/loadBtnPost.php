<?php
session_start();
require_once '../init.php';

if (!empty($_POST['param'])) { // i used $_REQUEST because it receives the data from POST or GET.
    $param = $_POST['param'];
}
?>

<?php if (isset($_SESSION['myapp_login'])) {
    $uuid = $_SESSION['myapp_login'];
    $getUser = $db->query("SELECT * FROM users WHERE user_id = '$uuid'");
    $getData = $getUser->fetch_assoc();
    $theme = $getData['theme'];
    $isLikedByUser = $db->query("SELECT * FROM likes WHERE user_id = '$uuid' AND post_id = '$param'");
    $isLiked = mysqli_num_rows($isLikedByUser);
    if ($isLiked == 1) { ?>
        <button type="button" id="likeBtn" name="likeBtn" class="btn p-0 my-auto" style="border: none"><i class="bi bi-heart-fill fs-4 <?= $theme == 'dark' ? 'text-danger' : 'text-danger' ?>"></i></button>
    <?php } else { ?>
        <button type="button" id="likeBtn" name="likeBtn" class="btn p-0 my-auto" style="border: none"><i class="bi bi-heart fs-4 <?= $theme == 'dark' ? 'text-light' : 'text-dark' ?>"></i></button>
<?php }
}
?>