<link href="https://fonts.googleapis.com/css?family=Russo+One" rel="stylesheet">

<svg class="svg-wrapper" viewBox="0 0 1320 300">
    <text class="svg-text" x="50%" y="50%" dy=".35em" text-anchor="middle">
        buzzhub
    </text>
</svg>

<style>
    .svg-wrapper {
        font-family: 'Russo One', sans-serif;
        position: absolute;
        width: 100%;
        height: 100%;
    }

    .svg-wrapper .svg-text {
        text-transform: uppercase;
        animation: stroke 3s alternate;
        stroke-width: 2;
        stroke: black;
        font-size: 140px;
    }

    @keyframes stroke {
        0% {
            fill: rgba(0, 0, 0, 0);
            stroke: rgba(0, 0, 0, 1);
            stroke-dashoffset: 25%;
            stroke-dasharray: 0 50%;
            stroke-width: 2;
        }

        70% {
            fill: rgba(0, 0, 0, 0);
            stroke: rgba(0, 0, 0, 1);
        }

        80% {
            fill: rgba(0, 0, 0, 0);
            stroke: rgba(0, 0, 0, 1);
            stroke-width: 3;
        }

        100% {
            fill: rgba(0, 0, 04, 1);
            stroke: rgba(54, 95, 160, 0);
            stroke-dashoffset: -25%;
            stroke-dasharray: 50% 0;
            stroke-width: 0;
        }
    }
</style>