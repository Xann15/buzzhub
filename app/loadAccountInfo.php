<?php
require_once '../init.php';

if (!empty($_POST['param'])) { // i used $_REQUEST because it receives the data from POST or GET.
    $param = $_POST['param'];

    $getFollowers = $db->query("SELECT * FROM followers WHERE user_id = '$param'");
    $getFollowing = $db->query("SELECT * FROM following WHERE user_id = '$param'");
    $getAllLikes = $db->query("SELECT * FROM likes WHERE posted_id = '$param'");


    $followers = mysqli_num_rows($getFollowers);
    $following = mysqli_num_rows($getFollowing);
    $userLikes = mysqli_num_rows($getAllLikes);
}
?>
<div id="accountInfo" class="d-flex account-info gap-3 justify-content-center">
    <div class="d-lg-flex gap-1" style="width: 100px">
        <p class="fw-bold text-center mb-0" style="font-size: 15px;"><?= number_format_short($following) ?></p>
        <p class="mb-0 text-center" style="font-size: 15px;">Following</p>
    </div>
    <div class="d-lg-flex gap-1">
        <p class="fw-bold text-center mb-0" style="font-size: 15px;"><?= $userLikes ?></p>
        <p class="mb-0" style="font-size: 15px;">Likes</p>
    </div>
    <div class="d-lg-flex gap-1" style="width: 100px">
        <p class="fw-bold text-center mb-0" style="font-size: 15px;"><?= number_format_short($followers) ?></p>
        <p class="mb-0 text-center" style="font-size: 15px;">Followers</p>
    </div>
</div>