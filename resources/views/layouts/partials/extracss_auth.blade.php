<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<style>
    /* ================================
           BACKGROUND OPTIONS
           ================================ */
    /* Note: Only uncomment one of the options below. */
    html {
        height: 100%;
        min-height: 100%;
        width: 100%;
        overflow: hidden;
        background: transparent;
    }

    body {
        height: 100%;
        min-height: 100vh;
        margin: 0;
        padding: 0;
        position: relative;
        width: 100%;
        background: transparent;
        overflow: hidden;
    }

    .auth-page {
        height: 100%;
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }

    .auth-page-body {
        position: relative;
        z-index: 1;
    }

    .auth-page .auth-page-bg {
        position: fixed;
        inset: 0;
        z-index: -2;
        overflow: hidden;
        background: linear-gradient(155deg, #0d572b 0%, #1d8349 45%, #4aae72 100%);
        pointer-events: none;
    }

    .auth-page .auth-page-bg .poly {
        position: absolute;
        display: block;
    }

    .poly-1 {
        top: -12%;
        left: -8%;
        width: 46%;
        height: 55%;
        background: #1f823f;
        clip-path: polygon(0 0, 100% 15%, 60% 100%, 0% 80%);
        opacity: 0.9;
    }
    .poly-2 {
        top: -6%;
        left: 22%;
        width: 38%;
        height: 42%;
        background: #2d9b4f;
        clip-path: polygon(0 10%, 100% 0, 80% 100%, 10% 90%);
        opacity: 0.5;
    }
    .poly-3 {
        top: 0;
        left: 52%;
        width: 36%;
        height: 48%;
        background: #176b35;
        clip-path: polygon(0 0, 100% 0, 70% 100%, 0 60%);
        opacity: 0.85;
    }
    .poly-4 {
        top: -10%;
        right: -10%;
        width: 38%;
        height: 46%;
        background: #3f9e56;
        clip-path: polygon(0 20%, 100% 0, 100% 100%, 20% 100%);
        opacity: 0.7;
    }
    .poly-5 {
        top: 30%;
        left: -10%;
        width: 34%;
        height: 40%;
        background: #319265;
        clip-path: polygon(0 0, 100% 25%, 70% 100%, 0 80%);
        opacity: 0.55;
    }
    .poly-6 {
        top: 32%;
        left: 14%;
        width: 34%;
        height: 34%;
        background: #186032;
        clip-path: polygon(0 0, 100% 20%, 60% 100%, 0 100%);
        opacity: 0.8;
    }
    .poly-7 {
        top: 28%;
        left: 42%;
        width: 30%;
        height: 36%;
        background: #4aa26a;
        clip-path: polygon(15% 0, 100% 10%, 85% 100%, 0 85%);
        opacity: 0.45;
    }
    .poly-8 {
        top: 22%;
        right: -6%;
        width: 32%;
        height: 42%;
        background: #27814d;
        clip-path: polygon(0 0, 100% 0, 100% 100%, 30% 80%);
        opacity: 0.75;
    }
    .poly-9 {
        bottom: -10%;
        left: -8%;
        width: 40%;
        height: 46%;
        background: #21703f;
        clip-path: polygon(0 0, 100% 30%, 70% 100%, 0 100%);
        opacity: 0.85;
    }
    .poly-10 {
        bottom: -8%;
        left: 26%;
        width: 36%;
        height: 40%;
        background: #36a56b;
        clip-path: polygon(0 30%, 100% 0, 100% 100%, 10% 100%);
        opacity: 0.5;
    }
    .poly-11 {
        bottom: -12%;
        left: 54%;
        width: 34%;
        height: 44%;
        background: #3b8f59;
        clip-path: polygon(0 0, 100% 20%, 80% 100%, 0 100%);
        opacity: 0.6;
    }
    .poly-12 {
        bottom: -10%;
        right: -10%;
        width: 38%;
        height: 44%;
        background: #11512e;
        clip-path: polygon(20% 0, 100% 0, 100% 100%, 0 100%);
        opacity: 0.9;
    }

    @media (max-width: 600px) {
        .poly-2,
        .poly-7 {
            display: none;
        }
        .lp-brand,
        .lp-brand-copy {
            display: none;
        }
    }

    h1 {
        color: #fff;
    }
</style>

<style type="text/css">
    /*
      * Pattern lock css
      * Pattern direction
      * http://ignitersworld.com/lab/patternLock.html
      */
    .patt-wrap {
        z-index: 10;
    }

    .patt-circ.hovered {
        background-color: #cde2f2;
        border: none;
    }

    .patt-circ.hovered .patt-dots {
        display: none;
    }

    .patt-circ.dir {
        background-image: url('http://pos.test/img/pattern-directionicon-arrow.png');
        background-position: center;
        background-repeat: no-repeat;
    }

    .patt-circ.e {
        -webkit-transform: rotate(0);
        transform: rotate(0);
    }

    .patt-circ.s-e {
        -webkit-transform: rotate(45deg);
        transform: rotate(45deg);
    }

    .patt-circ.s {
        -webkit-transform: rotate(90deg);
        transform: rotate(90deg);
    }

    .patt-circ.s-w {
        -webkit-transform: rotate(135deg);
        transform: rotate(135deg);
    }

    .patt-circ.w {
        -webkit-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    .patt-circ.n-w {
        -webkit-transform: rotate(225deg);
        transform: rotate(225deg);
    }

    .patt-circ.n {
        -webkit-transform: rotate(270deg);
        transform: rotate(270deg);
    }

    .patt-circ.n-e {
        -webkit-transform: rotate(315deg);
        transform: rotate(315deg);
    }
</style>
<style>
    h1 {
        color: #fff;
    }
</style>
<style>
    .action-link[data-v-1552a5b6] {
        cursor: pointer;
    }
</style>
<style>
    .action-link[data-v-397d14ca] {
        cursor: pointer;
    }
</style>
<style>
    .action-link[data-v-49962cc0] {
        cursor: pointer;
    }
</style>

<link href="{{ asset('css/tailwind/app.css') }}" rel="stylesheet" />
