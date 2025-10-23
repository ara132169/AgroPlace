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
        @include('front.layout.inc.header')
        <!-- End of Header -->
        <main class="main">
            <div class="page-content">
                <section class="intro-section">
                    <div class="intro-slider swiper-container swiper-theme animation-slider" data-swiper-options = "{
                        'slidesPerView': 1,
                        'autoplay': {
                            'delay': 8000,
                            'disableOnInteraction': false
                        }
                    }">
                    <div class="swiper-wrapper row cols-1 gutter-no">
                            <div  class="swiper-slide banner banner-fixed intro-slide intro-slide1"
                            style="background-image: url(/front/assets/images/demos/demo7/slides/slide1.jpg); background-color: #EEEDEB;">
                                <div class="container">
                                    <div class="banner-content d-inline-block y-50">
                                        <div class="slide-animate" data-animation-options="{
                                            'name': 'fadeInUpShorter', 'duration': '1s'
                                        }">
                                        <h5 class="banner-subtitle text-uppercase text-primary font-weight-bold mb-1">
                                                AGRO MARKET PLACE
                                            </h5>
                                            <h3 class="banner-title ls-25 mb-6 text-white">
                                                <span class="text-white">MarketPlace</span>
                                                Compra y venta de insumos agrícolas 
                                            </h3>
                                            <!-- <a href="#"
                                                class="btn btn-white btn-outline btn-rounded btn-icon-right">
                                                Ver Productos<i class="w-icon-long-arrow-right"></i>
                                            </a> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Intro Slide 1 -->
                            <div class="swiper-slide banner banner-fixed intro-slide intro-slide2"
                            style="background-image: url(/front/assets/images/demos/demo7/slides/slide2.jpg); background-color: #A9A9A9;">
                                <div class="container text-right">
                                    <div class="banner-content y-50 d-inline-block">
                                        <div class="slide-animate" data-animation-options="{
                                            'name': 'zoomIn', 'duration': '1s'
                                        }">
                                        <h5 class="banner-subtitle text-uppercase text-primary font-weight-bold mb-1">
                                                AGRO MARKET PLACE
                                            </h5>
                                            <h3 class="banner-title text-white text-uppercase ls-25">¡Tú mejor opción!</h3>
                                            <div class="banner-price-info text-white">Conoce nuestros productos</div>
                                           <!-- <a href="#"
                                                class="btn btn-white btn-outline btn-rounded btn-icon-right">
                                                VER PRODUCTOS<i class="w-icon-long-arrow-right"></i>
                                            </a> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Intro Slide 2 -->
                            <div class="swiper-slide banner banner-fixed intro-slide intro-slide3"
                                style="background-image: url(/front/assets/images/demos/demo7/slides/slide3.jpg); background-color: #F3F3F3;">
                                <div class="container">
                                    <div class="banner-content y-50 d-inline-block">
                                        <div class="slide-animate" data-animation-options="{
                                            'name': 'fadeInDownShorter', 'duration': '1s'
                                        }">
                                            <h5 class="banner-subtitle text-uppercase text-primary font-weight-bold mb-1">
                                                AGRO MARKET PLACE
                                            </h5>
                                            <h3 class="banner-title text-uppercase ls-25 text-white">Insumos</h3>
                                            <h4 class="ls-25 mb-0 text-white">Agrícolas</h4>
                                            <p class="ls-25 text-white">Todo para tu campo </p>
                                           <!-- <a href="#"
                                                class="btn btn-white btn-rounded btn-outline btn-icon-right">
                                                VER PRODUCTOS<i class="w-icon-long-arrow-right"></i>
                                            </a> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End of Intro Slide 3 
                            <div class="swiper-slide banner banner-fixed intro-slide intro-slide1"
                                style="background-image: url(/front/assets/images/demos/demo7/slides/slide-1.jpg); background-color: #EEEDEB;">
                                <div class="container">
                                    <div class="banner-content d-inline-block y-50">
                                        <div class="slide-animate" data-animation-options="{
                                            'name': 'fadeInUpShorter', 'duration': '1s'
                                        }">
                                            <h5 class="banner-subtitle text-uppercase font-weight-bold">
                                                Performance &amp; Lifestyle
                                            </h5>
                                            <h3 class="banner-title ls-25 mb-6">
                                                <span class="text-primary">Introducing</span>
                                                Fashion lifestyle collection
                                            </h3>
                                            <a href="#"
                                                class="btn btn-dark btn-outline btn-rounded btn-icon-right">
                                                Shop Now<i class="w-icon-long-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                            <!-- End of Intro Slide 1 
                            <div class="swiper-slide banner banner-fixed intro-slide intro-slide3"
                                style="background-image: url(/front/assets/images/demos/demo7/slides/slide-3.jpg); background-color: #F3F3F3;">
                                <div class="container">
                                    <div class="banner-content y-50 d-inline-block">
                                        <div class="slide-animate" data-animation-options="{
                                            'name': 'fadeInDownShorter', 'duration': '1s'
                                        }">
                                            <h5 class="banner-subtitle text-uppercase text-primary font-weight-bold mb-1">
                                                From Online Store
                                            </h5>
                                            <h3 class="banner-title text-uppercase ls-25">Sprimgchic</h3>
                                            <h4 class="ls-25 mb-0">Recommend</h4>
                                            <p class="ls-25">Free shipping on all orders over <span
                                                    class="text-dark">$99.00</span></p>
                                            <a href="#"
                                                class="btn btn-dark btn-rounded btn-outline btn-icon-right">
                                                Shop Now<i class="w-icon-long-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <!-- End of Intro Slide 3 -->
                        </div>
                        <div class="custom-dots swiper-img-dots appear-animate">
                            <a href="#" class="active appear-animate">
                                <img src="/front/assets/images/demos/demo7/slides/dot-1.png" alt="Slide Thumb" width="70"
                                    height="70">
                            </a>
                            <a href="#" class="appear-animate">
                                <img src=" /front/assets/images/demos/demo7/slides/dot-2.png" alt="Slide Thumb" width="70"
                                    height="70">
                            </a>
                            <a href="#" class="appear-animate">
                                <img src=" /front/assets/images/demos/demo7/slides/dot-3.png" alt="Slide Thumb" width="70"
                                    height="70">
                            </a>
                            
                        </div>
                    </div>
                </section>

                <div class="container">
                    <div class="swiper-container swiper-theme icon-box-wrapper appear-animate br-sm bg-white mb-10"
                        data-swiper-options="{
                        'loop': true,
                        'spaceBetween': 30,
                        'slidesPerView': 1,
                        'autoplay': {
                            'delay': 4000,
                            'disableOnInteraction': false
                        },
                        'breakpoints': {
                            '576': {
                                'slidesPerView': 2
                            },
                            '768': {
                                'slidesPerView': 2
                            },
                            '992': {
                                'slidesPerView': 3
                            },
                            '1200': {
                                'slidesPerView': 4
                            }
                        }}">
                        <div class="swiper-wrapper row cols-md-4 cols-sm-3 cols-1">
                            <div class="swiper-slide icon-box icon-box-side text-dark">
                                <span class="icon-box-icon icon-shipping">
                                    <i class="w-icon-truck"></i>
                                </span>
                                <div class="icon-box-content">
                                    <h4 class="icon-box-title font-weight-bolder ls-normal">Envios a todo México</h4>
                                    <p class="text-default">Cobertura nacional</p>
                                </div>
                            </div>
                            <div class="swiper-slide icon-box icon-box-side text-dark">
                                <span class="icon-box-icon icon-payment">
                                    <i class="w-icon-bag"></i>
                                </span>
                                <div class="icon-box-content">
                                    <h4 class="icon-box-title font-weight-bolder ls-normal">Pagos seguros</h4>
                                    <p class="text-default">Aceptamos tarjeta de crédito/débito</p>
                                </div>
                            </div>
                            <div class="swiper-slide icon-box icon-box-side text-dark icon-box-money">
                                <span class="icon-box-icon icon-money">
                                    <i class="w-icon-money"></i>
                                </span>
                                <div class="icon-box-content">
                                    <h4 class="icon-box-title font-weight-bolder ls-normal">Compras seguras</h4>
                                    <p class="text-default">Nuestros vendedores son certificados</p>
                                </div>
                            </div>
                            <div class="swiper-slide icon-box icon-box-side text-dark icon-box-chat">
                                <span class="icon-box-icon icon-chat">
                                    <i class="w-icon-chat"></i>
                                </span>
                                <div class="icon-box-content">
                                    <h4 class="icon-box-title font-weight-bolder ls-normal">Asistencia técnica</h4>
                                    <p class="text-default">Estamos para apoyarte en tus dudas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Iocn Box Wrapper -->

                    <div class="category-banner-3cols swiper-container swiper-theme pt-2 mb-10 appear-animate"
                        data-swiper-options="{
                        'spaceBetween': 20,
                        'slidesPerView': 1,
                        'breakpoints': {
                            '576': {
                                'slidesPerView': 2
                            },
                            '992': {
                                'slidesPerView': 3
                            }
                        }
                    }">
                    <div class="swiper-wrapper row cols-lg-3 cols-sm-2 cols-1">
                            <div class="swiper-slide banner banner-fixed br-xs">
                                <figure>
                                    <img src="/images/banners/{{ get_settings()->site_bannero}}" alt="Category Banner" width="440"
                                        height="180" style="background-color: #E3DFDE;" />
                                </figure>
                                <div class="banner-content y-50">
                                    <h3 class="banner-title text-white text-capitalize">Insumos Agrícolas</h3>
                                    
                                  <a href="#"
                                        class="btn btn-white btn-outline btn-rounded btn-sm">
                                        Ver productos<i class="w-icon-long-arrow-right"></i>
                                    </a> 
                                </div>
                            </div>
                            <!-- End of Categpry Banner -->
                            <div class="swiper-slide banner banner-fixed br-xs">
                                <figure>
                                    <img src="/images/banners/{{ get_settings()->site_bannert}}" alt="Category Banner" width="440"
                                        height="175" style="background-color: #272729;" />
                                </figure>
                                <div class="banner-content y-50">
                               
                                    <h3 class="banner-title text-white text-capitalize">Fertilizantes</h3>
                                    <a href="#"
                                        class="btn btn-white btn-outline btn-rounded btn-sm">Ver productos<i class="w-icon-long-arrow-right"></i>
                                    </a> 
                                </div>
                            </div>
                            <!-- End of Category Banner -->
                            <div class="swiper-slide banner banner-fixed br-xs">
                                <figure>
                                    <img src="/images/banners/{{ get_settings()->site_bannerth}}" alt="Category Banner" width="440"
                                        height="210" style="background-color: #DDD8D5;" />
                                </figure>
                                <div class="banner-content y-50">
                                    <h3 class="banner-title text-white text-capitalize">Biofertilizantes</h3>
                           
                                    <a href="#"
                                    class="btn btn-white btn-outline btn-rounded btn-sm">Ver productos<i class="w-icon-long-arrow-right"></i>
                                    </a> 
                                </div>
                            </div>
                            <!-- End of Categpry Banner -->
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                    <!-- End of Category Banner 3Cols -->

                    <div class="title-link-wrapper title-deals appear-animate">
                        <h2 class="title">Ofertas del dia</h2>
                        <a href="shop-boxed-banner.html" class="font-weight-bold ls-25">Ver más productos<i
                                class="w-icon-long-arrow-right"></i></a>
                    </div>
                    <div class="row appear-animate">
                    @foreach($productosConDescuento as $producto)
                    <div class="col-lg-6 mb-4">
                        <div class="product product-list br-xs mb-0">
                            <figure class="product-media">
                                <a href="{{ route('producto.index', $producto->slug) }}">
                                    <img src="{{ asset('images/products/' . $producto->product_image) }}" alt="{{ $producto->name }}" width="315" height="355">
                                </a>
                              
                            </figure>
                            <div class="product-details">
                                <h4 class="product-name">
                                    <a href="{{ route('producto.index', $producto->slug) }}">{{ $producto->name }}</a>
                                </h4>
                                
                                <div class="product-price">
                                    @if($producto->compare_price)
                                        <del class="text-muted">${{ number_format($producto->price, 2) }}</del>
                                        <span class="text-danger ms-2">${{ number_format($producto->compare_price, 2) }}</span>
                                    @else
                                        ${{ number_format($producto->price, 2) }}
                                    @endif
                                </div>
                                <ul class="product-desc">
                                    <li>{{ $producto->category ?? 'Sin categoría' }}</li>
                                    <li>{{ $producto->subcategory ?? 'Sin subcategoría' }}</li>
                                    
                                </ul>
                                <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
                                    @csrf
                                    <div class="product-action">
                                        <button type="submit" class="btn-product" title="Añadir a carrito">
                                            <i class="w-icon-cart"></i> Añadir a carrito
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach   
                    </div>
                    <!-- End of Product List -->

                    <div class="title-link-wrapper title-deals appear-animate">
                        <h2 class="title">Productos recientes</h2>
                        <a href="shop-boxed-banner.html" class="font-weight-bold ls-25">Ver más productos<i
                                class="w-icon-long-arrow-right"></i></a>
                    </div>
                    <div class="row cols-xl-4 cols-lg-3 cols-sm-2 cols-1 mb-10 appear-animate">
                    @foreach ($productos as $producto)
                        <div class="product-widget-wrap">
                            <div class="product product-widget">
                                <figure class="product-media">
                                    <a href="">
                                    <img src="{{ asset('images/products/' . $producto->product_image) }}" 
                                    alt="Product" width="100" height="106">
                                    </a>
                                </figure>
                                <div class="product-details">
                                    <h4 class="product-name">
                                        <a href="{{ route('producto.index', $producto->slug) }}">{{ $producto->name }}</a>
                                    </h4>
                                   
                                    <div class="product-price">
                                        <ins class="new-price"> ${{ $producto->price }}</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                      
                       
                
                    </div>
                    <!-- End of Product Widget -->

                    

                    <h2 class="title title-underline pt-1 mb-4 appear-animate">Vendedores Recientes</h2>
                    <div class="swiper-container swiper-theme shadow-swiper vendor-wrapper appear-animate"
                        data-swiper-options="{
                        'spaceBetween': 20,
                        'slidesPerView': 1,
                        'breakpoints': {
                            '576': {
                                'slidesPerView': 2
                            },
                            '768': {
                                'slidesPerView': 3
                            },
                            '992': {
                                'slidesPerView': 4
                            }
                        }
                    }">
                        <div class="swiper-wrapper row cols-xl-4 cols-md-3 cols-sm-2 cols-1">
                        @foreach ($vendedores as $vendedor)
                       
                            <div class="swiper-slide vendor-widget mb-0">
                           
                                <div class="vendor-widget-banner border-no">
                                    <figure class="vendor-banner">
                                        <a href="{{ route('perfil.vendedor', $producto->seller->username) }}">
                                        <img src="{{ $vendedor->shop && $vendedor->shop->shop_banner ? asset('images/shop/' . $vendedor->shop->shop_banner) : asset('images/shop/banner.webp') }}"
                                        alt="Banner de {{ $vendedor->username }}"
                                        width="625" height="300" style="background-color: #EFF0F2;" />
                                        </a>
                                    </figure>
                                    <div class="vendor-details">
                                 
                                        <figure class="vendor-logo">
                                        
                                        <a href="{{ route('perfil.vendedor', $producto->seller->username) }}">
                                        <img src="{{ $vendedor->shop && $vendedor->shop->shop_logo ? asset('images/shop/' . $vendedor->shop->shop_logo) : asset('images/users/default-avatar.png') }}" 
                                        alt="Logo de {{ $vendedor->username }}"
                                        width="90" height="90" />
                                            </a>
                                        </figure>
                                       
                                        <div class="vendor-personal">
                                            <h4 class="vendor-name">
                                            <a href="#">{{ $vendedor->username }}</a>
                                            </h4>
                                     
                                            <a href="{{ route('perfil.vendedor', $producto->seller->username) }}" class="visit-vendor-btn text-dark">Visitar perfil</a>
                                        </div>
                            
                                    </div>
                                </div>
                           
                                <!-- End of Vendor Widget Banner -->
                            </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                    <!-- End of Vendor -->

        

                    <div class="row category-banner-2cols appear-animate">
                        <div class="col-md-6 mb-4">
                            <div class="banner banner-fixed br-xs">
                                <figure>
                                <img src="/images/banners/{{ get_settings()->site_bannerf}}" alt="Catgory Banner"
                                width="670" height="180" style="background-color: #e6e7eb;" />
                                </figure>
                                <div class="banner-content y-50">
                                <h5 class="banner-subtitle text-white text-capitalize font-weight-normal">Descuentos exclusivos</h5>
                                <h3 class="banner-title text-capitalize text-white ls-25">¡Registrate!</h3>
                                <a href="{{ url('/tienda/registrarse') }}"
                                        class="btn btn-white btn-link btn-underline btn-icon-right">
                                        Registrarse<i class="w-icon-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            <!-- End of Banner -->
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="banner banner-fixed br-xs">
                                <figure>
                                <img src="/images/banners/{{ get_settings()->site_bannerfiv}}" alt="Catgory Banner"
                                width="670" height="180" style="background-color: #28272f;" />
                                </figure>
                                <div class="banner-content y-50">
                                <h5 class="banner-subtitle text-white text-capitalize font-weight-normal">Las mejores marcas las encuentras aquí</h5>
                                    <h3 class="banner-title text-white text-capitalize ls-25">Actualización constante de productos</h3>
                                    <a href="#"
                                        class="btn btn-white btn-link btn-underline btn-icon-right">
                                        Ver Productos<i class="w-icon-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            <!-- End of Banner -->
                        </div>
                    </div>
                    <!-- End of Category Banner 2Cosl -->

                    <div class="row pb-1 banner-product-wrapper appear-animate">
                        <div class="col-xl-3 col-lg-4 col-md-5 mb-4 mb-md-0">
                        <div class="banner banner-fixed br-xs" style="background-image: url(/front/assets/images/demos/demo7/banner/2.jpg);
                                background-color: #CCCFD4;">
                                <div class="banner-content">
                                    <h3 class="banner-title text-white text-capitalize ls-25">PROTECCIÓN VEGETAL
                                    </h3>        
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-9 col-lg-8 col-md-7">
                            <div class="swiper-container swiper-theme "
                                data-swiper-options="{
                                'spaceBetween': 20,
                                'slidesPerView': 2,
                                'breakpoints': {
                                    '768': {
                                        'slidesPerView': 2
                                    },
                                    '992': {
                                        'slidesPerView': 3
                                    },
                                    '1200': {
                                        'slidesPerView': 4
                                    }
                                }
                            }">
                                <div class="swiper-wrapper row cols-xl-4 cols-lg-3 cols-md-2 cols-2">
                                @foreach ($productos as $producto)
                                <div class="swiper-slide product-col">
                                    <div class="product product-slideup-content">
                                        <figure class="product-media">
                                            <a href="{{ route('producto.index', $producto->slug) }}">
                                                <img src="{{ asset('images/products/' . $producto->product_image) }}" alt="Product"
                                                    width="239" height="269">
                                            </a>
                                            
                                        </figure>
                                        <div class="product-details">
                                            <h3 class="product-name">
                                                <a href="{{ route('producto.index', $producto->slug) }}">{{ $producto->name }}</a>
                                            </h3>
                                            <div class="ratings-container">
                                                <div class="ratings-full">
                                                    <span class="ratings" style="width: 100%;"></span>
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                            </div>
                                            <div class="product-price">
                                                <ins class="new-price">${{ $producto->price }}</ins>
                                            </div>
                                        </div>
                                        <div class="product-hidden-details">
                                        <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
                                        @csrf
                                            <div class="product-action">
                                                {{-- Agregar al carrito --}}
                                               
                                                <button type="submit" class="btn-product " title="Añadir al carrito">
                                                    <i class="w-icon-cart"></i><span>Añadir a carrito</span>
                                                </button>
                                           


                                                {{-- Wishlist (puedes programarlo más adelante) --}}
                                                <!-- <form action="" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-product-icon btn-wishlist w-icon-heart"
                                                        title="Add to wishlist"></button>
                                                </form> -->
                                            </div>
                                            </form>
                                        </div>
                                    </div>
                                    <!-- End of Product Slideup Content -->
                                </div>
                            @endforeach
                                    <!-- End of Product Col -->
                                    
                                </div>
                                <div class="swiper-pagination"></div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Banner Product Wrapper -->

                    
                    <div class="sale-banner banner br-sm mb-10 appear-animate">
                        <div class="banner-content">
                            <h4
                                class="content-left banner-subtitle text-uppercase mb-8 mb-md-0 mr-0 mr-md-4 text-agro ls-25">
                                <span class="text-agro font-weight-bold lh-1 ls-normal mr-1"></span>LOS MEJORES PRODUCTOS</h4>
                            <div class="content-right bg-agro">
                                <h3
                                    class="banner-title text-uppercase font-weight-normal mb-4 mb-md-0 ls-25 text-white">
                                    <span>Variedad de 
                                        <strong class="mr-10 pr-lg-10">Productos</strong>
                                        Las mejores 
                                        <strong class="mr-10 pr-lg-10">Marcas</strong>
                                        Encontrarás
                                        <strong class="mr-10 pr-lg-10">Compras seguras</strong>
                                    </span>
                                </h3>
                              
                            </div>
                        </div>
                    </div>
                    <!-- End of Sale Banner -->

                    <div class="row mb-4 appear-animate">
                        <div class="col-lg-3 col-sm-6 mb-6">
                            <h4 class="title title-underline mb-2 pt-1">Mejores vendidos</h4>
                            <div class="widget widget-products">
                                <div class="product product-widget">
                                    <figure class="product-media">
                                        <a href="#">
                                            <img src="/front/assets/images/demos/demo7/products/6-6.jpg" alt="Product"
                                                width="124" height="140">
                                        </a>
                                    </figure>
                                    <div class="product-details">
                                        <h4 class="product-name">
                                            <a href="#">APIS BLOOM ATRAYENTE DE ABEJAS. TIERRA VIVA.</a>
                                        </h4>
                                        <div class="ratings-container">
                                            <div class="ratings-full">
                                                <span class="ratings" style="width: 100%;"></span>
                                                <span class="tooltiptext tooltip-top"></span>
                                            </div>
                                        </div>
                                        <div class="product-price">
                                            <ins class="new-price">$23.58 - $45.62</ins>
                                        </div>
                                    </div>
                                </div>
                                <!-- End of Product Widget -->
                               
                                <div class="product product-widget">
                                    <figure class="product-media">
                                        <a href="#">
                                            <img src="/front/assets/images/demos/demo7/products/7-3.jpg" alt="Product"
                                                width="124" height="140">
                                        </a>
                                    </figure>
                                    <div class="product-details">
                                        <h4 class="product-name">
                                            <a href="#">RACUMIN POLVO X 50 GR. AMIGO DEL CAMPO.</a>
                                        </h4>
                                        <div class="ratings-container">
                                            <div class="ratings-full">
                                                <span class="ratings" style="width: 100%;"></span>
                                                <span class="tooltiptext tooltip-top"></span>
                                            </div>
                                        </div>
                                        <div class="product-price">
                                            <ins class="new-price">$69.99</ins><del class="old-price">$84.16</del>
                                        </div>
                                    </div>
                                </div>
                                <!-- End of Product Widget -->
                            </div>
                            <!-- End of Widget Products -->
                        </div>
                        <!-- End of Col -->

                        <div class="col-lg-3 col-sm-6 mb-6">
                        <h4 class="title title-underline mb-2 pt-1">Recomendaciones</h4>
                        <div class="widget widget-products">
                            @foreach($recomendaciones as $producto)
                                <div class="product product-widget mb-2">
                                    <figure class="product-media">
                                        <a href="{{ route('producto.index', $producto->slug) }}">
                                            <img src="{{ asset('images/products/' . $producto->product_image) }}"
                                                alt="{{ $producto->name }}"
                                                width="124" height="140">
                                        </a>
                                    </figure>
                                    <div class="product-details">
                                        <h4 class="product-name">
                                            <a href="{{ route('producto.index', $producto->slug) }}">
                                                {{ $producto->name }}
                                            </a>
                                        </h4>
                                        <div class="ratings-container">
                                            <div class="ratings-full">
                                                <span class="ratings" style="width: 100%;"></span>
                                                <span class="tooltiptext tooltip-top"></span>
                                            </div>
                                        </div>
                                        <div class="product-price">
                                            <ins class="new-price">${{ number_format($producto->price, 2) }}</ins>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div> </div>

                        <div class="col-lg-3 col-sm-6 mb-6">
                            <h4 class="title title-underline mb-2 pt-1">Recientes</h4>
                            @foreach ($recientes as $reciente)
                            <div class="widget widget-products">
                                <div class="product product-widget">
                                    <figure class="product-media">
                                        <a href="#">
                                            <img src="{{ asset('images/products/' . $reciente->product_image) }}" alt="Product"
                                                width="124" height="140">
                                        </a>
                                    </figure>
                                  
                                    <div class="product-details">
                                        <h4 class="product-name">
                                            <a href="{{ route('producto.index', $reciente->slug) }}">{{ $producto->name }}</a>
                                        </h4>
                                        <div class="ratings-container">
                                            <div class="ratings-full">
                                                <span class="ratings" style="width: 100%;"></span>
                                                <span class="tooltiptext tooltip-top"></span>
                                            </div>
                                        </div>
                                        <div class="product-price">
                                            <ins class="new-price">${{ $producto->compare_price }}</ins><del class="old-price">${{ $producto->price }}</del>
                                        </div>
                                    </div>
                                </div>
                                <!-- End of Product Widget -->
                            </div>
                            @endforeach
                            <!-- End of Widget Products -->
                        </div>
                        <!-- End of Col -->

                        <div class="col-lg-3 col-sm-6 mb-6">
                        <h4 class="title title-underline mb-2 pt-1">Promociones</h4>
                        <div class="widget widget-products">
                            @foreach($productosConDescuento as $producto)
                                <div class="product product-widget mb-2">
                                    <figure class="product-media">
                                        <a href="{{ route('producto.index', $producto->slug) }}">
                                            <img src="{{ asset('images/products/' . $producto->product_image) }}"
                                                alt="{{ $producto->name }}"
                                                width="124" height="140">
                                        </a>
                                    </figure>
                                    <div class="product-details">
                                        <h4 class="product-name">
                                            <a href="{{ route('producto.index', $producto->slug) }}">
                                                {{ $producto->name }}
                                            </a>
                                        </h4>
                                        <div class="ratings-container">
                                            <div class="ratings-full">
                                                <span class="ratings" style="width: 100%;"></span>
                                                <span class="tooltiptext tooltip-top"></span>
                                            </div>
                                        </div>
                                        <div class="product-price">
                                            @if($producto->compare_price)
                                                <ins class="new-price">${{ number_format($producto->compare_price, 2) }}</ins>
                                                <del class="old-price">${{ number_format($producto->price, 2) }}</del>
                                            @else
                                                <ins class="new-price">${{ number_format($producto->price, 2) }}</ins>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- End of Row -->


                    <div class="title-link-wrapper title-post mb-4 appear-animate">
                        <h2 class="title ls-normal mb-0">Noticias recientes</h2>
                        <a href="blog-listing.html" class="font-weight-bold font-size-normal mb-2">Ver todos las noticias<i
                                class="w-icon-long-arrow-right"></i></a>
                    </div>
                    <div class="swiper-container swiper-theme post-wrapper mb-8 mb-lg-4 appear-animate"
                        data-swiper-options="{
                        'slidesPerView': 1,
                        'spaceBetween': 20,
                        'breakpoints': {
                            '576': {
                                'slidesPerView': 2
                            },
                            '768': {
                                'slidesPerView': 3
                            },
                            '992': {
                                'slidesPerView': 4
                            }
                        }
                    }">
                    <div class="swiper-wrapper row cols-lg-4 cols-md-3 cols-sm-2 cols-1">
                            <div class="swiper-slide post text-center overlay-zoom">
                                <figure class="post-media br-sm">
                                    <a href="#">
                                        <img src="/front/assets/images/demos/demo7/blog/1.jpg" alt="Post" width="325" height="214"
                                            style="background-color: #b8bfc4;" />
                                    </a>
                                </figure>
                                <div class="post-details">
                                    
                                    <h4 class="post-title"><a href="#">Consejos de agricultura</a>
                                    </h4>
                                    <a href="#" class="btn btn-link btn-dark btn-underline">Ver más<i
                                            class="w-icon-long-arrow-right"></i></a>
                                </div>
                            </div>
                            <div class="swiper-slide post text-center overlay-zoom">
                                <figure class="post-media br-sm">
                                    <a href="#">
                                        <img src="/front/assets/images/demos/demo7/blog/2.jpg" alt="Post" width="325" height="214"
                                            style="background-color: #596066;" />
                                    </a>
                                </figure>
                                <div class="post-details">
                                    
                                    <h4 class="post-title"><a href="#">Agricultura sostenible: el futuro de las industrias alimentarias</a>
                                    </h4>
                                    <a href="#" class="btn btn-link btn-dark btn-underline">Ver más<i
                                            class="w-icon-long-arrow-right"></i></a>
                                </div>
                            </div>
                            <div class="swiper-slide post text-center overlay-zoom">
                                <figure class="post-media br-sm">
                                    <a href="#">
                                        <img src="/front/assets/images/demos/demo7/blog/3.jpg" alt="Post" width="325" height="214"
                                            style="background-color: #eff3f4;" />
                                    </a>
                                </figure>
                                <div class="post-details">
                                   
                                    <h4 class="post-title"><a href="#">Importancia de la pasteurización en la elaboración de jugos</a>
                                    </h4>
                                    <a href="#" class="btn btn-link btn-dark btn-underline">Ver más<i
                                            class="w-icon-long-arrow-right"></i></a>
                                </div>
                            </div>
                            <div class="swiper-slide post text-center overlay-zoom">
                                <figure class="post-media br-sm">
                                    <a href="#">
                                        <img src="/front/assets/images/demos/demo7/blog/4.jpg" alt="Post" width="325" height="214"
                                            style="background-color: #68605e;" />
                                    </a>
                                </figure>
                                <div class="post-details">
                                    
                                    <h4 class="post-title"><a href="#">Las variedades de mango en México</a>
                                    </h4>
                                    <a href="#" class="btn btn-link btn-dark btn-underline">Ver más<i
                                            class="w-icon-long-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                    <!-- End of Blog Post -->

                    <div class="title-link-wrapper title-recent mb-4 appear-animate">
                        <h2 class="title ls-normal mb-0">Recientemente vistos</h2>
                      
                    </div>
                    <div class="swiper-container swiper-theme shadow-swiper appear-animate pb-2 mb-10 appear-animate"
                        data-swiper-options="{
                        'spaceBetween': 20,
                        'slidesPerView': 2,
                        'breakpoints': {
                            '576': {
                                'slidesPerView': 3
                            },
                            '768': {
                                'slidesPerView': 5
                            },
                            '992': {
                                'slidesPerView': 6
                            },
                            '1200': {
                                'slidesPerView': 8
                            }
                        }
                    }">
                    @if($productosVistos->isNotEmpty())
                        <div class="swiper-wrapper row cols-xl-8 cols-lg-6 cols-md-4 cols-2 mt-5">
                            @foreach($productosVistos as $producto)
                            <div class="swiper-slide product-wrap mb-0">
                                <div class="product text-center product-absolute">
                                    <figure class="product-media">
                                        <a href="{{ route('producto.index', $producto->slug) }}">
                                            <img src="{{ asset('images/products/' . $producto->product_image) }}" 
                                                alt="{{ $producto->name }}" width="152" height="171" style="background-color: #fff;" />
                                        </a>
                                    </figure>
                                    <h4 class="product-name">
                                        <a href="{{ route('producto.index', $producto->slug) }}">{{ $producto->name }}</a>
                                    </h4>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                        <div class="swiper-pagination"></div>
                    </div>
                    <!-- End of Reviewed Producs -->
                </div>
                <!-- End of Container -->
            </div>
            <!-- End of Page Cotent -->
        </main>

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

    <!-- Start of Newsletter popup  -->
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


    <script>
    document.addEventListener('DOMContentLoaded', function () {
        $('.add-to-cart-ajax').on('click', function (e) {
            e.preventDefault();

            let productId = $(this).data('id');

            $.ajax({
                url: '/carrito/agregar/' + productId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.success) {
                        // Actualizar contador del carrito en el header
                        $('#cart-count').text(response.cartCount);


                        // Mostrar mensaje (puedes hacer un toast, alerta, etc)
                        session()->flash('success', '¡Producto agregado al carrito!');
                    }
                },
                error: function () {
                    alert('¡Producto agregado al carrito!');

                }
            });
        });
    });
</script>
<script>$('.decrease-quantity').on('click', function(e) {
    e.preventDefault();
    let productId = $(this).data('id');

    $.ajax({
        url: '/carrito/disminuir/' + productId,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            $('.cart-count').text(response.cartCount);
            // Aquí puedes recargar el carrito, actualizar el total, etc.
        }
    });
});

$('.remove-product').on('click', function(e) {
    e.preventDefault();
    let productId = $(this).data('id');

    $.ajax({
        url: '/carrito/eliminar/' + productId,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            $('.cart-count').text(response.cartCount);
            // Aquí puedes recargar el carrito, actualizar el total, etc.
        }
    });
});
 </script>

</body>

</html>