<!DOCTYPE html>
<html lang="es">
<head>
    @include('front.layout.inc.head')
    
    @push('stylesheets')
    <style>
    /* WCMP Store Styles - Improved Design */
    .store-wcmp {
        background: #fff;
        border-radius: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        position: relative;
        border: 1px solid #e8f5e8;
    }
    
    .store-wcmp:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(34, 85, 48, 0.15);
        border-color: #2c5530;
    }

    .store-banner {
        position: relative;
        margin: 0;
        overflow: hidden;
        height: 220px;
        background: linear-gradient(135deg, #2c5530 0%, #4a7c59 100%);
    }

    .store-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .store-wcmp:hover .store-banner img {
        transform: scale(1.08);
    }

    .store-content {
        padding: 2rem 1.5rem 1.5rem 1.5rem;
        position: relative;
    }

    .seller-brand {
        position: absolute;
        top: -45px;
        left: 50%;
        transform: translateX(-50%);
        width: 90px;
        height: 90px;
        margin: 0;
        border-radius: 50%;
        overflow: hidden;
        background: #fff;
        border: 5px solid #fff;
        box-shadow: 0 8px 25px rgba(34, 85, 48, 0.2);
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .seller-brand img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .seller-date {
        margin-top: 50px;
        text-align: center;
    }

    .store-title {
        margin: 0 0 1.5rem 0;
        font-size: 1.4rem;
        font-weight: 700;
        line-height: 1.3;
        color: #2c5530;
    }

    .store-title a {
        color: #2c5530;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .store-title a:hover {
        color: #1a3920;
        text-decoration: underline;
    }

    .seller-info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .seller-info-list li {
        margin-bottom: 1rem;
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.5rem;
        background: #f8fdf8;
        border-radius: 0.5rem;
        border-left: 3px solid #2c5530;
    }

    .store-address i,
    .store-vendor i,
    .store-phone i,
    .store-member-since i,
    .store-products i {
        color: #2c5530;
        margin-top: 0.1rem;
        font-size: 1rem;
        flex-shrink: 0;
        width: 20px;
        text-align: center;
    }

    .store-address p,
    .store-vendor p,
    .store-phone p,
    .store-member-since p,
    .store-products p {
        margin: 0;
        color: #555;
        font-size: 0.9rem;
        line-height: 1.5;
        font-weight: 500;
    }

    .store-rating {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 0.5rem;
        padding: 0.5rem;
    }

    .ratings-container {
        display: flex;
        align-items: center;
    }

    .ratings-full {
        position: relative;
        display: inline-block;
        font-size: 0;
    }

    .ratings-full::before {
        content: "★★★★★";
        color: #ddd;
        font-size: 14px;
        letter-spacing: 2px;
    }

    .ratings {
        position: absolute;
        top: 0;
        left: 0;
        color: #ffb301;
        overflow: hidden;
        white-space: nowrap;
    }

    .ratings::before {
        content: "★★★★★";
        font-size: 14px;
        letter-spacing: 2px;
    }

    /* Vendor Filter Styles */
    .vendor-filter {
        background: linear-gradient(135deg, #f8fdf8 0%, #e8f5e8 100%);
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        border: 1px solid #d4edda;
        box-shadow: 0 4px 15px rgba(34, 85, 48, 0.08);
    }

    .vendor-filter-left {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .vendor-filter-right {
        color: #2c5530;
        font-weight: 600;
        font-size: 1rem;
    }

    .select-box {
        position: relative;
        min-width: 150px;
    }

    .select-box select {
        width: 100%;
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        border-radius: 0.375rem;
        background: #fff;
        font-size: 0.875rem;
    }

    .btn-rounded {
        border-radius: 2rem;
        padding: 0.75rem 2rem;
        font-size: 0.9rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-dark {
        background: linear-gradient(135deg, #2c5530 0%, #4a7c59 100%);
        color: #fff;
        border: 2px solid transparent;
    }

    .btn-dark:hover {
        background: linear-gradient(135deg, #1a3920 0%, #2c5530 100%);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(34, 85, 48, 0.3);
    }

    /* Store Grid */
    .cols-lg-3 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    @media (min-width: 992px) {
        .cols-lg-3 {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 767px) {
        .cols-lg-3 {
            grid-template-columns: 1fr;
        }
    }

    .store-wrap {
        margin-bottom: 0;
    }

    /* Breadcrumb */
    .breadcrumb-nav {
        background: linear-gradient(135deg, #f8fdf8 0%, #e8f5e8 100%);
        padding: 1.5rem 0;
        margin-bottom: 0;
        border-bottom: 1px solid #d4edda;
    }

    .breadcrumb {
        margin: 0;
        padding: 0;
        list-style: none;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .breadcrumb li {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #2c5530;
    }

    .breadcrumb li:not(:last-child)::after {
        content: '▶';
        color: #4a7c59;
        font-size: 0.8rem;
    }

    .breadcrumb a {
        color: #2c5530;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb a:hover {
        color: #1a3920;
        text-decoration: underline;
    }

    .bb-no {
        border-bottom: none;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #666;
        grid-column: 1 / -1;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: #ddd;
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 3rem;
        text-align: center;
    }

    .store-vendor,
    .store-phone,
    .store-member-since,
    .store-products {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .store-vendor i,
    .store-phone i,
    .store-member-since i,
    .store-products i {
        color: #999;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        flex-shrink: 0;
    }

    .store-vendor p,
    .store-phone p,
    .store-member-since p,
    .store-products p {
        margin: 0;
        color: #666;
        font-size: 0.875rem;
        line-height: 1.4;
    }

    /* Icon replacements with FontAwesome */
    .w-icon-map-marker::before {
        content: "\f3c5";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        font-style: normal;
    }

    .w-icon-user::before {
        content: "\f007";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        font-style: normal;
    }

    .w-icon-phone::before {
        content: "\f095";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        font-style: normal;
    }

    .w-icon-clock::before {
        content: "\f017";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        font-style: normal;
    }

    .w-icon-cart::before {
        content: "\f07a";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        font-style: normal;
    }

    /* Utility Classes */
    .br-sm {
        border-radius: 0.5rem;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .mb-8 {
        margin-bottom: 4rem;
    }

    .list-style-none {
        list-style: none;
    }

    .mr-auto {
        margin-right: auto;
    }

    .mr-2 {
        margin-right: 0.5rem;
    }

    .mr-4 {
        margin-right: 1rem;
    }

    .d-inline-block {
        display: inline-block;
    }

    .font-size-md {
        font-size: 0.9rem;
    }

    .text-dark {
        color: #333;
    }
    </style>
    @endpush
</head>

<body class="home">
    <div class="page-wrapper">
        @include('front.layout.inc.headerdos')

        <main class="main">
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb bb-no">
                        <li><a href="{{ url('/') }}">Inicio</a></li>
                        <li><a href="#">Vendedor</a></li>
                        <li><a href="#">WC Marketplace</a></li>
                        <li>Lista de Tiendas</li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of Page Content -->
            <div class="page-content mb-8">
                <div class="container">
                    
                    <!-- Start of Vendor Filter -->
                    <div class="vendor-filter mb-4">
                        <div class="vendor-filter-left mr-auto">
                            <div class="select-box mb-2 mr-2">
                                <select name="sortby" class="form-control">
                                    <option value="">Ordenar por</option>
                                    <option value="name">Nombre A-Z</option>
                                    <option value="date">Más recientes</option>
                                    <option value="rating">Mejor valoradas</option>
                                </select>
                            </div>
                            <a href="#" class="btn btn-dark btn-rounded mb-2 mr-4">Ordenar</a>
                        </div>
                        <div class="vendor-filter-right">
                            <label class="d-inline-block font-size-md text-dark mb-2">
                                Viendo {{ $tiendas ? $tiendas->count() : 0 }} tiendas
                            </label>
                        </div>
                    </div>
                    <!-- End of Vendor Filter -->

                    <!-- Start of Vendor Store -->
                    <div class="row cols-lg-3">
                        @if($tiendas && $tiendas->count() > 0)
                            @foreach($tiendas as $tienda)
                            <div class="store-wrap mb-4">
                                <div class="store store-wcmp br-sm">
                                    <figure class="store-banner">
                                        @if($tienda->shop_banner && file_exists(public_path('images/shop/' . $tienda->shop_banner)))
                                            <img src="{{ asset('images/shop/' . $tienda->shop_banner) }}" 
                                                 alt="{{ $tienda->shop_name }}" 
                                                 width="400" height="318"
                                                 style="background-color: #454b63;"
                                                 loading="lazy">
                                        @else
                                            <img src="{{ asset('images/default-store-banner.jpg') }}" 
                                                 alt="{{ $tienda->shop_name }}" 
                                                 width="400" height="318"
                                                 style="background-color: #454b63;"
                                                 loading="lazy">
                                        @endif
                                    </figure>
                                    
                                    <div class="store-content">
                                        <figure class="seller-brand">
                                            @if($tienda->shop_logo && file_exists(public_path('images/shop/' . $tienda->shop_logo)))
                                                <img src="{{ asset('images/shop/' . $tienda->shop_logo) }}" 
                                                     alt="{{ $tienda->shop_name }}"
                                                     width="80" height="80"
                                                     loading="lazy">
                                            @else
                                                <img src="{{ asset('images/default-shop-logo.jpg') }}" 
                                                     alt="{{ $tienda->shop_name }}"
                                                     width="80" height="80"
                                                     loading="lazy">
                                            @endif
                                        </figure>
                                        
                                        <div class="seller-date">
                                            <h4 class="store-title">
                                                <a href="{{ route('tienda.detalle', $tienda->id) }}">
                                                    {{ $tienda->shop_name }}
                                                </a>
                                            </h4>
                                            
                                            <ul class="seller-info-list list-style-none">
                                                @if($tienda->shop_address)
                                                <li class="store-address">
                                                    <i class="w-icon-map-marker"></i>
                                                    <p>{{ $tienda->shop_address }}</p>
                                                </li>
                                                @endif
                                                
                                                <li class="store-rating">
                                                    <div class="ratings-container">
                                                        <div class="ratings-full">
                                                            <span class="ratings" style="width: 80%;"></span>
                                                            <span class="tooltiptext tooltip-top"></span>
                                                        </div>
                                                    </div>
                                                </li>
                                                
                                                @if($tienda->seller)
                                                <li class="store-vendor">
                                                    <i class="w-icon-user"></i>
                                                    <p>{{ $tienda->seller->name }}</p>
                                                </li>
                                                @endif
                                                
                                                @if($tienda->shop_phone)
                                                <li class="store-phone">
                                                    <i class="w-icon-phone"></i>
                                                    <p>{{ $tienda->shop_phone }}</p>
                                                </li>
                                                @endif
                                                
                                                <li class="store-member-since">
                                                    <i class="w-icon-clock"></i>
                                                    <p>Miembro desde {{ $tienda->created_at->format('M Y') }}</p>
                                                </li>
                                                
                                                <li class="store-products">
                                                    <i class="w-icon-cart"></i>
                                                    <p>{{ $tienda->products_count ?? 0 }} productos</p>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <!-- Empty State -->
                            <div class="empty-state">
                                <i class="fas fa-store-slash"></i>
                                <h3>No hay tiendas disponibles</h3>
                                <p>Aún no se han registrado tiendas en la plataforma.</p>
                                <a href="{{ route('seller.register') }}" class="btn btn-primary btn-rounded">
                                    <i class="fas fa-plus me-1"></i>Registrar tu Tienda
                                </a>
                            </div>
                        @endif
                    </div>
                    <!-- End of Vendor Store -->

                    <!-- Pagination -->
                    @if($tiendas && $tiendas->hasPages())
                        <div class="pagination-wrapper">
                            {{ $tiendas->links() }}
                        </div>
                    @endif

                </div>
            </div>
            <!-- End of Page Content -->
        </main>

        @include('front.layout.inc.footer')
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

    <!-- JavaScript simplificado -->
    <script>
    // JavaScript básico sin conflictos
    </script>

    <!-- Plugin JS File -->
    <script src="/front/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/front/assets/js/main.min.js"></script>

</body>
</html>
