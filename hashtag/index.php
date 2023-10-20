<?php
session_start();

require_once '../init.php';

if (isset($_SESSION['myapp_login'])) {
    $uuid = $_SESSION['myapp_login'];
    $get = $db->query("SELECT * FROM users WHERE user_id = '$uuid'");
    $getData = mysqli_fetch_assoc($get);
    $theme = $getData['theme'];
}

isset($_GET['tag']) ? $tag = $_GET['tag'] : $tag = 'digity';


$postQuery = $db->query("SELECT * FROM hastag WHERE hastag = '$tag' GROUP BY post_id ORDER BY hastag ASC");



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Animate -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Loading Css -->
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/loading.min.css">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="<?= BASEURL; ?>/assets/js/bootstrap.bundle.min.js"></script>

    <!-- Favicon -->
    <link href="<?= BASEURL; ?>/assets/img/favicon.png" rel="icon">

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>

    <!-- Jquery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <title>Hashtag | #<?= $tag ?> | <?= TITLE_SITE ?></title>

</head>


<input type="text" name="link" id="link" value="<?= BASEURL ?>/hashtag/?tag=<?= $tag ?>" style="opacity: 0">

<body class="<?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
    <nav id="nav" class="navbar fixed-top navbar-expand shadow-sm p-1 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
        <div class="container d-flex justify-content-between">
            <div class="back">
                <a href="javascript:history.back()" class="nav-link my-auto">
                    <i class="bi bi-arrow-left fs-2"></i>
                </a>
            </div>
            <div class="tag m-auto d-flex">
                <p class="fw-bold m-auto">#<?= $tag ?></p>
            </div>
            <div class="share">
                <div class="dropdown">
                    <a class="btn p-0" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: none">
                        <i class="bi bi-share fs-5 <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end <?= $theme == 'dark' ? 'bg-black' : '' ?>">
                        <li>
                            <button type="button" class="dropdown-item d-flex gap-2 btn" data-bs-toggle="modal" data-bs-target="#qrModal" style="border: none">
                                <i class="bi bi-qr-code fs-6 my-auto dropwdown-text <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                                <p class="dropwdown-text my-auto <?= $theme == 'dark' ? 'text-white' : '' ?>">QR Code</p>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item d-flex gap-1" id="copy">
                                <i class=" bi bi-link-45deg fs-5 my-auto dropwdown-text <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                                <p class="my-auto <?= $theme == 'dark' ? 'text-white' : '' ?>" style="font-size: 13px;">Copy Link</p>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>


    <div class="container mt-5 mb-5 py-2 p-1">
        <div class="header mt-4 d-flex gap-2">
            <div class="hashtag-logo mx-2">
                <img src="hashtag.png" alt="hashtag" class="img-thumbnail" style="width: 140px;">
            </div>
            <div class="hashtag-detail pt-2 position-relative">
                <p class="fw-bold mb-0">#<?= $tag ?></p>
                <p class="text-muted" style="font-size: 13px"><?= number_format_short($totalViews) ?> views</p>

                <div class="bookmark d-flex rounded border p-2 py-1 gap-2 position-absolute bottom-0" style="width: 150px;">
                    <span class="bi bi-bookmark"></span>
                    <p class="m-auto" style="font-size: 14px;">Add to favorites</p>
                </div>
            </div>
        </div>
        <div class="posts mt-4">
            <div class="row row-cols-3 row-cols-lg-5 g-1 g-lg-2">
                <?php
                while ($fetchPost = mysqli_fetch_assoc($postQuery)) :
                    $postId = $fetchPost['post_id'];
                    $getPost = $db->query("SELECT * FROM posts WHERE id = '$postId'");
                    while ($fetchPost = mysqli_fetch_assoc($getPost)) :
                        $postIds = $fetchPost['id'];
                        $getViews = $db->query("SELECT * FROM views WHERE post_id = '$postIds'");
                        $views = mysqli_num_rows($getViews);
                ?>
                        <div class="col">
                            <a href="<?= BASEURL ?>/post/?p=<?= $fetchPost['id'] ?>" class="m-0 d-flex" style="height: 170px; overflow: hidden; position: relative;">
                                <?php if ($fetchPost['type'] == 'video') { ?>
                                    <div class="video-overlay d-flex">
                                        <i class="bi bi-play fs-5 text-white fw-bold"></i>
                                        <p class="my-auto text-white" style="font-size: 14px;"><?= $views ?></p>
                                    </div>
                                    <video class="d-block mx-auto" style="height: 170px;" src="<?= BASEURL; ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>">
                                    <?php } elseif ($fetchPost['type'] == 'post') { ?>
                                        <img src="<?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" class="d-block mx-auto" style="height: 170px;">
                                    <?php } ?>
                            </a>
                        </div>

                <?php
                    endwhile;
                endwhile; ?>
            </div>
            <div class="end mt-5">
                <p class="text-center text-muted">---------- no more videos ----------</p>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="exampleModalLabel">#<?= $tag ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <img class="d-block mx-auto" src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?= BASEURL ?>/hashtag/?tag=<?= $tag ?>&choe=UTF-8" title="Link to #<?= $tag ?>'" />
                        <p class="display-6 fs-6 text-center"><?= TITLE_SITE ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("copy").addEventListener("click", function() {
            copyToClipboard(document.getElementById("link"));
        });

        function copyToClipboard(elem) {
            // create hidden text element, if it doesn't already exist
            var targetId = "_hiddenCopyText_";
            var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
            var origSelectionStart, origSelectionEnd;
            if (isInput) {
                // can just use the original source element for the selection and copy
                target = elem;
                origSelectionStart = elem.selectionStart;
                origSelectionEnd = elem.selectionEnd;
            } else {
                // must use a temporary form element for the selection and copy
                target = document.getElementById(targetId);
                if (!target) {
                    var target = document.createElement("textarea");
                    target.style.position = "absolute";
                    target.style.left = "-9999px";
                    target.style.top = "0";
                    target.id = targetId;
                    document.body.appendChild(target);
                }
                target.textContent = elem.textContent;
            }
            // select the content
            var currentFocus = document.activeElement;
            target.focus();
            target.setSelectionRange(0, target.value.length);

            // copy the selection
            var succeed;
            try {
                succeed = document.execCommand("copy");
                alert('copied')
            } catch (e) {
                succeed = false;
            }
            // restore original focus
            if (currentFocus && typeof currentFocus.focus === "function") {
                currentFocus.focus();
            }

            if (isInput) {
                // restore prior selection
                elem.setSelectionRange(origSelectionStart, origSelectionEnd);
            } else {
                // clear temporary content
                target.textContent = "";
            }
            return succeed;
        }
    </script>
</body>

</html>