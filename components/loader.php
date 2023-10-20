<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    require_once 'header.php';
    require_once 'bottom_nav.php';
    ?>
    <div id="preloader" class="d-flex" style="height: 60vh">
        <svg class="m-auto" style="heigh:100px; width: 100px;" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
            <circle cx="21.5" cy="55.5" r="4" fill="#000000">
                <animate attributeName="cy" calcMode="spline" keySplines="0 0.5 0.5 1;0.5 0 1 0.5;0.5 0.5 0.5 0.5" repeatCount="indefinite" values="55.5;44.5;55.5;55.5" keyTimes="0;0.395;0.79;1" dur="1.3157894736842106s" begin="-1.0394736842105263s"></animate>
            </circle>
            <circle cx="40.5" cy="55.5" r="4" fill="#000000">
                <animate attributeName="cy" calcMode="spline" keySplines="0 0.5 0.5 1;0.5 0 1 0.5;0.5 0.5 0.5 0.5" repeatCount="indefinite" values="55.5;44.5;55.5;55.5" keyTimes="0;0.395;0.79;1" dur="1.3157894736842106s" begin="-0.7796052631578948s"></animate>
            </circle>
            <circle cx="59.5" cy="55.5" r="4" fill="#000000">
                <animate attributeName="cy" calcMode="spline" keySplines="0 0.5 0.5 1;0.5 0 1 0.5;0.5 0.5 0.5 0.5" repeatCount="indefinite" values="55.5;44.5;55.5;55.5" keyTimes="0;0.395;0.79;1" dur="1.3157894736842106s" begin="-0.5197368421052632s"></animate>
            </circle>
            <circle cx="78.5" cy="55.5" r="4" fill="#000000">
                <animate attributeName="cy" calcMode="spline" keySplines="0 0.5 0.5 1;0.5 0 1 0.5;0.5 0.5 0.5 0.5" repeatCount="indefinite" values="55.5;44.5;55.5;55.5" keyTimes="0;0.395;0.79;1" dur="1.3157894736842106s" begin="-0.2598684210526316s"></animate>
            </circle>
        </svg>
    </div>

    <script>
        window.onload = function() {
            window.setTimeout(fadeOut, 1500)
            document.querySelector('#nav').style.display = 'none';
            document.querySelector('#app').style.display = 'none';
        }

        function fadeOut() {
            document.querySelector('#preloader').style.display = 'none';
            document.querySelector('#preloader').style.height = '0';
            document.querySelector('#preloader').style.opacity = '0';
            document.querySelector('#nav').style.display = '';
            document.querySelector('#app').style.display = '';
        }
    </script>
</body>

</html>