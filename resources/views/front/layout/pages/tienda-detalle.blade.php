@extends('front.layout.pages-layout')
@section('pageTitle', $tienda->shop_name . ' - Tienda')

@push('stylesheets')
<style>
.shop-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 0;
}
.product-card {
    transition: transform 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.product-card:hover {
    transform: translateY(-5px);
}
.lazy-img {
    opacity: 0;
    transition: opacity 0.3s;
}
.lazy-img.loaded {
    opacity: 1;
}
</style>
@endpush

@section('content')
<!-- Header de la tienda -->
<div class="shop-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                @if($tienda->shop_logo)
                    <img src="{{ asset('images/shops/' . $tienda->shop_logo) }}" 
                         class="img-fluid rounded-circle border border-white"
                         alt="{{ $tienda->shop_name }}"
                         style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                         style="width: 150px; height: 150px; margin: 0 auto;">
                        <i class="fas fa-store fa-3x text-primary"></i>
                    </div>
                @endif
            </div>
            <div class="col-md-9">
                <h1 class="mb-3">{{ $tienda->shop_name }}</h1>
                <p class="lead mb-2">{{ $tienda->shop_description ?? 'Tienda oficial en AgroMarket' }}</p>
                @if($tienda->shop_address)
                    <p><i class="fas fa-map-marker-alt me-2"></i>{{ $tienda->shop_address }}</p>
                @endif
                @if($tienda->shop_phone)
                    <p><i class="fas fa-phone me-2"></i>{{ $tienda->shop_phone }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Productos de la tienda -->
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Productos disponibles</h3>
        <a href="{{ route('tiendas.index') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left me-1"></i>Volver a tiendas
        </a>
    </div>
    
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
                        
                        @if($producto->compare_price && $producto->compare_price > $producto->selling_price)
                            <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                                -{{ round((($producto->compare_price - $producto->selling_price) / $producto->compare_price) * 100) }}%
                            </span>
                        @endif
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ Str::limit($producto->name, 50) }}</h6>
                        <p class="card-text text-muted small flex-grow-1">
                            {{ Str::limit($producto->short_description, 80) }}
                        </p>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    @if($producto->compare_price && $producto->compare_price > $producto->selling_price)
                                        <small class="text-decoration-line-through text-muted">
                                            ${{ number_format($producto->compare_price, 2) }}
                                        </small>
                                    @endif
                                    <div class="fw-bold text-success">
                                        ${{ number_format($producto->selling_price, 2) }}
                                    </div>
                                </div>
                            </div>
                            
                            <a href="{{ route('producto.index', $producto->product_slug) }}" 
                               class="btn btn-primary btn-sm w-100">
                                Ver producto
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No hay productos disponibles</h4>
                <p class="text-muted">Esta tienda aún no ha publicado productos.</p>
            </div>
        @endforelse
    </div>
    
    <!-- Paginación -->
    @if($productos->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $productos->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
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
});
</script>
@endpush
