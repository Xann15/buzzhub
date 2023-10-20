<?php

function likePost()
{
    global $db;
    $uuid = $_SESSION['myapp_login'];
    $postId = $_POST['postId'];
    $datetime = date('Y:m:d H:i:s');

    $getPostedBy = $db->query("SELECT user_id FROM posts WHERE id = '$postId'");
    $postedBy = $getPostedBy->fetch_assoc()['user_id'];

    $isLikedByUser = $db->query("SELECT * FROM likes WHERE user_id = '$uuid' AND post_id = '$postId'");
    $isLiked = $isLikedByUser->num_rows;

    if ($isLiked == 1) {
        $db->query("DELETE FROM likes WHERE user_id = '$uuid' AND post_id = '$postId'");
        $db->query("DELETE FROM notification WHERE person_id = '$uuid' AND post_id = '$postId' AND type = 'likePost'");
        return 0;
    }

    $db->query("INSERT INTO likes(user_id, post_id, posted_id, createdAt) VALUES('$uuid','$postId','$postedBy','$datetime')");

    if ($uuid != $postedBy) {
        $db->query("INSERT INTO notification(user_id, person_id, post_id, type, time) VALUES('$postedBy','$uuid','$postId', 'likePost', '$datetime')");
    }

    return 1;
}

function sendTweet($tweet)
{
    global $db;
    $uuid = $_SESSION['myapp_login'];
    $setComment = 'true';
    $type = 'tweet';
    $date = date('Y:m:d H:i:s');

    if ($tweet != '') {
        $db->query("INSERT INTO posts(user_id, type, tweet, show_comment, createdAt) VALUES('$uuid','$type','$tweet','$setComment','$date')");
        return 1;
    } else {
        return 0;
    }
}

function time_elapsed_string($datetime, $full = false)
{
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hr',
        'i' => 'min',
        's' => 's',
    );

    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . '' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) {
        $string = array_slice($string, 0, 1);
    }

    return $string ? implode(', ', $string) . '' : 'just now';
}

function maxLength($x, $length)
{
    if (strlen($x) <= $length) {
        echo $x;
    } else {
        $y = substr($x, 0, $length) . '...';
        echo $y;
    }
}

function joinedAt($datetime)
{
    $x = DateTime::createFromFormat('m/Y', $datetime);
    return $x->format('F Y');
}

function number_format_short($n, $precision = 1)
{
    if ($n <= 999) {
        $n_format = number_format($n);
    } else if ($n <= 999999) {
        $n_format = number_format($n / 1000, $precision) . 'K';
    } else if ($n <= 999999999) {
        $n_format = number_format($n / 1000000, $precision) . 'M';
    } else {
        $n_format = number_format($n / 1000000000, $precision) . 'B';
    }

    return $n_format;
}

function uploadFile($file, $dir, $extention)
{
    $validExtensions = ['jpg', 'jpeg', 'png', 'mp4', 'avi', 'mkv', '3gp', 'mpg/mpeg', 'flv', 'mov', 'm4v'];


    if ($file['error'] == UPLOAD_ERR_NO_FILE) {
        $_SESSION['failed'] = "Content cannot be empty";
        return false;
    }

    if (!in_array($extention, $validExtensions)) {
        $_SESSION['failed'] = "Unsupported file type";
        return false;
    }

    $isMoved = move_uploaded_file($file['tmp_name'], $dir);

    if ($isMoved) {
        return $dir;
    } else {
        $_SESSION['failed'] = "Failed to upload file";
        return false;
    }
}

function uploadPost()
{
    global $db;

    $media = $_FILES['media'];
    $caption = htmlspecialchars($_POST['caption']);
    $createdAt = date("Y:m:d H:i:s");
    $isAllow = isset($_POST['allow-comment']) ? 'true' : 'false';

    // Generate a random filename using UUID and the original file extension
    $fileExtension = strtolower(pathinfo($media['name'], PATHINFO_EXTENSION));
    $randomFileName = uniqid('', true) . '.' . $fileExtension;
    $uploadPath = '../../assets/uploads/posts/' . $randomFileName;


    if (!uploadFile($media, $uploadPath, $fileExtension)) {
        return false;
    }

    $type = in_array($fileExtension, ['mp4', 'avi', 'mkv', '3gp', 'mpg/mpeg', 'flv', 'mov', 'm4v']) ? 'video' : 'post';

    $uuid = $_SESSION['myapp_login'];

    $db->query("INSERT INTO posts(user_id, type, post, caption, createdAt, show_comment) VALUES('$uuid','$type','$randomFileName','$caption','$createdAt', '$isAllow')");

    // Mencari kata yang mengandung '#' di dalam caption
    preg_match_all('/#(\w+)/', $caption, $matches);
    $getIdPost = $db->query("SELECT * FROM posts WHERE user_id = '$uuid' AND createdAt = '$createdAt'");

    $fetchIdPost = $getIdPost->fetch_assoc();
    $post_id = $fetchIdPost['id'];

    // Mengambil kata setelah '#' dan menyimpannya di tabel 'hashtag'
    if (!empty($matches[1])) {
        foreach ($matches[1] as $tag) {
            // Simpan $tag ke dalam tabel 'hashtag'
            // Misalnya, menggunakan objek $db untuk mengakses database:
            $escapedTag = $db->real_escape_string($tag);
            $db->query("INSERT INTO hashtag(post_id, hashtag) VALUES ('$post_id', '$escapedTag')");
        }
    }

    return true;
}

