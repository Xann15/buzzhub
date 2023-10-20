<?php

require_once "../init.php";

$key = $_POST['key'];

$lengthKey = strlen($key);

if ($lengthKey >= 2) {
    // Escape keyword untuk mencegah serangan SQL injection
    $keyword = $db->real_escape_string($key);
    $suggesQuery = $db->query("SELECT * FROM hastag WHERE hastag LIKE '%" . $keyword . "%' ORDER BY hastag ASC");
} else {
    exit;
}

?>

<div class="result-query" id="result-query">

    <div class="hastag mb-4">
        <!-- <?php if ($numHashtag > 0) { ?> -->
        <p class="fw-bold fs-4 mb-0">Hashtag</p>

        <?php while ($fetch = mysqli_fetch_assoc($suggesQuery)) : ?>
            <a href="<?= BASEURL ?>/search/?q=<?= $fetch['hastag'] ?>" class="nav-link wrapper-content d-flex rounded py-1">
                <div class="hash-logo my-auto">
                    <i class="bi bi-search fw-bold fs-4"></i>
                </div>
                <div class="uname d-flex mb-0 gap-1">
                    <p class="fw-bold my-auto"><?= $fetch['hastag'] ?></p>
                </div>
            </a>
            <hr class="m-0 text-muted">
        <?php endwhile; ?>
        <!-- <?php } ?> -->
    </div>

</div>