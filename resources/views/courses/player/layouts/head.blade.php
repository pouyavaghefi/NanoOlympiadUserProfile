<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title')</title>
<link rel="icon" type="image/png" sizes="16x16" href="/assets/img/icon/favicon.jpg">
<!-- plugin css file  -->
<link rel="stylesheet" href="/course_player/css/carousel.min.css" />
<!-- project css file  -->
<link rel="stylesheet" href="/course_player/css/e-learn.style.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body {
        overflow-x: hidden;
    }
    hr.sep-1 {
        position: relative;
        display: block;
        margin-top: 4em;
        margin-bottom: 4em;
        height: 3px;
        border:none;
        background: linear-gradient(to right, transparent 50%, #fff 50%), linear-gradient(to right, #00b9ff, #59d941);
        background-size: 1.5rem, 100%;
        transform: rotate(-5deg);
        transform-origin: 50% 0%;
    }

    hr.sep-1::after {
        font-family: 'FontAwesome';
        content: '\f061     \f1cb     \f060';
        white-space: pre;
        display: block;
        position: absolute;
        top: 1px;
        left: 50%;
        padding-left: 1rem;
        padding-right: 1rem;
        transform: translate(-50%, -50%);
        transform-origin: 50% 50%;
        background-image: linear-gradient(to right, #00b9ff, #59d941);
        padding: 0 1em;
        color: transparent;
        -webkit-background-clip: text;
    }
    hr.sep-1::before {
        content: '';
        width: 5rem;
        height: 1rem;
        display: block;
        position: absolute;
        left: 50%;
        transform: translate(-50%, -50%);
        transform-origin: 50% 50%;
        background-color: white;
    }
    .episode-number {
        display: inline-block;
        width: 30px; /* Adjust the size */
        height: 30px; /* Adjust the size */
        background-color: blue; /* Blue circle */
        color: white; /* White text color */
        border-radius: 50%; /* Make it circular */
        text-align: center; /* Center the text horizontally */
        line-height: 30px; /* Center the text vertically */
        font-weight: bold; /* Optional: makes the number bolder */
        font-size: 14px; /* Adjust the font size */
    }

</style>
@yield('styles')

@php
    $server = env('URL_ADMIN');
    $fav = $bases['siteFavicon'] ?? '';
    $fullFav = $server . "/" . $fav;
@endphp
<link rel="icon" type="image/png" sizes="16x16" href="{{ $fullFav }}">