<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    @include('layouts.init.head')
    <title>{{ env('APP_NAME') }}</title>
    @yield('styles')
    <style>
        @keyframes pulse-ring {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.5);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
            }
        }

        .animate-unread {
            animation: pulse-ring 2s infinite;
            border-radius: 8px;
        }

    </style>
</head>
<body>

<div id="elearn-layout" class="theme-purple">
    <!-- sidebar -->
    @include('layouts.includes.overalls.sidebar')

    @yield('main-body')
</div>

@include('layouts.init.scripts')

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@yield('scripts')
</body>
</html>
