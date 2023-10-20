<?php
require_once '../init.php';

$uuid = $_POST['uuid'];

$getStoryListByFollowingUser = $db->query("SELECT * FROM following WHERE user_id = '$uuid'");
while ($fetchGetListStory = mysqli_fetch_assoc($getStoryListByFollowingUser)) :
    $listStoryUserId = $fetchGetListStory['following'];
    $getStory = $db->query("SELECT * FROM story WHERE user_id = '$listStoryUserId' AND time > DATE_SUB(CURDATE(), INTERVAL 1 DAY) GROUP BY user_id ORDER BY time DESC");
?>


    <?php while ($fetchStory = mysqli_fetch_assoc($getStory)) :
        $userIdStory = $fetchStory['user_id'];
        $getProfile = $db->query("SELECT * FROM users WHERE user_id = '$userIdStory'");
        $fetchGetProfile = mysqli_fetch_assoc($getProfile);
    ?>
        <a href="<?= BASEURL ?>/story/?sid=<?= $fetchGetProfile['user_id'] ?>" class="nav-link">
            <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $fetchGetProfile['profile_picture'] ?>" alt="<?= $fetchGetProfile['username'] ?>" class="rounded-circle p-1" style="border: 2px solid #15AAEE" width="80px">
            <p class="mb-0 text-center" style="font-size: 14px;"><?= maxLength($fetchGetProfile['username'], 9) ?></p>
        </a>
    <?php endwhile; ?>

<?php endwhile;
?>