<!DOCTYPE html>
<html lang="es">
<head>
    @include('front.layout.inc.head')
    
    @push('stylesheets')
    <style>
    .shop-card {
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }
    .shop-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .shop-logo {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem 0;
        margin-bottom: 0;
    }
    .skeleton-loader {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }
    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    </style>
    @endpush
</head>

<body class="home">
    <div class="page-wrapper">
        @include('front.layout.inc.header')

        <main class="main">
            <!-- Header de la página -->
            <div class="page-header">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-4 mb-3">Tiendas Registradas</h1>
                            <p class="lead">Descubre todas las tiendas disponibles en nuestra plataforma</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <i class="fas fa-store fa-5x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="container py-5">
                <!-- Filtros y búsqueda -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Buscar tiendas..." id="searchShops">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            {{ $tiendas->total() ?? 0 }} tiendas encontradas
                        </p>
                    </div>
                </div>

                <!-- Grid de tiendas -->
                <div class="row" id="shops-container">
                    @if($tiendas && $tiendas->count() > 0)
                        @foreach($tiendas as $tienda)
                        <div class="col-lg-4 col-md-6 mb-4 shop-item">
                            <div class="card shop-card h-100">
                                <div class="card-body text-center">
                                    <!-- Logo de la tienda -->
                                    <div class="mb-3">
                                        @if($tienda->shop_logo && file_exists(public_path('images/shops/' . $tienda->shop_logo)))
                                            <img data-src="{{ asset('images/shops/' . $tienda->shop_logo) }}" 
                                                 class="shop-logo lazy-img mx-auto d-block" 
                                                 alt="{{ $tienda->shop_name }}"
                                                 loading="lazy">
                                        @else
                                            <div class="shop-logo mx-auto d-flex align-items-center justify-content-center bg-light">
                                                <i class="fas fa-store fa-2x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Información de la tienda -->
                                    <h5 class="card-title mb-2">{{ $tienda->shop_name }}</h5>
                                    
                                    @if($tienda->shop_description)
                                        <p class="card-text text-muted mb-3">
                                            {{ Str::limit($tienda->shop_description, 100) }}
                                        </p>
                                    @endif

                                    <!-- Información del vendedor -->
                                    @if($tienda->seller)
                                        <small class="text-success d-block mb-2">
                                            <i class="fas fa-user me-1"></i>{{ $tienda->seller->name }}
                                        </small>
                                    @endif

                                    <!-- Información adicional -->
                                    <div class="text-start mb-3">
                                        @if($tienda->shop_address)
                                            <small class="text-muted d-block">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ Str::limit($tienda->shop_address, 50) }}
                                            </small>
                                        @endif
                                        
                                        @if($tienda->shop_phone)
                                            <small class="text-muted d-block">
                                                <i class="fas fa-phone me-1"></i>{{ $tienda->shop_phone }}
                                            </small>
                                        @endif
                                    </div>
                                </div>

                                <!-- Footer de la tarjeta -->
                                <div class="card-footer bg-transparent border-0 text-center">
                                    <a href="{{ route('tienda.detalle', $tienda->id) }}" 
                                       class="btn btn-primary btn-sm px-4">
                                        <i class="fas fa-eye me-1"></i>Ver Tienda
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <!-- Estado vacío -->
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="fas fa-store fa-5x text-muted mb-4"></i>
                                <h3 class="text-muted">No hay tiendas registradas</h3>
                                <p class="text-muted">Aún no hay tiendas disponibles en la plataforma.</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Paginación -->
                @if($tiendas && $tiendas->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $tiendas->links() }}
                    </div>
                @endif
            </div>
        </main>

        @include('front.layout.inc.footer')
    </div>

</body>
</html>
