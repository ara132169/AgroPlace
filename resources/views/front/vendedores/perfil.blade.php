@php
    use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
@include('front.layout.inc.head')
</head>


<body class="home">
    <!-- start of .page-wrapper -->
    <div class="page-wrapper">
        <h1 class="d-none">Agro - MarketPlace</h1>
        <!-- Start of Header -->
		@include('front.layout.inc.headerdos')
        <!-- End of Header -->
<br>
         <!-- Start of Main -->
         <main class="main">
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb bb-no">
                        <li><a href="demo1.html">Inicio</a></li>
                        <li><a href="#">Tienda</a></li>
                        <li><a href="#">{{ $vendedor->shop->shop_name }}</a></li>
                      
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of Pgae Contetn -->
            <div class="page-content mb-8">
                <div class="container">
                    <div class="row gutter-lg">
                        <aside class="sidebar left-sidebar vendor-sidebar sticky-sidebar-wrapper sidebar-fixed">
                            <!-- Start of Sidebar Overlay -->
                            <div class="sidebar-overlay"></div>
                            <a class="sidebar-close" href="#"><i class="close-icon"></i></a>
                            <a href="#" class="sidebar-toggle"><i class="fas fa-chevron-right"></i></a>
                            <div class="sidebar-content">
                                <div class="sticky-sidebar">
                                   
                                    <!-- End of Widget -->
                                    <div class="widget widget-collapsible widget-contact">
                                        <h3 class="widget-title"><span>Contactar Vendedor</span></h3>
                                        <div class="widget-body">
                                            <input type="text" class="form-control" name="name" id="name"
                                                placeholder="Nombre" />
                                            <input type="text" class="form-control" name="email" id="email_1"
                                                placeholder="email" />
                                            <textarea name="message" maxlength="1000" cols="25" rows="6"
                                                placeholder="Escribe tu mensaje..." class="form-control"
                                                required="required"></textarea>
                                            <a href="#" class="btn btn-dark btn-rounded">Enviar</a>
                                        </div>
                                    </div>
                                    <!-- End of Widget -->
                                </div>
                            </div>
                        </aside>
                        <!-- End of Sidebar -->

                        <div class="main-content">
                            <div class="store store-banner mb-4">
                                <figure class="store-media">
                                    <img src="{{ $vendedor->shop && $vendedor->shop->shop_banner ? asset('images/shop/' . $vendedor->shop->shop_banner) : asset('images/shop/banner.webp') }}" alt="Vendor" width="930" height="446"
                                        style="background-color: #414960;" />
                                </figure>
                                <div class="store-content">
                          
                                <figure class="seller-brand">  
                                <img src="{{ !empty($vendedor->shop?->shop_logo) && file_exists(public_path('images/shop/' . $vendedor->shop->shop_logo)) 
                                    ? asset('images/shop/' . $vendedor->shop->shop_logo) 
                                    : asset('images/users/default-avatar.png') }}" 
                                    alt="Brand" width="80" height="80" />                           
                               </figure>
                                  
                                    <h4 class="store-title">{{ $vendedor->shop->shop_name }}</h4>
                                    <ul class="seller-info-list list-style-none mb-6">
                                        <li class="store-address">
                                            <i class="w-icon-map-marker"></i>
                                            {{ $vendedor->shop->shop_address }}
                                        </li>
                                        <li class="store-phone">
                                            <a href="tel:{{ $vendedor->shop->shop_phone }}">
                                                <i class="w-icon-phone"></i>
                                                {{ $vendedor->shop->shop_phone }}
                                            </a>
                                        </li>
                                       
                                    </ul>
                                    <div class="social-icons social-no-color border-thin">
                                        <a href="#" class="social-icon social-facebook w-icon-facebook"></a>                   
                                        <a href="#" class="social-icon social-youtube w-icon-youtube"></a>
                                        <a href="#" class="social-icon social-instagram w-icon-instagram"></a>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Store Banner -->

                            <h2 class="title vendor-product-title mb-4"><a href="#">Productos</a></h2>

                            <div class="product-wrapper row cols-md-3 cols-sm-2 cols-2">
                            @forelse($productos as $producto)
                                <div class="product-wrap">
                                    <div class="product text-center">
                                        <figure class="product-media">
                                            <a href="product-default.html">
                                                <img src="{{ asset('images/products/' . $producto->product_image) }}" alt="Product" width="300"
                                                    height="338" loading="lazy" />
                                            </a>
                                            
                                        </figure>
                                        <div class="product-details">
                                            <h3 class="product-name">
                                                <a href="{{ route('producto.index', $producto->slug) }}">{{ $producto->name }}</a>
                                            </h3>
                                            
                                            <div class="product-pa-wrapper">
                                                    <div class="product-price">
                                                @if($producto->discount_price)
                                                    <del class="text-muted">${{ number_format($producto->price, 2) }}</del>
                                                    <span class="text-danger ms-2"> ${{ number_format($producto->compare_price, 2) }} </span>
                                                @else
                                                    ${{ number_format($producto->price, 2) }}
                                                @endif
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- End of Product Wrap -->
                                @empty
                                <p>No hay productos publicados aún.</p>
                            @endforelse
                           
                            </div>
                        </div>
                        <!-- End of Main Content -->
                    </div>
                </div>
            </div>
            <!-- End of Page Content -->
        </main>
        <!-- End of Main -->

        @include('front.layout.inc.footer')
        <!-- End of Footer -->
    </div>
    <!-- end of .page-wrapper -->

    <!-- Start of Sticky Footer -->
    @include('front.layout.inc.footermovil')
    <!-- End of Sticky Footer -->

    <!-- Start of Scroll Top -->
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> <i class="w-icon-angle-up"></i> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"> <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle> </svg> </a>
    <!-- End of Scroll Top -->

    <!-- Start of Mobile Menu -->
        @include('front.layout.inc.mobile-menu')
    <!-- End of Mobile Menu -->

    <!-- Start of Newsletter popup -->
    <div class="newsletter-popup mfp-hide">
        <div class="newsletter-content">
            <h4 class="text-uppercase font-weight-normal ls-25 text-white">Obtén grandes <span class="text-primary">descuentos</span></h4>
            <h2 class="ls-25 text-white">Suscríbete a nuestro Newsletter</h2>
            <p class="ls-10 text-white">Obtendrás grandes promociones y noticias recientes.</p>
            <form action="#" method="get" class="input-wrapper input-wrapper-inline input-wrapper-round">
                <input type="email" class="form-control email font-size-md text-white" name="email" id="email2"
                    placeholder="Ingresa tu correo" required="">
                <button class="btn btn-dark" type="submit">ENVIAR</button>
            </form>
            <div class="form-checkbox d-flex align-items-center">
                <input type="checkbox" class="custom-checkbox" id="hide-newsletter-popup" name="hide-newsletter-popup"
                    required="">
                <label for="hide-newsletter-popup" class="font-size-sm text-white">No mostrar otra vez.</label>
            </div>
        </div>
    </div>
    <!-- End of Newsletter popup -->



    <script src="/front/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/front/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="/front/assets/vendor/parallax/parallax.min.js"></script>
    <script src="/front/assets/vendor/jquery.plugin/jquery.plugin.min.js"></script>
    <script src="/front/assets/vendor/jquery.countdown/jquery.countdown.min.js"></script>
    <script src="/front/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="/front/assets/vendor/isotope/isotope.pkgd.min.js"></script>
    <script src="/front/assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="/front/assets/vendor/zoom/jquery.zoom.js"></script>

    <!-- Main JS -->
    <script src="/front/assets/js/main.min.js"></script>
</body>

</html>