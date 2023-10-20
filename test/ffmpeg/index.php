<?php
if (isset($_POST["submit"])) {
    $video = $_FILES["video"]["name"];
    $tmp_name = $_FILES["video"]["tmp_name"];

    move_uploaded_file($tmp_name, "video.mp4");
    $video = "video.mp4";

    $command = "/usr/local/bin/ffmpeg -i " . $video . " -vf fps=1/60 thumbnail-%03d.png";
    system($command);

    echo "Thumbnail has been generated";
}
?>

<link rel="stylesheet" href="bootstrap-darkly.min.css">

<div class="container" style="margin-top: 200px;">
    <div class="offset-md-4 col-md-4">
        <form method="POST" enctype="multipart/form-data" action="index.php">
            <div class="form-group">
                <label>Select video</label>
                <input type="file" name="video" accept="video/*" class="form-control" required>
            </div>

            <input type="submit" class="btn btn-primary" name="submit" value="Generate">
        </form>
    </div>
</div>