<!doctype html>
<html class="no-js" lang="en" dir="ltr">

<head>
    @include('courses.player.layouts.head')
</head>

<body>

<div id="elearn-layout" class="theme-purple">
    @yield('wrapper')
</div>

@include('courses.player.layouts.scripts')
@yield('scripts')
</body>
</html>