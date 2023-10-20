<?php
session_start();
require_once '../../init.php';

if (!isset($_SESSION['myapp_login'])) {
	$_SESSION['failed'] = "Oops, this page available for loged user only";
	header('Location: ' . BASEURL . '/login');
	exit;
}

$uuid = $_SESSION['myapp_login'];

$getUser = $db->query("SELECT * FROM users WHERE user_id = '$uuid'");
$getUser = mysqli_fetch_assoc($getUser);
$theme = $getUser['theme'];

if (isset($_POST['change'])) {

	$name = htmlspecialchars($_POST['name']);
	$username = htmlspecialchars($_POST['username']);
	$bio = htmlspecialchars($_POST['bio']);
	$oldPassword = mysqli_real_escape_string($db, $_POST['oldPassword']);
	$newPassword = mysqli_real_escape_string($db, $_POST['newPassword']);

	if (isValidChangeProfile($_POST) > 0) {
	} else {
		$_SESSION['failed'] = "Please at lease change one";
		header("location: " . BASEURL . "/profile/edit");
		exit;
	}

	if ($oldPassword == '' && $newPassword == '') {
		if ($name != $getUser['name']) {
			if (changeName($_POST) > 0) {
				$_SESSION['success'] = "Changed Successfully";
				header("location: " . BASEURL . "/profile/edit");
				exit;
			}
		}

		if ($username != $getUser['username']) {
			if (changeUsername($_POST) > 0) {
				$_SESSION['success'] = "Changed Successfully";
				header("location: " . BASEURL . "/profile/edit");
				exit;
			} else {
				$_SESSION['failed'] = "Username already taken, please try another one.";
				header("location: " . BASEURL . "/profile/edit");
				exit;
			}
		}

		if ($bio != $getUser['bio']) {
			if (changeBio($_POST) > 0) {
				$_SESSION['success'] = "Changed Successfully";
				header("location: " . BASEURL . "/profile/edit");
				exit;
			}
		}
	}

	if ($oldPassword != '' && $newPassword != '') {
		if (changePassword($_POST) > 0) {
			$_SESSION['success'] = "Changed Successfully";
			header("location: " . BASEURL . "/profile/edit");
			exit;
		} else {
			$_SESSION['failed'] = "Old password is wrong..";
			header("location: " . BASEURL . "/profile/edit");
			exit;
		}
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
	<!-- <script src="<?= BASEURL; ?>/assets/js/bootstrap.bundle.min.js"></script> -->

	<!-- Favicon -->
	<link href="<?= BASEURL; ?>/assets/img/favicon.png" rel="icon">

	<!-- Fonts -->
	<link href='https://fonts.googleapis.com/css?family=Nunito' rel='stylesheet'>

	<!-- Jquery -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
	<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet" />
	<!-- <script src="https://unpkg.com/dropzone"></script> -->
	<script src="https://unpkg.com/cropperjs"></script>

	<title>Profile | <?= TITLE_SITE ?></title>

	<style>
		.image_area {
			position: relative;
		}

		img {
			display: block;
		}

		.preview {
			overflow: hidden;
			width: 160px;
			height: 160px;
			margin: 10px;
			border: 1px solid red;
		}

		.modal-lg {
			max-width: 1000px !important;
		}

		.overlay {
			position: absolute;
			bottom: 10px;
			left: 0;
			right: 0;
			background-color: rgba(255, 255, 255, 0.5);
			overflow: hidden;
			height: 0;
			transition: .5s ease;
			width: 100%;
		}

		.image_area:hover .overlay {
			height: 50%;
			cursor: pointer;
		}

		.text {
			color: #333;
			font-size: 18px;
			position: absolute;
			top: 50%;
			left: 50%;
			-webkit-transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
			text-align: center;
		}
	</style>

</head>

<body class="<?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
	<nav id="nav" class="navbar fixed-top shadow-sm navbar-expand p-0 <?= $theme == 'dark' ? 'bg-black text-white' : '' ?>">
		<div class="container justify-content-start">
			<a class="nav-text navbar-left d-flex" href="<?= BASEURL; ?>/profile">
				<i class="bi bi-arrow-left fs-1 <?= $theme == 'dark' ? 'text-white' : 'text-black' ?>"></i>
			</a>
		</div>
	</nav>

	<div class="container p-0 py-5 my-5">
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

		<div class="col-12 col-md-4 col-lg-4">
			<div class="image_area d-flex">
				<form method="post" class="mx-auto">
					<label for="upload_image">
						<img src="<?= BASEURL ?>/assets/user_profile_picture/<?= $getUser['profile_picture'] ?>" id="uploaded_image" class="img-responsive img-circle" width="170px" />
						<div class="overlay">
							<div class="text">Tap to Change</div>
						</div>
						<input type="file" name="image" class="image" id="upload_image" style="display:none" />
					</label>
				</form>
			</div>
		</div>
		<div class="col-12 col-md-8 col-lg-8">
			<form action="" method="post">
				<div class="d-flex gap-2 align-items-center mb-3">
					<span class="bi bi-person fs-3"></span>
					<input class="form-control <?= $theme == 'dark' ? 'bg-dark text-white' : '' ?>" type="text" name="name" id="name" placeholder="Name" value="<?= $getUser['name'] ?>" style="border:none">
				</div>
				<div class="d-flex gap-2 align-items-center mb-3">
					<span class="fs-3">@</span>
					<input class="form-control <?= $theme == 'dark' ? 'bg-dark text-white' : '' ?>" type="text" name="username" id="username" placeholder="Username" value="<?= $getUser['username'] ?>" style="border:none">
				</div>
				<div class="form-outline mb-3">
					<textarea class="form-control <?= $theme == 'dark' ? 'bg-dark text-white' : '' ?>" name="bio" id="bio" rows="4" placeholder="Bio" style="border: none"><?= $getUser['bio'] ?></textarea>
				</div>
				<div class="d-flex gap-2 align-items-center mb-3">
					<span class="bi bi-lock fs-3"></span>
					<input class="form-control <?= $theme == 'dark' ? 'bg-dark text-white' : '' ?>" type="password" name="oldPassword" id="oldPassword" placeholder="Old Password" style="border:none">
				</div>
				<div class="d-flex gap-2 align-items-center mb-3">
					<span class="bi bi-lock-fill fs-3"></span>
					<input class="form-control <?= $theme == 'dark' ? 'bg-dark text-white' : '' ?>" type="text" name="newPassword" id="newPassword" placeholder="New Password" style="border:none">
				</div>

				<button type="submit" name="change" id="change" class="btn rounded py-0 col-12 mt-3 text-white shadow-sm" style="background-color: #FF3B5C;">
					<span class="bi bi-check fs-1"></span>
				</button>
			</form>

		</div>
		<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Crop Image Before Upload</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="img-container">
							<div class="row">
								<div class="col-md-8">
									<img src="" id="sample_image" style="width: 100%  " />
								</div>
								<div class="col-md-4">
									<div class="preview"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" id="crop" class="btn text-white shadow-sm" style="background-color: #FF3B5C;"><i class="bi bi-crop"></i> Change</button>
						<button type="button" class="btn btn-muted" data-dismiss="modal"><i class="bi bi-x-lg"></i> Cancel</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>

<script>
	$(document).ready(function() {

		var $modal = $('#modal');

		var image = document.getElementById('sample_image');

		var cropper;

		$('#upload_image').change(function(event) {
			var files = event.target.files;

			var done = function(url) {
				image.src = url;
				$modal.modal('show');
			};

			if (files && files.length > 0) {
				reader = new FileReader();
				reader.onload = function(event) {
					done(reader.result);
				};
				reader.readAsDataURL(files[0]);
			}
		});

		$modal.on('shown.bs.modal', function() {
			cropper = new Cropper(image, {
				aspectRatio: 1,
				viewMode: 3,
				preview: '.preview'
			});
		}).on('hidden.bs.modal', function() {
			cropper.destroy();
			cropper = null;
		});

		$('#crop').click(function() {
			canvas = cropper.getCroppedCanvas({
				width: 400,
				height: 400
			});

			canvas.toBlob(function(blob) {
				url = URL.createObjectURL(blob);
				var reader = new FileReader();
				reader.readAsDataURL(blob);
				reader.onloadend = function() {
					var base64data = reader.result;
					$.ajax({
						url: 'upload.php',
						method: 'POST',
						data: {
							image: base64data
						},
						success: function(data) {
							$modal.modal('hide');
							$('#uploaded_image').attr('src', data);
						}
					});
				};
			});
		});

	});
</script>