function uploadStory()
{
    global $db;

    $media = $_FILES['media'];
    $createdAt = date("Y:m:d H:i:s");

    // Generate a random filename using UUID and the original file extension
    $fileExtension = strtolower(pathinfo($media['name'], PATHINFO_EXTENSION));
    $randomFileName = uniqid('', true) . '.' . $fileExtension;
    $uploadPath = '../../assets/uploads/story/' . $randomFileName;

    if (!uploadFile($media, $uploadPath, $fileExtension)) {
        return false;
    }

    $type = in_array($fileExtension, ['mp4', 'avi', 'mkv', '3gp', 'mpg/mpeg', 'flv', 'mov', 'm4v']) ? 'video' : 'post';

    $uuid = $_SESSION['myapp_login'];

    $db->query("INSERT INTO story(user_id, type, media, time) VALUES('$uuid','$type','$randomFileName','$createdAt')");

    return true;
}

function follow()
{
    global $db;

    $following = $_POST['uid'];
    $user = $_SESSION['myapp_login'];
    $datetime = date('Y:m:d H:i:s');

    $isFollow = $db->query("SELECT * FROM following WHERE user_id = '$user' AND following = '$following'");

    if (mysqli_num_rows($isFollow) > 0) {
        $db->query("DELETE FROM following WHERE user_id = '$user' AND following = '$following'");
        $db->query("DELETE FROM followers WHERE user_id = '$following' AND followers = '$user'");
        $db->query("DELETE FROM notification WHERE user_id = '$following' AND person_id = '$user' AND type = 'follow'");
        return 0;
    }

    $db->query("INSERT INTO notification(user_id, person_id, type, time) VALUES('$following','$user', 'follow', '$datetime')");
    $db->query("INSERT INTO following(user_id,following,createdAt) VALUES('$user','$following','$datetime')");
    $db->query("INSERT INTO followers(user_id,followers,createdAt) VALUES('$following','$user','$datetime')");
    return 1;
}

function isValidChangeProfile()
{
    global $db;

    $name = htmlspecialchars($_POST['name']);
    $username = htmlspecialchars($_POST['username']);
    $bio = htmlspecialchars($_POST['bio']);
    $oldPassword = mysqli_real_escape_string($db, $_POST['oldPassword']);
    $newPassword = mysqli_real_escape_string($db, $_POST['newPassword']);

    $uuid = $_SESSION['myapp_login'];

    $getUser = $db->query("SELECT * FROM users WHERE user_id = '$uuid'");
    $getUser = mysqli_fetch_assoc($getUser);

    return ($name != $getUser['name'] || $username != $getUser['username'] || $bio != $getUser['bio'] || $oldPassword != '' || $newPassword != '') ? 1 : 0;
}

function changeName()
{
    global $db;
    $name = htmlspecialchars($_POST['name']);
    $uuid = $_SESSION['myapp_login'];
    $db->query("UPDATE users SET name = '$name' WHERE user_id = '$uuid'");
    return 1;
}

function changeUsername()
{
    global $db;
    $username = $_POST['username'];
    $uuid = $_SESSION['myapp_login'];

    if ($username == '') {
        return 0;
    }

    $isTaken = $db->query("SELECT username FROM users WHERE username = '$username'");
    if (mysqli_num_rows($isTaken) > 0) {
        return 0;
    } else {
        $db->query("UPDATE USERS SET verified = 0 WHERE user_id = '$uuid'");
        $db->query("UPDATE users SET username = '$username' WHERE user_id = '$uuid'");
        return 1;
    }
}

function changeBio()
{
    global $db;
    $bio = htmlspecialchars($_POST['bio']);
    $uuid = $_SESSION['myapp_login'];

    $db->query("UPDATE users SET bio = '$bio' WHERE user_id = '$uuid'");
    return 1;
}

function changePassword()
{
    global $db;
    $oldPass = $_POST['oldPassword'];
    $newPass = $_POST['newPassword'];
    $uuid = $_SESSION['myapp_login'];

    $getOldPassword = $db->query("SELECT password FROM users WHERE user_id = '$uuid'");
    $isOldPass = mysqli_fetch_assoc($getOldPassword);

    if (password_verify($oldPass, $isOldPass['password'])) {
        $newPass = password_hash($newPass, PASSWORD_DEFAULT);
        $db->query("UPDATE users SET password = '$newPass' WHERE user_id = '$uuid'");
        return 1;
    } else {
        return 0;
    }
}

