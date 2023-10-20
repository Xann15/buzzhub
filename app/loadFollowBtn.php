<?php

session_start();
require_once '../init.php';

if (isset($_POST['param'])) { // i used $_REQUEST because it receives the data from POST or GET.
    $param = $_POST['param'];
}
?>

<?php if (isset($_SESSION['myapp_login'])) {
    $uid = $_SESSION['myapp_login'];
    $isFollow = $db->query("SELECT * FROM following WHERE user_id = '$uid' AND following = '$param'");
    $isFollow = mysqli_num_rows($isFollow);
    if ($isFollow == 1) { ?>
        <button type="button" id="followBtn" name="follow" class="btn py-0 px-3 text-light" style="border-radius: 8px; border:none; background-color: #FF3B5C;">
            <i class="bi bi-person-check-fill text-light"></i>
        </button>
    <?php } else { ?>
        <button type="button" id="followBtn" name="follow" class="btn text-light py-0 px-4" style="border-radius: 8px; border:none; background-color: #FF3B5C;">Follow</button>

<?php }
} ?>