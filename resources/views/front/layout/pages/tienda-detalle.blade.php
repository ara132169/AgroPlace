<!DOCTYPE html>
<html lang="es">
<head>
    @include('front.layout.inc.head')
    <title>{{ $tienda->shop_name }} - Detalle de Tienda - AgroMarket</title>
    
    <style>
    .shop-header {
        position: relative;
        background: linear-gradient(135deg, #2c5530 0%, #4a7c59 100%);
        color: white;
        padding: 4rem 0 6rem 0;
        overflow: hidden;
        min-height: 400px;
        display: flex;
        align-items: center;
    }
    
    .shop-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('{{ $tienda->shop_banner ? asset("images/shop/" . $tienda->shop_banner) : asset("images/default-store-banner.jpg") }}') center/cover;
        opacity: 0.3;
        z-index: 1;
    }
    
    .shop-header::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(44, 85, 48, 0.8) 0%, rgba(74, 124, 89, 0.8) 100%);
        z-index: 2;
    }
    
    .shop-header .container {
        position: relative;
        z-index: 3;
    }
    
    .shop-logo {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: 6px solid #fff;
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        object-fit: cover;
        background: #fff;
        padding: 5px;
        margin: 0 auto;
        display: block;
    }
    
    .shop-logo-placeholder {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: 6px solid #fff;
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .shop-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }
    
    .shop-description {
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        opacity: 0.95;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }
    
    .shop-info {
        font-size: 1rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .shop-info i {
        background: rgba(255,255,255,0.2);
        padding: 0.5rem;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .products-section {
        margin-top: -3rem;
        position: relative;
        z-index: 4;
    }
    
    .products-header {
        background: #fff;
        padding: 2rem;
        border-radius: 1rem 1rem 0 0;
        box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
        margin-bottom: 0;
    }
    
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid #e8f5e8;
    }
    
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(34, 85, 48, 0.15);
        border-color: #2c5530;
    }
    
    .lazy-img {
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .lazy-img.loaded {
        opacity: 1;
    }
    
    .btn-back {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 2rem;
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    .btn-back:hover {
        background: rgba(255,255,255,0.3);
        border-color: rgba(255,255,255,0.5);
        color: white;
        transform: translateY(-2px);
    }
    
    @media (max-width: 768px) {
        .shop-header {
            padding: 3rem 0 5rem 0;
            text-align: center;
        }
        
        .shop-title {
            font-size: 2rem;
        }
        
        .shop-logo, .shop-logo-placeholder {
            width: 140px;
            height: 140px;
        }
    }
    </style>
</head>
<body>
    @include('front.layout.inc.headerdos')
<!-- Header de la tienda -->
<div class="shop-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 text-center mb-4 mb-md-0">
                @if($tienda->shop_logo && file_exists(public_path('images/shop/' . $tienda->shop_logo)))
                    <img src="{{ asset('images/shop/' . $tienda->shop_logo) }}" 
                         class="shop-logo"
                         alt="{{ $tienda->shop_name }}">
                @else
                    <div class="shop-logo-placeholder">
                        <i class="fas fa-store fa-4x" style="color: #2c5530;"></i>
                    </div>
                @endif
            </div>
            <div class="col-lg-9 col-md-8">
                <div class="row align-items-center">
                    <div class="col">
                        <h1 class="shop-title">{{ $tienda->shop_name }}</h1>
                        <p class="shop-description">
                            {{ $tienda->shop_description ?? 'Tienda oficial especializada en productos agrícolas de calidad' }}
                        </p>
                        
                        @if($tienda->shop_address)
                            <div class="shop-info">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $tienda->shop_address }}</span>
                            </div>
                        @endif
                        
                        @if($tienda->shop_phone)
                            <div class="shop-info">
                                <i class="fas fa-phone"></i>
                                <span>{{ $tienda->shop_phone }}</span>
                            </div>
                        @endif
                        
                        @if($tienda->seller)
                            <div class="shop-info">
                                <i class="fas fa-user"></i>
                                <span>{{ $tienda->seller->name }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="col-auto">
                        <a href="{{ url()->previous() }}" class="btn-back">
                            <i class="fas fa-arrow-left me-2"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Productos de la tienda -->
<div class="products-section">
    <div class="container">
        <div class="products-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0" style="color: #2c5530; font-weight: 700;">
                        <i class="fas fa-box-open me-2" style="color: #4a7c59;"></i>
                        Productos disponibles
                    </h3>
                    <p class="text-muted mb-0 mt-1">Descubre todos los productos de esta tienda</p>
                </div>
                <div class="col-auto">
                    <span class="badge bg-success bg-gradient px-3 py-2" style="font-size: 0.9rem;">
                        {{ $productos->total() }} producto{{ $productos->total() != 1 ? 's' : '' }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="bg-white px-4 pb-5">
            <div class="row" id="productos-grid">
        @forelse($productos as $producto)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card product-card h-100">
                    <div class="position-relative">
                        <img data-src="{{ asset('images/products/' . $producto->product_image) }}" 
                             class="card-img-top lazy-img" 
                             alt="{{ $producto->name }}"
                             style="height: 200px; object-fit: cover;"
                             loading="lazy">
                        
                        @if($producto->compare_price && $producto->compare_price > $producto->price)
                            <span class="badge position-absolute top-0 end-0 m-2" 
                                  style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); font-size: 0.8rem;">
                                -{{ round((($producto->compare_price - $producto->price) / $producto->compare_price) * 100) }}%
                            </span>
                        @endif
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title fw-bold" style="color: #2c5530;">{{ Str::limit($producto->name, 50) }}</h6>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit($producto->summary, 80) }}
                        </p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    @if($producto->compare_price && $producto->compare_price > $producto->price)
                                        <small class="text-decoration-line-through text-muted d-block">
                                            ${{ number_format($producto->compare_price, 2) }}
                                        </small>
                                    @endif
                                    <div class="fw-bold fs-5" style="color: #2c5530;">
                                        ${{ number_format($producto->price, 2) }}
                                    </div>
                                </div>
                            </div>
                            
                            <a href="{{ route('producto.index', $producto->slug) }}" 
                               class="btn w-100"
                               style="background: linear-gradient(135deg, #2c5530 0%, #4a7c59 100%); 
                                      color: white; border: none; border-radius: 0.75rem; 
                                      padding: 0.75rem; font-weight: 600; 
                                      transition: all 0.3s ease;">
                                <i class="fas fa-eye me-1"></i>Ver producto
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="py-5">
                    <i class="fas fa-box-open fa-4x mb-4" style="color: #4a7c59; opacity: 0.6;"></i>
                    <h4 style="color: #2c5530;">No hay productos disponibles</h4>
                    <p class="text-muted">Esta tienda aún no ha publicado productos.</p>
                    <a href="{{ url()->previous() }}" class="btn btn-outline-secondary mt-3">
                        <i class="fas fa-arrow-left me-1"></i>Volver atrás
                    </a>
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Paginación -->
    @if($productos->hasPages())
        <div class="d-flex justify-content-center mt-4 pb-4">
            {{ $productos->links() }}
        </div>
    @endif
        </div>
    </div>
</div>
    
    @include('front.layout.inc.footer')

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lazy loading para imágenes
        const images = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
        
        // Efectos hover para botones
        const buttons = document.querySelectorAll('.btn');
        buttons.forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                if (this.style.background.includes('linear-gradient')) {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 8px 25px rgba(34, 85, 48, 0.3)';
                }
            });
            
            btn.addEventListener('mouseleave', function() {
                if (this.style.background.includes('linear-gradient')) {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'none';
                }
            });
        });
    });
    </script>

    <!-- Plugin JS File - Solo esenciales -->
    <script src="/front/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/front/assets/js/main.min.js"></script>

</body>
</html>
