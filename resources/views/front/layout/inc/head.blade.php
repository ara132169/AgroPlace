<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>@yield('pageTitle')</title>

    <meta name="keywords" content="Marketplace " />
    <meta name="description" content="Agro Marketplace - venta y compra de insumos agricolas">
    <meta name="author" content="D-THEMES">

    <!-- Favicon -->
    @php
        $favicon = get_settings()->site_favicon ?? 'default-favicon.png';
        $faviconPath = '/images/site/' . $favicon;
        $faviconFullPath = public_path('images/site/' . $favicon);
    @endphp
    
    @if(file_exists($faviconFullPath) && !empty($favicon))
        <link rel="icon" type="image/png" href="{{ $faviconPath }}">
        <link rel="shortcut icon" type="image/png" href="{{ $faviconPath }}">
    @else
        <link rel="icon" type="image/png" href="/front/assets/images/favicon.png">
        <link rel="shortcut icon" type="image/png" href="/front/assets/images/favicon.png">
    @endif

    <!-- WebFont.js -->
    <script>
        WebFontConfig = {
            google: { families: ['Poppins:400,500,600,700'] }
        };
        (function (d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = '/front/assets/js/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>

<link rel="preload" href="/front/assets/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="/front/assets/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="/front/assets/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2"
            crossorigin="anonymous">
    <link rel="preload" href="/front/assets/fonts/wolmart.woff?png09e" as="font" type="font/woff" crossorigin="anonymous">

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="/front/assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="/front/assets/vendor/animate/animate.min.css">

    <!-- Plugin CSS -->
    <link rel="stylesheet" href="/front/assets/vendor/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="/front/assets/vendor/magnific-popup/magnific-popup.min.css">

    <!-- Default CSS -->
    <link rel="stylesheet" type="text/css" href="/front/assets/css/style.min.css">
        <link rel="stylesheet" type="text/css" href="/front/assets/css/demo7.min.css">