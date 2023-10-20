<?php
session_start();
require_once "../../init.php";

$user = $_POST['userId'];
$people = $_POST['peopleId'];

$getData = $db->query("SELECT * FROM users WHERE user_id = '$user'");
$getData = $getData->fetch_assoc();
$theme = $getData['theme'];

$getListDirect = $db->query("SELECT * FROM direct_message WHERE(user_id = '$user' AND people_id = '$people') OR (user_id = '$people' AND people_id = '$user')");

?>

<div class="message mt-3 mb-5 pb-5" id="bodyMessage">
    <?php while ($fetch = mysqli_fetch_assoc($getListDirect)) :
    ?>

        <?php if ($fetch['user_id'] == $_SESSION['myapp_login']) {
        ?>
            <div class="d-flex justify-content-end m-2">
                <p class="shadow-sm text-white my-auto p-2 rounded <?= $theme == 'dark' ? 'bg-dark text-white' : 'bg-info' ?>" style="max-width: 90vw;"><?= $fetch['message'] ?></p>
            </div>
        <?php
        } else { ?>
            <div class="d-flex justify-content-start m-2">
                <p class="shadow-sm my-auto p-2 rounded <?= $theme == 'dark' ? 'bg-black border border-dark text-white' : '' ?>" style="max-width: 90vw;"><?= $fetch['message'] ?></p>
            </div>
        <?php } ?>

    <?php endwhile; ?>
</div>