function addViewPost()
{
    global $db;
    $user = $_SESSION['myapp_login'];
    $post_id = $_GET['p'];

    $isView = $db->query("SELECT * FROM views WHERE user_id = '$user' AND post_id = '$post_id'");
    if (mysqli_num_rows($isView) > 0) {
        return 0;
    }
    $db->query("INSERT INTO views(user_id, post_id) VALUES('$user', '$post_id')");
    return 1;
}

function setNotificationVisitProfile($user)
{
    global $db;

    $currentTime = date("Y:m:d H:i:s");
    $user_id = $_SESSION['myapp_login'];

    if ($user == $user_id) {
        return 0;
    }

    $getInfo = $db->query("SELECT * FROM notification WHERE user_id = '$user' AND person_id = '$user_id' AND type = 'visitProfile' ORDER BY id DESC LIMIT 1");
    $fetch = mysqli_fetch_assoc($getInfo);


    if (mysqli_num_rows($getInfo) > 0) {
        $time = strtotime($currentTime) - strtotime($fetch['time']);

        if ($time < 86400) {
            return 0;
        } else {
            $db->query("INSERT INTO notification(user_id, person_id, type, time) VALUES('$user','$user_id','visitProfile','$currentTime')");
        }
    } else {
        $db->query("INSERT INTO notification(user_id, person_id, type, time) VALUES('$user','$user_id','visitProfile','$currentTime')");
    }
}

function checkUserExists($username)
{
    global $db;

    $check = $db->query("SELECT * FROM users WHERE username = '$username'");
    $isUser  = mysqli_num_rows($check);
    $fetch = $check->fetch_assoc();

    if ($isUser > 0) {
        $uuid = $fetch['user_id'];
        return $uuid;
    } else {
        return 0;
    }
}

function convertHashtoLink($string)
{
    $expression = "/(@|#)([a-zA-Z0-9_]+)/";
    $string = preg_replace_callback($expression, 'replaceCallback', $string);
    return $string;
}

function replaceCallback($matches)
{
    $tag = $matches[1];
    $text = $matches[2];
    $url = '';

    if ($tag === '@') {
        $userExistsId = checkUserExists($text);

        if ($userExistsId) {
            $url = BASEURL . '/profile/user?id=' . urlencode($userExistsId);
        }
    } elseif ($tag === '#') {
        $url = BASEURL . '/search?q=' . urlencode($text);
    }

    return $url !== '' ? '<a href="' . $url . '" style="text-decoration: none;">' . $matches[0] . '</a>' : $matches[0];
}

function convertHashtoLinkReels($string)
{
    $expression = "/(@|#)([a-zA-Z0-9_]+)/";
    $string = preg_replace_callback($expression, 'replaceCallbackReels', $string);
    return $string;
}

function replaceCallbackReels($matches)
{
    $tag = $matches[1];
    $text = $matches[2];
    $url = '';

    if ($tag === '@') {
        $userExistsId = checkUserExists($text);

        if ($userExistsId) {
            $url = BASEURL . '/profile/user?id=' . urlencode($userExistsId);
        }
    } elseif ($tag === '#') {
        $url = BASEURL . '/search?q=' . urlencode($text);
    }

    return $url !== '' ? '<a href="' . $url . '" style="text-decoration: none" class="text-white fw-bold">' . $matches[0] . '</a>' : $matches[0];
}

function totalNotification($time)
{
    global $db;
    $uuid = $_SESSION['myapp_login'];
    $currentTime = date("Y:m:d H:i:s");

    $getNotification = $db->query("SELECT * FROM notification WHERE user_id = '$uuid' AND time > '$time'");

    setcookie("notification", $currentTime, time() + 31536000);

    return mysqli_num_rows($getNotification);
}

function getNotification($time)
{
    global $db;
    $uuid = $_SESSION['myapp_login'];

    $getNotification = $db->query("SELECT * FROM notification WHERE user_id = '$uuid' AND time > '$time'");

    return mysqli_num_rows($getNotification);
}

function gethashtagViews($tag)
{
    global $db;

    $totalViews = 0;
    $viewsQuery = $db->query("SELECT * FROM hashtag WHERE hashtag = '$tag' GROUP BY post_id ORDER BY hashtag ASC");

    while ($dataPost = mysqli_fetch_assoc($viewsQuery)) :
        $post_id = $dataPost['post_id'];
        $getTotalViews = $db->query("SELECT * FROM views WHERE post_id = '$post_id'");
        $h = mysqli_num_rows($getTotalViews);
        $totalViews += $h;
    endwhile;

    return $totalViews;
}

function isFollow($uuid, $person)
{
    global $db;
    $isFollow = $db->query("SELECT * FROM following WHERE user_id = '$uuid' AND following = '$person'");
    return mysqli_num_rows($isFollow);
}
