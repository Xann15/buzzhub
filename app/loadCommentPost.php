<?php
session_start();
require_once '../init.php';

if (!empty($_POST['param'])) { // i used $_REQUEST because it receives the data from POST or GET.
    $param = $_POST['param'];

    $getLikes = $db->query("SELECT * FROM likes WHERE post_id = '$param' ORDER BY id DESC");
    $like = mysqli_num_rows($getLikes);

    $getComment = $db->query("SELECT * FROM comment WHERE comment_id = '$param' ORDER BY id DESC");
}
?>

<div class="body-commentVideo p-0" id="commentPost">
    <?php while ($fetchComment = mysqli_fetch_assoc($getComment)) :
        $userComment = $fetchComment['user_id'];
        $getUserComment = $db->query("SELECT * FROM users WHERE user_id = '$userComment'");
        $fetchUserComment = mysqli_fetch_assoc($getUserComment);
    ?>
        <div class="user mb-1 p-1 rounded">
            <div class="body-user d-flex gap-2">
                <a class="d-flex my-auto" href="<?= BASEURL; ?>/profile/user/?id=<?= $fetchUserComment['user_id'] ?>">
                    <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $fetchUserComment['profile_picture'] ?>" alt="<?= $getData['username'] ?>" class="my-auto rounded-circle" style="width: 40px; height: 40px;">
                </a>
                <div class="info-user">
                    <p class="fw-bold mb-0">
                        <?= $fetchUserComment['name'] ?>
                        <?php if ($fetchUserComment['verified'] == 1) { ?>
                            <i class="bi bi-patch-check-fill text-info"></i>
                        <?php } ?>
                        <span class="text-muted" style="font-size: 11px">• <?= time_elapsed_string($fetchComment['createdAt']) ?></span>
                    </p>
                    <p class="text-muted mb-0" style="font-size: 12px; transform: translateY(-20%)">
                        @<?= $fetchUserComment['username'] ?>
                    </p>
                </div>
            </div>
            <p class="text my-auto" style="margin-left: 45px; transform: translateY(-15%)"><?= $fetchComment['comment'] ?></p>
        </div>
    <?php endwhile; ?>
</div>