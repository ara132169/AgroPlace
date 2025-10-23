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
        <main class="main mb-10 pb-1">
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav container">
                <ul class="breadcrumb bb-no">
                    <li><a href="demo1.html">Inicio</a></li>
                    <li><a href="product-default.html">Productos</a></li>
                    <li>{{ $producto->name }}</li>
                </ul>
               
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of Page Content -->
            <div class="page-content">
                <div class="container">
                    <div class="row gutter-lg">
                        <div class="main-content">
                            <div class="product product-single row">
                                <div class="col-md-6 mb-6">
                                    <div class="product-gallery product-gallery-sticky product-gallery-vertical">
                                        <div class="swiper-container product-single-swiper swiper-theme nav-inner"
                                            data-swiper-options="{
                                            'navigation': {
                                                'nextEl': '.swiper-button-next',
                                                'prevEl': '.swiper-button-prev'
                                            }
                                        }">
                                        <div class="swiper-wrapper row cols-1 gutter-no">
                                            {{-- Imagen principal --}}
                                            <div class="swiper-slide">
                                                <figure class="product-image">
                                                    <img src="{{ asset('images/products/' . $producto->product_image) }}"
                                                        data-zoom-image="{{ asset('images/products/' . $producto->product_image) }}"
                                                        alt="{{ $producto->name }}" width="800" height="900">
                                                </figure>
                                            </div>

                                            {{-- Imágenes adicionales (máx. 3 para que con la principal sean 4) --}}
                                           

                                           
                                                <div class="swiper-slide">
                                                @foreach($producto->images->take(4) as $img)
                                                    <figure class="product-image">
                                                        <img src="{{ asset('images/products/' . $img->image) }}"
                                                            data-zoom-image="{{ asset('images/products/' . $img->image) }}"
                                                            alt="{{ $producto->name }}" width="800" height="900">
                                                @endforeach
                                                    </figure>
                                                </div>
                                           
                                            
                                            {{-- Fin de imágenes adicionales --}}
                                        </div>

                                            <button class="swiper-button-next"></button>
                                            <button class="swiper-button-prev"></button>
                                            <a href="#" class="product-gallery-btn product-image-full"><i
                                                    class="w-icon-zoom"></i></a>
                                        </div>
                                        <div class="product-thumbs-wrap swiper-container" data-swiper-options="{
                                            'navigation': {
                                                'nextEl': '.swiper-button-next',
                                                'prevEl': '.swiper-button-prev'
                                            },
                                            'breakpoints': {
                                                '992': {
                                                    'direction': 'vertical',
                                                    'slidesPerView': 'auto'
                                                }
                                            }
                                        }">
                                        <div class="product-thumbs swiper-wrapper row cols-lg-1 cols-4 gutter-sm">
                                            <div class="swiper-slide">
                                            @foreach ($producto->images->take(4) as $img)
                                        
                                                <figure class="product-image">
                                                <img src="{{ asset('images/products/' . $img->image) }}" 
                                                        data-zoom-image="{{ asset('images/products/' . $img->image) }}"
                                                        alt="{{ $producto->name }}" width="800" height="900">
                                                </figure>
                                                @endforeach
                                            </div>
                                   
                                        </div>
                                            <button class="swiper-button-prev"></button>
                                            <button class="swiper-button-next"></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 sticky-sidebar-wrapper mb-4 mb-md-6">
                                    <div class="product-details sticky-sidebar" data-sticky-options="{'minWidth': 767}">
                                        <h1 class="product-title">{{ $producto->name }}</h1>
                                        <div class="product-bm-wrapper">
                                            
                                            <div class="product-meta">
                                                <div class="product-categories">
                                                    Categoría:
                                                    <span class="product-category"><a href="#">{{ $producto->category->category_name ?? 'Sin categoría' }}</a></span>
                                                </div>
                                                <div class="product-sku">
                                                    Vendido por: <span>{{ $producto->seller->username }}</span>
                                                </div>
                                            </div>

                                        </div>

                                        <hr class="product-divider">

                                        
                                        <div class="product-price">
                                        @if($producto->discount_price)
                                            <del class="text-muted">${{ number_format($producto->price, 2) }}</del>
                                            <span class="text-danger ms-2"> ${{ number_format($producto->compare_price, 2) }} </span>
                                        @else
                                            ${{ number_format($producto->price, 2) }}
                                        @endif
                                    </div>
                                        
                                        <div class="product-short-desc lh-2">
                                        {{ Str::words(strip_tags($producto->summary), 40, '...') }}

                                        </div>

                                        <hr class="product-divider">

                                        <div class="fix-bottom product-sticky-content sticky-content">
                                        
                                            <!-- empieza -->
                                            <form action="{{ route('carrito.agregar', $producto->id) }}" method="POST">
                                                @csrf
                                                <div class="product-form container">
                                                <input type="number" name="quantity" value="1" min="1" class="form-control mb-2" style="width: 100px;">
                                                    <input type="hidden" name="redirect_to_checkout" value="1">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="w-icon-cart"></i>
                                                        <span>Añadir al carrito</span>
                                                    </button>
                                                </div>
                                            </form>

                                            <!-- End of .product-form -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab tab-nav-boxed tab-nav-underline product-tabs mt-3">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a href="#product-tab-description" class="nav-link active">Descripción</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#product-tab-vendor" class="nav-link">Perfil del vendedor</a>
                                    </li>
                                    
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="product-tab-description">
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-5">
                                                <h4 class="title tab-pane-title font-weight-bold mb-2">Detalles</h4>
                                                <p class="mb-4">{!! $producto->summary !!}</p>
                                            </div>                         
                                        </div>
                                        
                                    </div>
                                    @if($producto->seller && $producto->seller->shop)
                                    <div class="tab-pane" id="product-tab-vendor">
                                        <div class="row mb-3">
                                            <div class="col-md-6 mb-4">
                                                <figure class="vendor-banner br-sm">
                                                    <img src="{{ asset('images/shop/' . $producto->seller->shop->shop_logo) }}"
                                                        alt="Vendor Banner" width="610" height="295"
                                                        style="background-color: #353B55;" />
                                                </figure>
                                            </div>
                                            <div class="col-md-6 pl-2 pl-md-6 mb-4">
                                                <div class="vendor-user">
                                                    <figure class="vendor-logo mr-4">
                                                        <a href="#">
                                                            <img src="{{ asset('images/shop/' . $producto->seller->shop->shop_logo) }}"
                                                                alt="Logo" width="80" height="80" />
                                                        </a>
                                                    </figure>
                                                    <div>
                                                        <div class="vendor-name"><a href="#">{{ $producto->seller->shop->shop_name }}</a></div>
                                                        <div class="ratings-container">
                                                            <div class="ratings-full">
                                                                <span class="ratings" style="width: 90%;"></span>
                                                                <span class="tooltiptext tooltip-top"></span>
                                                            </div>
                                                          
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="vendor-info list-style-none">
                                                    <li class="store-name">
                                                        <label>Nombre de la tienda:</label>
                                                        <span class="detail">{{ $producto->seller->shop->shop_name }}</span>
                                                    </li>
                                                    <li class="store-address">
                                                        <label>Ubicación:</label>
                                                        <span class="detail">{{ $producto->seller->shop->shop_address }}</span>
                                                    </li>
                                                    <li class="store-phone">
                                                        <label>Teléfono:</label>
                                                        <a href="tel:{{ $producto->seller->shop->shop_phone }}">{{ $producto->seller->shop->shop_phone }}</a>
                                                    </li>
                                                </ul>
                                                <a href="{{ route('perfil.vendedor', $producto->seller->username) }}"
                                                    class="btn btn-dark btn-link btn-underline btn-icon-right">Visitar Perfil<i class="w-icon-long-arrow-right"></i></a>
                                            </div>
                                        </div>
                                        <p class="mb-5"><strong class="text-dark"></strong>{{ $producto->seller->shop->shop_description }}
                                        </p>
                                        
                                    </div>
                                    @endif
                                     <!-- fin div vendedor -->
                                   
                                </div>
                            </div>
                            <section class="vendor-product-section">
                                <div class="title-link-wrapper mb-4">
                                    <h4 class="title text-left">Más productos de este vendedor</h4>
                                    
                                </div>
                                <div class="swiper-container swiper-theme" data-swiper-options="{
                                    'spaceBetween': 20,
                                    'slidesPerView': 2,
                                    'breakpoints': {
                                        '576': {
                                            'slidesPerView': 3
                                        },
                                        '768': {
                                            'slidesPerView': 4
                                        },
                                        '992': {
                                            'slidesPerView': 3
                                        }
                                    }
                                }">
                                    <div class="swiper-wrapper row cols-lg-3 cols-md-4 cols-sm-3 cols-2">
                                    @forelse($productosRelacionados as $relacionado)
                                        <div class="swiper-slide product">
                                            <figure class="product-media">
                                                <a href="product-default.html">
                                                    <img src="{{ asset('images/products/' . $relacionado->product_image) }}" alt="Product"
                                                        width="300" height="338" />
                                                    <img src="{{ asset('images/products/' . $relacionado->product_image) }}" alt="Product"
                                                        width="300" height="338" />
                                                </a>
                                                <div class="product-action-vertical">
                                                    <a href="#" class="btn-product-icon btn-cart w-icon-cart"
                                                        title="Add to cart"></a>
                                                </div>
                                               
                                            </figure>
                                            <div class="product-details">
                                                <div class="product-cat"><a
                                                        href="shop-banner-sidebar.html">{{ $relacionado->category->category_name ?? 'Sin categoría' }}</a>
                                                </div>
                                                <h4 class="product-name"><a href="{{ route('producto.index', $relacionado->slug) }}">{{ \Illuminate\Support\Str::limit($relacionado->name, 30) }}</a>
                                                </h4>
                                                <div class="ratings-container">
                                                    <div class="ratings-full">
                                                        <span class="ratings" style="width: 100%;"></span>
                                                        <span class="tooltiptext tooltip-top"></span>
                                                    </div>
                                                    
                                                </div>
                                                <div class="product-pa-wrapper">
                                                    <div class="product-price">${{ number_format($relacionado->price, 2) }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        @empty
                                            <p>Este vendedor no tiene más productos.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </section>
                           
                        </div>
                        <!-- End of Main Content -->
                        <aside class="sidebar product-sidebar sidebar-fixed right-sidebar sticky-sidebar-wrapper">
                            <div class="sidebar-overlay"></div>
                            <a class="sidebar-close" href="#"><i class="close-icon"></i></a>
                            <a href="#" class="sidebar-toggle d-flex d-lg-none"><i class="fas fa-chevron-left"></i></a>
                            <div class="sidebar-content scrollable">
                                <div class="sticky-sidebar">
                                    <div class="widget widget-icon-box mb-6">
                                        <div class="icon-box icon-box-side">
                                            <span class="icon-box-icon text-dark">
                                                <i class="w-icon-truck"></i>
                                            </span>
                                            <div class="icon-box-content">
                                                <h4 class="icon-box-title">Envíos a todo México</h4>
                                                <p>Cobertura nacional</p>
                                            </div>
                                        </div>
                                        <div class="icon-box icon-box-side">
                                            <span class="icon-box-icon text-dark">
                                                <i class="w-icon-bag"></i>
                                            </span>
                                            <div class="icon-box-content">
                                                <h4 class="icon-box-title">Pagos seguros</h4>
                                                <p>Aceptamos tarjetas de crédito/débito</p>
                                            </div>
                                        </div>
                                        <div class="icon-box icon-box-side">
                                            <span class="icon-box-icon text-dark">
                                                <i class="w-icon-money"></i>
                                            </span>
                                            <div class="icon-box-content">
                                                <h4 class="icon-box-title">Compras seguras</h4>
                                                <p>Nuestros vendedores son certificados</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of Widget Icon Box -->
                                </div>
                            </div>
                        </aside>
                        <!-- End of Sidebar -->
                    </div>
                </div>
            </div>
            <!-- End of Page Content -->
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