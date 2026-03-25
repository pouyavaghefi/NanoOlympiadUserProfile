<!-- Jquery Core Js -->
<script src="/course_player/bundles/libscripts.bundle.js"></script>

<!-- Plugin Js-->
<script src="/course_player/bundles/carousel.bundle.js"></script>

<!-- Jquery Page Js -->
{{--<script src="/course_player/js/template.js"></script>--}}
<script src="/course_player/js/page/video.js"></script>
<script>
    window.addEventListener('load', function () {
        document.querySelector('#scrollHere').scrollIntoView({
            behavior: 'smooth'
        });
    });
</script>

@include('sweetalert::alert')
