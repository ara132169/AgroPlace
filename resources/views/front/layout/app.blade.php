<!DOCTYPE html>
<html lang="en">

<head>
@include('front.layout.inc.head')
</head>

<body class="@yield('bodyClass', 'home')">
    <!-- start of .page-wrapper -->
    <div class="page-wrapper">
        <h1 class="d-none">Agro - MarketPlace</h1>
        <!-- Start of Header -->
        @include('front.layout.inc.header')
        <!-- End of Header -->
        
        <!-- Start of Main -->
        <main class="main">
            @yield('content')
        </main>
        <!-- End of Main -->

        <!-- Start of Footer -->
        @include('front.layout.inc.footer')
        <!-- End of Footer -->

    </div>
    <!-- end of .page-wrapper -->

    <!-- Start of Scroll Top -->
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> <i class="w-icon-angle-up"></i> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"> <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle> </svg> </a>
    <!-- End of Scroll Top -->

    <!-- Start of Mobile Menu -->
    @include('front.layout.inc.mobile-menu')
    <!-- End of Mobile Menu -->

    <!-- Plugin JS File -->
    @include('front.layout.inc.scripts')

    @stack('scripts')

</body>

</html>