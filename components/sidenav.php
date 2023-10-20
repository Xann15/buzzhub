<?php

if (isset($_COOKIE['notification'])) {
    $time = $_COOKIE['notification'];
    $numberofNewNotification = totalNotification($time);
}

?>
<div class="sidenav d-none d-md-flex d-lg-flex col-2 shadow position-fixed h-100">
    <div class="sidenav-container container">
        <div class="header mt-4 pt-3">
            <p class="display-6 fs-4 fw-bold text-center" style="font-family: nunito;"><?= TITLE_SITE ?></p>
        </div>

        <div class="sub-menu mt-4">
            <div class="rounded mb-3">
                <a class="nav-link fw-bold d-flex gap-3" href="<?= BASEURL; ?>/profile">
                    <img src="<?= BASEURL; ?>/assets/user_profile_picture/<?= $getData['profile_picture'] ?>" alt="<?= $getData['username'] ?>" class="rounded-circle" style="width: 30px; height: 30px;">
                    <p class="my-auto text-sidenav">Profil</p>
                </a>
            </div>
            <div class="menu-sidenav py-0 px-2 rounded mt-3 mb-3">
                <a href="<?= BASEURL; ?>" class="nav-link fw-bold d-flex gap-3">
                    <i class="bi bi-house fs-4 text-sidenav"></i>
                    <p class="my-auto text-sidenav">Home</p>
                </a>
            </div>
            <div class="menu-sidenav py-0 px-2 rounded mt-3 mb-3">
                <a href="<?= BASEURL; ?>/explore" class="nav-link fw-bold d-flex gap-3">
                    <i class="bi bi-compass fs-4 text-sidenav"></i>
                    <p class="my-auto text-sidenav">Explore</p>
                </a>
            </div>
            <div class="menu-sidenav py-0 px-2 rounded mt-3 mb-3">
                <a href="<?= BASEURL; ?>/search" class="nav-link fw-bold d-flex gap-3">
                    <i class="bi bi-search fs-4 text-sidenav"></i>
                    <p class="my-auto text-sidenav">Search</p>
                </a>
            </div>
            <div class="menu-sidenav py-0 px-2 rounded mt-3 mb-3">
                <a href="<?= BASEURL; ?>/chat" class="nav-link fw-bold d-flex gap-3">
                    <i class="bi bi-chat fs-4 text-sidenav"></i>
                    <p class="my-auto text-sidenav">Direct Message</p>
                </a>
            </div>
            <div class="menu-sidenav py-0 px-2 rounded mb-3">
                <div class="dropdown">
                    <a class="btn p-0 d-flex gap-3 fw-bold <?= $theme == 'dark' ? 'text-white' : '' ?>" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border: none">
                        <i class="bi bi-cloud-plus fs-3 text-sidenav"></i>
                        <p class="my-auto text-sidenav">Upload</p>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-start <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
                        <li>
                            <a class="dropdown-item  d-flex gap-3" href="<?= BASEURL ?>/upload/story">
                                <i class="bi bi-browser-edge fs-6 my-auto dropwdown-text text-sidenav"></i>
                                <p class="dropwdown-text my-auto">Story</p>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex gap-3" href="<?= BASEURL ?>/upload/post">
                                <i class="bi bi-grid-1x2-fill fs-6 my-auto dropwdown-text text-sidenav"></i>
                                <p class="dropwdown-text my-auto">Post</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="menu-sidenav py-0 px-2 rounded mb-3">
                <a href="<?= BASEURL; ?>/notification" class="nav-link fw-bold position-relative d-flex gap-3">
                    <i class="bi bi-bell fs-4 text-sidenav"></i>
                    <p class="my-auto text-sidenav">Notifications</p>
                    <?php if (isset($_COOKIE['notification'])) { ?>
                        <span id="notification" class="position-absolute text-dark top-0 start-100 translate-middle badge rounded-pill badge-danger" style="background-color: #F9E1E5; font-size: 10px;">
                            <?= $numberofNewNotification ?>
                            <span class="visually-hidden">unread messages</span>
                        </span>
                    <?php } ?>
                </a>
            </div>
            <div class="menu-sidenav py-0 px-2 rounded mb-3">
                <a href="<?= BASEURL; ?>/settings" class="nav-link fw-bold d-flex gap-3">
                    <i class="bi bi-gear fs-4 text-sidenav"></i>
                    <p class="my-auto text-sidenav">Settings</p>
                </a>
            </div>

        </div>
    </div>
</div>

<input type="hidden" name="baseurl" id="baseurl" value="<?= BASEURL ?>">

<script>
    const urls = $('#baseurl').val();
    setInterval(() => {
        $.ajax({
            url: urls + "/components/realTimeBottomNav.php",
            method: "POST",
            data: {},
            dataType: "text",
            success: function(data) {
                $('#notification').html(data);
            }
        });
    }, 1000);
</script>