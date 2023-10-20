<?php
session_start();
require_once "../init.php";

$userId = $_SESSION['myapp_login'];

$getData = $db->query("SELECT * FROM users WHERE user_id = '$userId'");
$getData = $getData->fetch_assoc();
$theme = $getData['theme'];

$getListDirectMessage = $db->query("SELECT DISTINCT
                                        CASE WHEN user_id < people_id THEN user_id ELSE people_id END AS user_id,
                                        CASE WHEN user_id < people_id THEN people_id ELSE user_id END AS people_id,
                                        last_message,
                                        time
                                    FROM
                                        list_direct_message
                                    WHERE
                                        user_id = '$userId' OR people_id = '$userId'
                                    ORDER BY
                                        time DESC");

while ($fetchList = mysqli_fetch_assoc($getListDirectMessage)) :
    if ($fetchList['user_id'] == $_SESSION['myapp_login']) {
        $userListId = $fetchList['people_id'];
    } else {
        $userListId = $fetchList['user_id'];
    }
    $getUserData = $db->query("SELECT * FROM users WHERE user_id = '$userListId'");
    $getUserData = mysqli_fetch_assoc($getUserData);
?>
    <a href="<?= BASEURL ?>/chat/t/?direct=<?= $userListId ?>" class="list-direct-message nav-link rounded-4 shadow-sm mb-1 <?= $theme == 'dark' ? 'bg-dark text-white' : '' ?>">
        <input type="hidden" name="peopleId" id="peopleId" value="<?= $userListId ?>">
        <div class="room d-flex justify-content-between">
            <div class="left d-flex gap-2 p-2">
                <div class="profile">
                    <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $getUserData['profile_picture'] ?>" class="rounded-circle" width="50px" alt="profile picture <?= $getUserData['username'] ?>">
                </div>
                <div class="room-desc row">
                    <p class="display-6 fs-6 my-auto mb-0">
                        <?php
                        if ($getUserData['name'] != '') {
                            echo $getUserData['name'];
                        } else {
                            echo $getUserData['username'];
                        }

                        if ($getUserData['verified'] == 1) {
                        ?>
                            <i class="bi bi-patch-check-fill text-info"></i>
                        <?php } ?>
                    </p>
                    <p class="text-muted my-auto mt-0"><?= maxLength($fetchList['last_message'], 25) ?></p>
                </div>
            </div>
            <div class="time d-flex p-3">
                <p class="text-muted m-auto" style="font-size: 14px;"><?= time_elapsed_string($fetchList['time']) ?></p>
            </div>
        </div>
    </a>
<?php endwhile ?>