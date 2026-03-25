<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
@php
    $server = env('URL_ADMIN');
    $fav = $bases['siteFavicon'] ?? '';
    $fullFav = $server . "/" . $fav;
@endphp
<title>{{ auth()->user()->fname . "'s Dashboard - " . $bases['siteName'] ?? 'User Profile Dashboard' }}</title>
<link rel="icon" type="image/png" sizes="16x16" href="{{ $fullFav }}">
<!-- plugin css file  -->
<link rel="stylesheet" href="/assets/css/carousel.min.css" />
<!-- project css file  -->
<link rel="stylesheet" href="/assets/css/e-learn.style.min.css">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    .brand-icon {
        transition: color 0.3s ease;
        text-decoration: none;
    }

    .brand-icon .nano-logo {
        transition: transform 0.6s ease-in-out;
        transform-origin: center;
    }

    .brand-icon:hover .nano-logo {
        transform: rotate(360deg) scale(1.1);
    }
    .sidebar-logo-container {
        /* margin-top: auto to push to bottom via flex */
        /* centered content */
    }

    .sidebar-logo-img {
        max-width: 80px;      /* adjust width */
        max-height: 80px;     /* keep it square */
        object-fit: contain;
        opacity: 0.7;         /* optional slight transparency */
        transition: opacity 0.3s ease;
    }

    .sidebar-logo-img:hover {
        opacity: 1;
        cursor: pointer;
    }

    #nprogress .bar {
        background: #5E61E6 !important; /* Tailwind sky-400, change as needed */
        height: 3px;
    }
    #nprogress .spinner {
        display: none;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />
