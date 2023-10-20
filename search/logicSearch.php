<?php
session_start();
require_once "../init.php";

$key = $_POST['key'];

if (isset($_SESSION['myapp_login'])) {
    $uuid = $_SESSION['myapp_login'];
    $getData = $db->query("SELECT * FROM users WHERE user_id = '$uuid'");
    $getData = $getData->fetch_assoc();
    $theme = $getData['theme'];
}

$lengthKey = strlen($key);

if ($lengthKey >= 2) {
    // Escape keyword untuk mencegah serangan SQL injection
    $keyword = $db->real_escape_string($key);

    // Query untuk mencari pengguna
    $userQuery = $db->query("SELECT * FROM users WHERE username LIKE '%" . $keyword . "%' OR name LIKE '%" . $keyword . "%' ORDER BY username ASC LIMIT 5");

    // Query untuk mencari hashtag
    $hashtagQuery = $db->query("SELECT * FROM hashtag WHERE hashtag LIKE '%" . $keyword . "%' GROUP BY hashtag ORDER BY hashtag ASC LIMIT 5");

    // Query untuk mencari postingan
    $postQuery = $db->query("SELECT * FROM hashtag WHERE hashtag LIKE '%" . $keyword . "%' GROUP BY post_id ORDER BY hashtag ASC");

    // Num Rows Query
    $numUser = mysqli_num_rows($userQuery);
    $numHashtag = mysqli_num_rows($hashtagQuery);
    $numPost = mysqli_num_rows($postQuery);

    $total = $numUser + $numHashtag + $numPost;
} else {
    exit;
}


?>

<div class="result-query" id="result-query">
    <?php if ($numUser == 0 && $numHashtag == 0 && $numPost == 0) { ?>
        <p class="fs-6 text-center">No results for <span class="fw-bold">'<?= $key ?>'</span></p>
    <?php exit;
    } ?>
    <p class="fs-6 text-center">showing results for <span class="fw-bold">'<?= $key ?>'</span>, total <span class="fw-bold">(<?= $total ?>)</span></p>

    <div class="user mb-4">
        <?php if ($numUser > 0) { ?>
            <p class="fw-bold fs-4 mb-0">Account</p>

            <?php while ($fetchUser = mysqli_fetch_assoc($userQuery)) :
            ?>
                <a href="<?= BASEURL ?>/profile/user/?id=<?= $fetchUser['user_id'] ?>" class="nav-link wrapper-content rounded py-1 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
                    <div class="account rounded d-flex p-1 gap-2">
                        <div class="profile my-auto">
                            <img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $fetchUser['profile_picture'] ?>" alt="<?= $fetchUser['username'] ?>" style="width: 45px; height: 45px; border-radius: 50%;">
                        </div>
                        <div class="username">
                            <div class="uname d-flex mb-0 gap-1">
                                <p class="fw-bold my-auto"><?= $fetchUser['name'] ?></p>
                                <?php
                                if ($fetchUser['verified'] == 1) {
                                ?>
                                    <i class="bi bi-patch-check-fill text-info"></i>
                                <?php } ?>
                            </div>
                            <p class="text-muted mb-0 my-0" style="font-size: 12px;">@<?= $fetchUser['username'] ?></p>
                        </div>
                    </div>
                </a>
                <hr class="m-0 text-muted">
            <?php endwhile; ?>
        <?php } ?>
    </div>

    <div class="hashtag mb-4">

    <p class="fw-bold fs-4 mb-0">Hashtag</p>

        <?php if ($numHashtag > 0) { ?>

            <?php while ($fetchHashtag = mysqli_fetch_assoc($hashtagQuery)) :
                $tag = $fetchHashtag['hashtag'];
                $totalViews = gethashtagViews($tag);
                if ($totalViews > 0) {
            ?>

                    <a href="<?= BASEURL ?>/hashtag/?tag=<?= $fetchHashtag['hashtag'] ?>" class="nav-link wrapper-content gap-2 col-12 d-flex rounded py-1 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
                        <div class="hash-logo my-auto d-flex">
                            <div class="rounded-circle m-auto border d-flex" style="height: 35px; width: 35px;">
                                <i class="bi bi-hash fw-bold m-auto fs-4"></i>
                            </div>
                        </div>
                        <div class="uname d-flex mb-0 justify-content-between" style="width: 100%">
                            <p class="fw-bold my-auto"><?= $fetchHashtag['hashtag'] ?></p>
                            <p class="text-muted my-auto"><?= number_format_short($totalViews) ?> views</p>
                        </div>
                    </a>
                    <hr class="m-0 text-muted">
            <?php }
            endwhile; ?>
        <?php } ?>
    </div>

    <div class="posts mb-4">
    <p class="fw-bold fs-4 mb-0">Posts</p>

        <?php if ($numPost > 0) { ?>

            <?php
            while ($fetchPost = mysqli_fetch_assoc($postQuery)) :
                $postId = $fetchPost['post_id'];
                $getPost = $db->query("SELECT * FROM posts WHERE id = '$postId'");
                if ($getPost->num_rows > 0) {
                    while ($fetchPost = mysqli_fetch_assoc($getPost)) :
                        $postIds = $fetchPost['id'];
                        $getViews = $db->query("SELECT * FROM views WHERE post_id = '$postIds'");
                        $veiws = mysqli_num_rows($getViews);
            ?>

                        <div class="row row-cols-3 row-cols-lg-5 g-1 g-lg-2">
                            <div class="col">
                                <a href="<?= BASEURL ?>/post/?p=<?= $fetchPost['id'] ?>" class="m-0 d-flex" style="height: 170px; overflow: hidden; position: relative;">
                                    <?php if ($fetchPost['type'] == 'video') { ?>
                                        <div class="video-overlay d-flex">
                                            <i class="bi bi-play fs-5 text-white fw-bold"></i>
                                            <p class="my-auto text-white" style="font-size: 14px;"><?= $veiws ?></p>
                                        </div>
                                        <video class="d-block mx-auto" style="height: 170px;" src="<?= BASEURL; ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>">
                                        <?php } elseif ($fetchPost['type'] == 'post') { ?>
                                            <img src="<?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" class="d-block mx-auto" style="height: 170px;">
                                        <?php } ?>
                                </a>
                            </div>

                <?php
                    endwhile;
                }
            endwhile; ?>
                        </div>
                    <?php } ?>
    </div>
</div>