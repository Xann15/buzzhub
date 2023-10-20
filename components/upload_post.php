<?php

isset($_GET['upload']) ? $uploadType = $_GET['upload'] : $uploadType = 'post';


if (isset($_POST['upload-photo'])) {
  if (uploadPhoto($_POST) > 0) {
    $_SESSION['success'] = "Uploaded";
    header('location: ' . BASEURL);
    exit;
  } else {
  }
}

if (isset($_POST['upload-video'])) {
  if (uploadVideo($_POST) > 0) {
    $_SESSION['success'] = "Uploaded";
    header('location: ' . BASEURL);
    exit;
  } else {
  }
}

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
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.js"></script>

  <title>Upload Post | MyApp</title>

</head>

<body>
  <nav id="nav" class="navbar fixed-top shadow-sm">
    <div class="container">
      <a href="<?= BASEURL; ?>" class="nav-title nav-link fw-bold d-flex gap-2">
        <i class="bi bi-arrow-left fs-4 my-auto"></i>
        <p class="my-auto fs-5"><?= TITLE_SITE; ?></p>
      </a>
    </div>
  </nav>

  <?php if (isset($_SESSION['myapp_login'])) {
    require_once 'components/bottom_nav.php';
  } else {
    require_once 'components/bottom_nav_guest.php';
  } ?>

  <div class="container mt-5 py-5">
    <!-- Alert -->
    <?php if (isset($_SESSION['success'])) { ?>
      <div class="alert alert-success alert-dismissible animate__animated animate__fadeInUp animate__delay-1s">
        <?= $_SESSION['success'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php }
    unset($_SESSION['success']); ?>


    <!-- Alert -->
    <?php if (isset($_SESSION['failed'])) { ?>
      <div class="alert alert-danger alert-dismissible animate__animated animate__fadeInUp animate__delay-1s">
        <?= $_SESSION['failed'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php }
    unset($_SESSION['failed']); ?>
  </div>

  <!-- Upload Post Images -->
  <?php if ($uploadType == 'post') { ?>
    <div class="upload-img mb-5 pb-3" id="upload-img">
      <p class="display-6 text-center mb-0">Upload Something</p>
      <p class="text-muted text-center">upload a photo less than 25MB.</p>
      <form action="" method="post" enctype="multipart/form-data">
        <img src="" alt="" id="prev-image" class="img-thumbnail d-block mx-auto">

        <div class="container">
          <input type="file" onchange="readURL(this)" name="uploadedImage" id="uploadedImage" class="form-control mt-2">
          <div class="d-flex gap-2 mt-3 mx-2 mx-lg-5 mt-lg-3 justify-content-between">
            <div class="cta my-auto">
              <a href="<?= BASEURL ?>/?q=upload_post&upload=post" class="btn btn-dark px-3 py-0 rounded-pill">
                <i class="bi bi-image"></i> Photo
              </a>
              <a href="<?= BASEURL ?>/?q=upload_post&upload=video" class="btn border-dark px-3 py-0 rounded-pill">
                <i class="bi bi-play-btn"></i> Video
              </a>
            </div>
            <div class="upload">
              <button type="submit" name="upload-photo" id="upload-photo" class="btn btn-dark px-3 py-1 rounded-pill">
                <i class="bi bi-upload"></i> Upload
              </button>
            </div>
          </div>

          <div class="body-content mt-4">

            <div class="commnet mb-3">
              <div class="form-check px-2">
                <input class="form-check-input" type="checkbox" name="allow-comment" id="allow-comment">
                <label for="allow-comment" class="checkbox text-dark">
                  allow comment
                </label>
              </div>
            </div>

            <div class="caption mb-3">
              <div class="form-group">
                <label for="exampleFormControlTextarea1">Caption</label>
                <textarea name="caption" id="caption" class="form-control" id="exampleFormControlTextarea1" rows="2"></textarea>
              </div>
            </div>

            <div class="hastag mb-3">
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">#</div>
                </div>
                <input type="text" name="hastag" id="hastag" class="form-control" id="hastag" placeholder="hastag">
              </div>
            </div>

          </div>
        </div>
    </div>

    </form>
    </div>
  <?php } elseif ($uploadType == 'video') { ?>
    <div class="upload-img mb-5 pb-3" id="upload-img">
      <p class="display-6 text-center mb-0">Upload Something</p>
      <p class="text-muted text-center">upload a video less than 25MB.</p>
      <form action="" method="post" enctype="multipart/form-data">
        <video src="" id="prev-media" autoplay loop class="img-thumbnail d-block mx-auto"></video>

        <div class="container">
          <input type="file" onchange="readURL(this)" name="uploadedVideo" id="uploadedVideo" class="form-control mt-2">
          <div class="d-flex gap-2 mt-3 mx-2 mx-lg-5 mt-lg-3 justify-content-between">
            <div class="cta my-auto">
              <a href="<?= BASEURL ?>/?q=upload_post&upload=post" class="btn border-dark px-3 py-0 rounded-pill">
                <i class="bi bi-image"></i> Photo
              </a>
              <a href="<?= BASEURL ?>/?q=upload_post&upload=video" class="btn btn-dark px-3 py-0 rounded-pill">
                <i class="bi bi-play-btn"></i> Video
              </a>
            </div>
            <div class="upload">
              <button type="submit" name="upload-video" id="upload-video" class="btn btn-dark px-3 py-1 rounded-pill">
                <i class="bi bi-upload"></i> Upload
              </button>
            </div>
          </div>

          <div class="body-content mt-4">

            <div class="commnet mb-3">
              <div class="form-check px-2">
                <input class="form-check-input" type="checkbox" name="allow-comment" id="allow-comment">
                <label for="allow-comment" class="checkbox text-dark">
                  allow comment
                </label>
              </div>
            </div>

            <div class="caption mb-3">
              <div class="form-group">
                <label for="exampleFormControlTextarea1">Caption</label>
                <textarea name="caption" id="caption" class="form-control" id="exampleFormControlTextarea1" rows="2"></textarea>
              </div>
            </div>

            <div class="hastag mb-3">
              <div class="input-group">
                <div class="input-group-prepend">
                  <div class="input-group-text">#</div>
                </div>
                <input type="text" name="hastag" class="form-control" id="hastag" placeholder="hastag">
              </div>
            </div>

          </div>
        </div>

      </form>
    </div>
  <?php } ?>

  </div>


  <script>
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $('#prev-image')
            .attr('src', e.target.result);
          $('#upload-img')
            .addClass('preview-img');
          $('#prev-media')
            .attr('src', e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
</body>

</html>