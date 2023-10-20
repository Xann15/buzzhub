<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Player with Controls</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .video-container {
            position: relative;
            max-width: 100%;
        }

        #myVideo {
            width: 100%;
        }

        #videoControls {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 10px;
            color: white;
            display: flex;
            align-items: center;
        }

        #seekBar {
            width: 100%;
            margin-left: 10px;
            margin-right: 10px;
        }

        #seekBarTooltip {
            position: absolute;
            display: none;
            padding: 5px;
            background-color: black;
            color: white;
            font-size: 12px;
            border-radius: 4px;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>

<body>
    <div class="video-container">
        <video id="myVideo" loading="lazy" loop style="width: 100vw;">
            <source src="video.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div id="videoControls">
            <button id="playPause" class="btn btn-primary">
                <i class="in bi bi-play fs-2 fw-bold"></i>
            </button>
            <input type="range" id="seekBar" value="0">
            <div id="seekBarTooltip"></div>
            <span id="currentTime">0:00</span>
        </div>
    </div>
    <div class="video-container">
        <video id="myVideo2" loading="lazy" loop style="width: 100vw;">
            <source src="video.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div id="videoControls">
            <button id="playPause" class="btn btn-primary">
                <i class="in bi bi-play fs-2 fw-bold"></i>
            </button>
            <input type="range" id="seekBar" value="0">
            <div id="seekBarTooltip"></div>
            <span id="currentTime">0:00</span>
        </div>
    </div>
    <div class="video-container">
        <video id="myVideo3" loading="lazy" loop style="width: 100vw;">
            <source src="video.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div id="videoControls">
            <button id="playPause" class="btn btn-primary">
                <i class="in bi bi-play fs-2 fw-bold"></i>
            </button>
            <input type="range" id="seekBar" value="0">
            <div id="seekBarTooltip"></div>
            <span id="currentTime">0:00</span>
        </div>
    </div>
    <div class="video-container">
        <video id="myVideo4" loading="lazy" loop style="width: 100vw;">
            <source src="video.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div id="videoControls">
            <button id="playPause" class="btn btn-primary">
                <i class="in bi bi-play fs-2 fw-bold"></i>
            </button>
            <input type="range" id="seekBar" value="0">
            <div id="seekBarTooltip"></div>
            <span id="currentTime">0:00</span>
        </div>
    </div>
    <div class="video-container">
        <video id="myVideo5" loading="lazy" loop style="width: 100vw;">
            <source src="video.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div id="videoControls">
            <button id="playPause" class="btn btn-primary">
                <i class="in bi bi-play fs-2 fw-bold"></i>
            </button>
            <input type="range" id="seekBar" value="0">
            <div id="seekBarTooltip"></div>
            <span id="currentTime">0:00</span>
        </div>
    </div>
    <div class="video-container">
        <video id="myVideo6" loading="lazy" loop style="width: 100vw;">
            <source src="video.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div id="videoControls">
            <button id="playPause" class="btn btn-primary">
                <i class="in bi bi-play fs-2 fw-bold"></i>
            </button>
            <input type="range" id="seekBar" value="0">
            <div id="seekBarTooltip"></div>
            <span id="currentTime">0:00</span>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            var video = $('#myVideo')[0]; // Ambil elemen video pertama dalam koleksi

            // Ketika tombol Play/Pause diklik
            $('#playPause').click(function() {
                if (video.paused || video.ended) {
                    video.play();
                    $('#playPause i').removeClass('bi-play');
                    $('#playPause i').addClass('bi-pause');
                } else {
                    video.pause();
                    $('#playPause i').removeClass('bi-pause');
                    $('#playPause i').addClass('bi-play');
                }
            });

            // Ketika waktu pemutaran video berubah
            video.addEventListener('timeupdate', function() {
                var currentTime = video.currentTime;
                var duration = video.duration;

                // Perbarui nilai slider dan waktu saat ini
                $('#seekBar').val((currentTime / duration) * 100);
                $('#currentTime').text(formatTime(currentTime));
            });

            // Ketika tombol maju mundur slider diubah
            $('#seekBar').on('input', function() {
                var seekTime = (video.duration * ($('#seekBar').val() / 100));
                video.currentTime = seekTime;
            });

            // Fungsi untuk mengubah waktu dalam format menit:detik
            function formatTime(time) {
                var minutes = Math.floor(time / 60);
                var seconds = Math.floor(time % 60);
                return minutes + ':' + (seconds < 10 ? '0' : '') + seconds;
            }

            $('#seekBar').on('input', function() {
                var seekTime = (video.duration * ($('#seekBar').val() / 100));
                video.currentTime = seekTime;
            });

            var seekBar = $('#seekBar');
            var seekBarTooltip = $('#seekBarTooltip');

            seekBar.on('mousemove touchmove', function(e) {
                var seekBarWidth = seekBar.outerWidth();
                var offsetX = e.offsetX || (e.originalEvent.touch[0].pageX - seekBar.offset().left);
                var hoverTime = (offsetX / seekBarWidth) * video.duration;

                seekBarTooltip.text(formatTime(hoverTime) + ' / ' + formatTime(video.duration));
                seekBarTooltip.css('left', offsetX - seekBarTooltip.outerWidth() / 2);
                seekBarTooltip.show();
            }).on('mouseout touchend', function() {
                seekBarTooltip.hide();
            });
        });
    </script>
    </script>
</body>

</html>