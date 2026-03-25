
<!-- Jquery Core Js -->
<script src="/assets/bundles/libscripts.bundle.js"></script>

<!-- Plugin Js-->
<script src="/assets/bundles/carousel.bundle.js"></script>
<script src="/assets/bundles/apexcharts.bundle.js"></script>

<!-- Jquery Page Js -->
<script src="/assets/js/page/elearn-index.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
<script>
    $(document).on('click', 'a', function (e) {
        const href = $(this).attr('href');

        if (href && href !== '#' && !href.startsWith('javascript:')) {
            e.preventDefault(); // prevent default navigation for demo

            NProgress.start();

            // Simulate a delay (e.g., fetching content)
            setTimeout(() => {
                NProgress.done();
                window.location.href = href; // proceed with navigation
            }, 1000);
        }
    });

    $(window).on('load', function () {
        NProgress.done();
    });
</script>
