<?php
require_once '../init.php';

if (!empty($_POST['param'])) { // i used $_REQUEST because it receives the data from POST or GET.
    $postId = $_POST['param'];
    $getLike = $db->query("SELECT * FROM likes WHERE post_id = '$postId'");
    $like = mysqli_num_rows($getLike);
}
?>

<span class="total-likes" id="total-likes"><?= $like ?></span>