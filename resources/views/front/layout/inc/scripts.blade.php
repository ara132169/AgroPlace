<!-- Plugin JS File -->
<script src="{{ asset('front/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('front/vendor/sticky/sticky.min.js') }}"></script>
<script src="{{ asset('front/vendor/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('front/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
<script src="{{ asset('front/vendor/zoom/jquery.zoom.min.js') }}"></script>
<script src="{{ asset('front/vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('front/vendor/nouislider/nouislider.min.js') }}"></script>
<script src="{{ asset('front/vendor/photoswipe/photoswipe.min.js') }}"></script>
<script src="{{ asset('front/vendor/photoswipe/photoswipe-ui-default.min.js') }}"></script>

<!-- Main JS File -->
<script src="{{ asset('front/js/main.min.js') }}"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Custom Scripts -->
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
</script>

@stack('scripts')