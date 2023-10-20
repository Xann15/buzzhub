<?php
include '../init.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <style>
        video {
            width: 100%;
        }

        .white {
            height: 1000px;
        }
    </style>
</head>

<body>

    <?php $getPost = $db->query("SELECT p.id, p.user_id, p.type, p.post, p.tweet, p.caption, p.createdAt, p.show_comment, u.user_id, u.profile_picture, u.verified, u.username, u.name FROM posts as p JOIN users AS u ON p.user_id = u.user_id AND p.type = 'video' ORDER BY p.id DESC LIMIT 10"); ?>

    <?php while ($fetchPost = mysqli_fetch_assoc($getPost)) : ?>
        <video>
            <source src="<?= BASEURL ?>/assets/uploads/posts/<?= $fetchPost['post'] ?>" type="video/mp4" />
        </video>
    <?php endwhile; ?>

    <script>
        $("video").each(function() {
            $(this).prop({
                controls: true,
                controlslist: "nodownload"
            });
            const observer = new window.IntersectionObserver(
                ([entry]) => {
                    if (entry.isIntersecting) {
                        if (this.paused) {
                            // $(this).prop("muted", true);
                            this.play();
                        }
                    } else {
                        this.pause();
                    }
                }, {
                    threshold: 0.5,
                }
            );
            observer.observe(this);
        });
    </script>
</body>

</html>