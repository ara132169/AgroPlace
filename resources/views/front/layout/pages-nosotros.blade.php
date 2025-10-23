<!DOCTYPE html>
<html lang="en">

<head>
@include('front.layout.inc.head')
</head>

<body class="about-us">
    <div class="page-wrapper">
        <!-- Start of Header -->
        @include('front.layout.inc.headerdos')
        <!-- End of Header -->

        <!-- Start of Main -->
        <main class="main">
            <!-- Start of Page Header -->
            <div class="page-header">
                <div class="container">
                    <h1 class="page-title mb-0">Acerca de AgroPlace</h1>
                </div>
            </div>
            <!-- End of Page Header -->

            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav mb-10 pb-8">
                <div class="container">
                    <ul class="breadcrumb">
                        <li><a href="{{ url('/') }}">Inicio</a></li>
                        <li>Nosotros</li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->
            
            <!-- Start of Page Content -->
            <div class="page-content">
                <div class="container">
                    <section class="introduce mb-10 pb-10">
                        <h2 class="title title-center">
                        Bienvenidos a nuestra plataforma de <br> Marketplace agroindustrial
                        </h2>
                        <p class=" mx-auto text-center">Un espacio único donde los productores, distribuidores y comerciantes pueden ofrecer sus productos al mundo. </p>
                        <figure class="br-lg">
                            <img src="/front/assets/images/pages/about_us/1.jpg" alt="Banner" 
                                width="1240" height="540" style="background-color: #D0C1AE;" />
                        </figure>
                    </section>

                    <section class="customer-service mb-7">
                        <div class="row align-items-center">
                            <div class="col-md-6 pr-lg-8 mb-8">
                                <h2 class="title text-left">Diseñada especialmente para el sector agrícola</h2>
                                <div class="accordion accordion-simple accordion-plus">
                                    <div class="card border-no">
                                        <div class="card-header">
                                            <a href="#collapse3-1" class="collapse">Beneficios</a>
                                        </div>
                                        <div class="card-body expanded" id="collapse3-1">
                                            <p class="mb-0">
                                            Aquí, cada productor tiene la oportunidad de posicionar sus productos frente a miles de potenciales compradores, desde pequeños negocios hasta grandes distribuidores y consumidores finales, todos interesados en lo mejor que la agroindustria tiene para ofrecer.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <a href="#collapse3-2" class="expand">¿Por qué elegirnos?</a>
                                        </div>
                                        <div class="card-body collapsed" id="collapse3-2">
                                            <p class="mb-0">
                                            Nuestro marketplace no solo es un sitio de venta, sino un escenario dinámico que permite la promoción de nuevos productos y ofertas especiales, impulsando la innovación y la competitividad dentro del sector. Al unir a productores y clientes en un mismo espacio, no solo facilitamos la comercialización de productos agrícolas y agroindustriales, sino que también apoyamos el desarrollo sostenible de la industria.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <a href="#collapse3-3" class="expand">Nuestra filosofía</a>
                                        </div>
                                        <div class="card-body collapsed" id="collapse3-3">
                                            <p class="mb-0">
                                            Creemos en el valor de ofrecer productos de alta calidad, en apoyar la economía local y en crear un entorno transparente y confiable donde todos los miembros de la cadena agroindustrial puedan prosperar.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-8">
                                <figure class="br-lg">
                                    <img src="/front/assets/images/pages/about_us/2.jpg" alt="Banner"
                                        width="610" height="500" style="background-color: #CECECC;" />
                                </figure>
                            </div>
                        </div>
                    </section>

                    <section class="count-section mb-10 pb-5">
                        <div class="swiper-container swiper-theme" data-swiper-options="{
                            'slidesPerView': 1,
                            'breakpoints': {
                                '768': {
                                    'slidesPerView': 2
                                },
                                '992': {
                                    'slidesPerView': 3
                                }
                            }
                        }">
                            <div class="swiper-wrapper row cols-lg-3 cols-md-2 cols-1">
                                <div class="swiper-slide counter-wrap">
                                    <div class="counter text-center">
                                        
                                    <span class="title title-center">Misión</span>
                                        <p>Nuestra misión es revolucionar la agroindustria mediante una plataforma digital inclusiva y accesible, que permita a los productores, comerciantes y compradores conectarse y hacer negocios de manera eficiente, rentable y sostenible</p>
                                    </div>
                                </div>
                                <div class="swiper-slide counter-wrap">
                                    <div class="counter text-center">
                                        
                                    <span class="title title-center">Visión</span>
                                        <p>Aspiramos a convertirnos en la principal plataforma de comercio digital de productos agroindustriales a nivel nacional e internacional, liderando la transformación digital de la industria y proporcionando un espacio de confianza, eficiencia y sostenibilidad.</p>
                                    </div>
                                </div>
                                <div class="swiper-slide counter-wrap">
                                    <div class="counter text-center">
                                    
                                        <span class="title title-center">Valores</span>
                                       
                                        <p>Nos destacamos por nuestra transparencia y compromiso con la calidad, asegurando un entorno de confianza en cada transacción y manteniendo altos estándares para que tanto compradores como vendedores puedan acceder a productos y servicios fiables. </p>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </section>
                </div>

                <section class="boost-section pt-10 pb-10">
                    <div class="container mt-10 mb-9">
                        <div class="row align-items-center mb-10">
                            <div class="col-md-6 mb-8">
                                <figure class="br-lg">
                                    <img src="/front/assets/images/pages/about_us/3.jpg" alt="Banner"
                                        width="610" height="450" style="background-color: #9E9DA2;" />
                                </figure>
                            </div>
                            <div class="col-md-6 pl-lg-8 mb-8">
                                <h4 class="title text-left">¡No solo somos un sitio de venta!</h4>
                                <p class="mb-6">Nuestro marketplace no solo es un sitio de venta, sino un escenario dinámico que permite la promoción de nuevos productos y ofertas especiales, impulsando la innovación y la competitividad dentro del sector. Al unir a productores y clientes en un mismo espacio, no solo facilitamos la comercialización de productos agrícolas y agroindustriales, sino que también apoyamos el desarrollo sostenible de la industria. Creemos en el valor de ofrecer productos de alta calidad, en apoyar la economía local y en crear un entorno transparente y confiable donde todos los miembros de la cadena agroindustrial puedan prosperar.</p>
                                
                            </div>
                        </div>

                        
                    </div>
                </section>

               
            </div>
        </main>
        <!-- End of Main -->

        <!-- Start of Footer -->
        @include('front.layout.inc.footer')
        <!-- End of Footer -->
    </div>

     <!-- Start of Sticky Footer -->
     @include('front.layout.inc.footermovil')
    <!-- End of Sticky Footer -->
  
    
    <!-- Start of Scroll Top -->
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> <i class="w-icon-angle-up"></i> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"> <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle> </svg> </a>
    <!-- End of Scroll Top -->

    <!-- Start of Mobile Menu -->
     @include('front.layout.inc.mobile-menu')
    <!-- End of Mobile Menu -->

    <!-- Plugin JS File -->
    <script src="/front/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/front/assets/vendor/jquery.count-to/jquery.count-to.min.js"></script>
    <script src="/front/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="/front/assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="/front/assets/js/main.min.js"></script>
</body>
</html>