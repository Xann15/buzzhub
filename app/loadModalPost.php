<?php
session_start();
require_once '../init.php';

if (!empty($_POST['param'])) { // i used $_REQUEST because it receives the data from POST or GET.
    $param = $_POST['param'];

    $getLikes = $db->query("SELECT * FROM likes WHERE post_id = '$param' ORDER BY id DESC");
    $like = mysqli_num_rows($getLikes);
}
?>

<div class="modal-body p-0" id="modalLikeBody">
    <?php
    while ($fetchLike = mysqli_fetch_assoc($getLikes)) :
        $x = $fetchLike['user_id'];
        $getUser = $db->query("SELECT username, verified, name, profile_picture FROM users WHERE user_id = $x");
        $getUser = mysqli_fetch_assoc($getUser);
    ?>
        <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchLike['user_id'] ?>" class="user nav-link d-flex justify-content-between shadow-sm rounded mb-2 px-2 p-1">
            <div class="left d-flex gap-2">
                <div class="img my-auto">
                    <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $getUser['profile_picture'] ?>" alt="<?= $getUser['username'] ?>" width="40px" class="rounded-circle">
                </div>
                <div class="username my-auto">
                    <div class="uname d-flex gap-1">
                        <p class="fw-bold mb-0"><?= $getUser['name'] ?></p>
                        <?php
                        if ($getUser['verified'] == 1) {
                        ?>
                            <i class="bi bi-patch-check-fill text-info"></i>
                        <?php } ?>
                    </div>

                    <p class="text-muted mb-0" style="font-size: 12px;">@<?= $getUser['username'] ?></p>
                </div>
            </div>
            <?php if (isset($_SESSION['myapp_login'])) {
                $uid = $_SESSION['myapp_login'];
                $uids = $fetchLike['user_id'];
                $isFollow = $db->query("SELECT * FROM following WHERE user_id = '$uid' AND following = '$uids'");
                $isFollow = mysqli_num_rows($isFollow);
                if ($uid != $uids) {
            ?>
                    <form action="" method="post" class="my-auto">
                        <input type="hidden" name="uid" value="<?= $uids ?>">
                        <?php if ($isFollow == 1) { ?>
                            <button type="submit" name="follow" class="btn py-0 px-3 text-white shadow-sm my auto" style="background-color: #FF3B5C;"><i class="bi bi-person-check-fill"></i> Friends</button>
                        <?php } else { ?>
                            <button type="submit" name="follow" class="btn text-white shadow-sm py-0 px-3 my auto" style="background-color: #FF3B5C;">Follow</button>
                        <?php } ?>
                    </form>
            <?php }
            } ?>
        </a>
    <?php endwhile; ?>
    <p class="display-6 fs-6 mt-3 mb-0 fw-bold text-center"><?= TITLE_SITE ?></p>
</div>