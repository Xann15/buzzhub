<?php if (isset($_SESSION['myapp_login'])) { ?>
    <nav id="nav" class="navbar fixed-top shadow-sm p-2 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
        <div class="container">
            <a href="<?= BASEURL; ?>" class="nav-link fw-bold my-auto fs-5"><?= TITLE_SITE; ?></a>
            <a href="<?= BASEURL; ?>/chat" class="nav-link fw-bold my-auto">
                <i class="bi bi-chat fs-3"></i>
            </a>
        </div>
    </nav>
<?php } else { ?>
    <nav id="nav" class="navbar fixed-top shadow-sm navbar-expand p-1">
        <div class="container">
            <a class="navbar-brand d-flex gap-2" href="<?= BASEURL; ?>">
                <p class="my-auto fw-bold"><?= TITLE_SITE; ?></p>
            </a>
            <a class="nav-link mx-2" aria-current="page" href="<?= BASEURL; ?>/login"><i class="fs-4 bi bi-box-arrow-in-right"></i></a>
        </div>
    </nav>
<?php } ?>

<div class="contaier mt-5 py-1 px-1 mb-0">
    <!-- Alert -->
    <?php if (isset($_SESSION['success'])) { ?>
        <div class="alert alert-success alert-dismissible animate__animated animate__fadeInUp">
            <?= $_SESSION['success'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php }
    unset($_SESSION['success']); ?>


    <!-- Alert -->
    <?php if (isset($_SESSION['failed'])) { ?>
        <div class="alert alert-danger alert-dismissible animate__animated animate__fadeInUp">
            <?= $_SESSION['failed'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php }
    unset($_SESSION['failed']);
    ?>
</div>