<!DOCTYPE html>
<html lang="es">
<head>
    @include('front.layout.inc.head')
    
    <style>
    /* Estilos simplificados para las tiendas */
    .store-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.2s ease;
    }
    
    .store-card:hover {
        transform: translateY(-2px);
    }

    .store-banner {
        height: 200px;
        overflow: hidden;
    }

    .store-banner img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .store-content {
        padding: 1.5rem;
    }

    .store-logo {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin-bottom: 1rem;
        border: 3px solid #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .store-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .store-info {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .vendor-section {
        margin-top: 3rem;
        padding: 2rem 0;
        background: #f8f9fa;
    }

    .vendor-card {
        background: #fff;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    </style>
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
                    
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <select name="sortby" class="form-control me-3" style="max-width: 200px;">
                                    <option value="">Ordenar por</option>
                                    <option value="name">Nombre A-Z</option>
                                    <option value="date">Más recientes</option>
                                    <option value="rating">Mejor valoradas</option>
                                </select>
                                <button class="btn btn-dark">Ordenar</button>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <span class="text-muted">Viendo {{ $tiendas ? $tiendas->count() : 0 }} tiendas</span>
                        </div>
                    </div>

                    <!-- Grid de Tiendas -->
                    <div class="row">
                        @if($tiendas && $tiendas->count() > 0)
                            @foreach($tiendas as $tienda)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="store-card">
                                    <!-- Banner de la tienda -->
                                    <div class="store-banner">
                                        @if($tienda->shop_banner && file_exists(public_path('images/shop/' . $tienda->shop_banner)))
                                            <img src="{{ asset('images/shop/' . $tienda->shop_banner) }}" 
                                                 alt="{{ $tienda->shop_name }}">
                                        @else
                                            <img src="{{ asset('images/default-store-banner.jpg') }}" 
                                                 alt="{{ $tienda->shop_name }}">
                                        @endif
                                    </div>
                                    
                                    <!-- Contenido de la tienda -->
                                    <div class="store-content">
                                        <!-- Logo de la tienda -->
                                        @if($tienda->shop_logo && file_exists(public_path('images/shop/' . $tienda->shop_logo)))
                                            <img src="{{ asset('images/shop/' . $tienda->shop_logo) }}" 
                                                 alt="{{ $tienda->shop_name }}" class="store-logo">
                                        @else
                                            <img src="{{ asset('images/default-shop-logo.jpg') }}" 
                                                 alt="{{ $tienda->shop_name }}" class="store-logo">
                                        @endif
                                        
                                        <!-- Información de la tienda -->
                                        <h4 class="store-title">
                                            <a href="{{ route('tienda.detalle', $tienda->id) }}" class="text-decoration-none">
                                                {{ $tienda->shop_name }}
                                            </a>
                                        </h4>
                                        
                                        @if($tienda->shop_address)
                                        <div class="store-info">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $tienda->shop_address }}
                                        </div>
                                        @endif
                                        
                                        @if($tienda->seller)
                                        <div class="store-info">
                                            <i class="fas fa-user me-1"></i>
                                            {{ $tienda->seller->name }}
                                        </div>
                                        @endif
                                        
                                        @if($tienda->shop_phone)
                                        <div class="store-info">
                                            <i class="fas fa-phone me-1"></i>
                                            {{ $tienda->shop_phone }}
                                        </div>
                                        @endif
                                        
                                        <div class="store-info">
                                            <i class="fas fa-clock me-1"></i>
                                            Miembro desde {{ $tienda->created_at->format('M Y') }}
                                        </div>
                                        
                                        <div class="store-info">
                                            <i class="fas fa-shopping-cart me-1"></i>
                                            {{ $tienda->products_count ?? 0 }} productos
                                        </div>
                                        
                                        <div class="mt-3">
                                            <a href="{{ route('tienda.detalle', $tienda->id) }}" class="btn btn-primary btn-sm">
                                                Ver Tienda
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="fas fa-store-slash fa-4x text-muted mb-3"></i>
                                    <h4>No hay tiendas disponibles</h4>
                                    <p class="text-muted">Aún no se han registrado tiendas en la plataforma.</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Paginación -->
                    @if($tiendas && $tiendas->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $tiendas->links() }}
                        </div>
                    @endif

                    <!-- Sección de Vendedores Recientes -->
                    @if(isset($vendedores) && $vendedores->count() > 0)
                    <div class="vendor-section">
                        <div class="container">
                            <h3 class="text-center mb-4">Vendedores Recientes</h3>
                            <div class="row">
                                @foreach ($vendedores as $vendedor)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                    <div class="vendor-card">
                                        @if($vendedor->shop && $vendedor->shop->shop_logo)
                                            <img src="{{ asset('images/shop/' . $vendedor->shop->shop_logo) }}" 
                                                 alt="{{ $vendedor->name }}" 
                                                 class="rounded-circle mb-2" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @endif
                                        <h6 class="mb-1">{{ $vendedor->name }}</h6>
                                        <p class="text-muted small mb-2">@{{ $vendedor->username }}</p>
                                        <a href="{{ route('perfil.vendedor', $vendedor->username) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            Ver Perfil
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
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

    <!-- Plugin JS File - Solo lo esencial -->
    <script src="/front/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/front/assets/js/main.min.js"></script>

</body>
</html>