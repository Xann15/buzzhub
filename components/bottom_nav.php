<?php


if (isset($_COOKIE['notification'])) {
    $time = $_COOKIE['notification'];
    $numberofNewNotification = getNotification($time);
}
?>
<!-- Bottom Nav -->
<nav id="nav" class="navbar fixed-bottom shadow-sm p-1 <?= $theme == 'dark' ? 'bg-black text-white' : 'bg-white' ?>">
    <div class="container d-flex justify-content-between p-1 px-3 col-lg-6 mx-auto">
        <a href="<?= BASEURL; ?>" class="nav-link fw-bold">
            <i class="bi bi-house fs-4"></i>
        </a>
        <a href="<?= BASEURL; ?>/explore" class="nav-link fw-bold">
            <i class="bi bi-compass fs-4"></i>
        </a>
        <a href="<?= BASEURL; ?>/reels" class="nav-link fw-bold">
            <i class="bi bi-camera-reels fs-4"></i>
        </a>
        <div class="dropup">
            <a class="btn p-0" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: none">
                <i class="bi bi-cloud-plus fs-3 <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
            </a>

            <ul class="dropdown-menu dropdown-menu-start <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
                <li>
                    <a class="dropdown-item d-flex gap-2" href="<?= BASEURL ?>/upload/story">
                        <i class="bi bi-browser-edge fs-6 my-auto dropwdown-text <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                        <p class="dropwdown-text my-auto <?= $theme == 'dark' ? 'text-white' : '' ?>">Story</p>
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex gap-2" href="<?= BASEURL ?>/upload/post">
                        <i class="bi bi-grid-1x2-fill fs-6 my-auto dropwdown-text <?= $theme == 'dark' ? 'text-white' : '' ?>"></i>
                        <p class="dropwdown-text my-auto <?= $theme == 'dark' ? 'text-white' : '' ?>">Post</p>
                    </a>
                </li>
            </ul>
        </div>
        <a href="<?= BASEURL; ?>/notification" class="nav-link fw-bold position-relative">
            <i class="bi bi-bell fs-4"></i>
            <?php if (isset($_COOKIE['notification'])) { ?>
                <span id="notification" class="position-absolute text-dark top-0 start-100 translate-middle badge rounded-pill badge-danger" style="background-color: #F9E1E5; font-size: 10px;">
                    <?= $numberofNewNotification ?>
                    <span class="visually-hidden">unread messages</span>
                </span>
            <?php } ?>
        </a>
        <a class="nav-link" href="<?= BASEURL; ?>/profile">
            <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $getData['profile_picture'] ?>" alt="<?= $getData['username'] ?>" class="rounded-circle" style="width: 35px; height: 35px;">
        </a>
    </div>
</nav>
<input type="hidden" name="baseurl" id="baseurl" value="<?= BASEURL ?>">

<script>
    const url = $('#baseurl').val();
    setInterval(() => {
        $.ajax({
            url: url + "/components/realTimeBottomNav.php",
            method: "POST",
            data: {},
            dataType: "text",
            success: function(data) {
                $('#notification').html(data);
            }
        });
    }, 1000);
    $("#fullscreen").click(function() {
        if ($(this).hasClass("fullscreen")) {
            openFullscreen();
            $(this).toggleClass("fullscreen exitFullscreen");
        } else {
            closeFullscreen();
            $(this).toggleClass("exitFullscreen fullscreen");
        }
    });


    /* Get the documentElement (<html>) to display the page in fullscreen */
    var elem = document.documentElement;

    /* View in fullscreen */
    function openFullscreen() {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) {
            /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) {
            /* IE11 */
            elem.msRequestFullscreen();
        }
    }

    /* Close fullscreen */
    function closeFullscreen() {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            /* Safari */
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) {
            /* IE11 */
            document.msExitFullscreen();
        }
    }
</script>