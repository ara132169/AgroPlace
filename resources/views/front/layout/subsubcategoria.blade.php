@extends('front.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Productos - Subsubcategoría')

@section('content')
<!-- Start of Main -->
<main class="main">
    <!-- Start of Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="page-title mb-0">{{ $subsubcategoria->subcategory_name }}</h1>
        </div>
    </div>
    <!-- End of Page Header -->

    <!-- Start of Breadcrumb -->
    <nav class="breadcrumb-nav mb-10 pb-1">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ url('/') }}">Inicio</a></li>
                <li><a href="{{ route('categoria.productos', $categoria->category_slug) }}">{{ $categoria->category_name }}</a></li>
                <li><a href="{{ route('subcategoria', ['categorySlug' => $categoria->category_slug, 'subcategorySlug' => $subcategoria->subcategory_slug]) }}">{{ $subcategoria->subcategory_name }}</a></li>
                <li>{{ $subsubcategoria->subcategory_name }}</li>
            </ul>
        </div>
    </nav>
    <!-- End of Breadcrumb -->

    <!-- Start of Page Content -->
    <div class="page-content">
        <div class="container">
            
            @if($productos->count() > 0)
                <div class="row cols-2 cols-sm-3 cols-md-4 cols-lg-5 cols-xl-6">
                    @foreach($productos as $producto)
                        <div class="product-wrap">
                            <div class="product text-center">
                                <figure class="product-media">
                                    <a href="{{ route('producto.index', $producto->slug) }}">
                                        @if($producto->product_image)
                                            <img src="{{ asset('images/products/' . $producto->product_image) }}" 
                                                 alt="{{ $producto->name }}" 
                                                 loading="lazy"
                                                 width="300" height="338">
                                        @else
                                            <img src="{{ asset('front/assets/images/products/default.jpg') }}" 
                                                 alt="{{ $producto->name }}" 
                                                 loading="lazy"
                                                 width="300" height="338">
                                        @endif
                                    </a>
                                    <div class="product-action-vertical">
                                        <a href="#" class="btn-product-icon btn-cart w-icon-cart" 
                                           title="Agregar al carrito"
                                           onclick="agregarAlCarrito({{ $producto->id }})"></a>
                                        <a href="#" class="btn-product-icon btn-wishlist w-icon-heart" 
                                           title="Agregar a favoritos"></a>
                                        <a href="#" class="btn-product-icon btn-compare w-icon-compare" 
                                           title="Comparar"></a>
                                        <a href="#" class="btn-product-icon btn-quickview w-icon-search" 
                                           title="Vista rápida"></a>
                                    </div>
                                </figure>
                                <div class="product-details">
                                    <h4 class="product-name">
                                        <a href="{{ route('producto.index', $producto->slug) }}">{{ $producto->name }}</a>
                                    </h4>
                                    <div class="ratings-container">
                                        <div class="ratings-full">
                                            <span class="ratings" style="width: 100%;"></span>
                                            <span class="tooltiptext tooltip-top"></span>
                                        </div>
                                        <a href="#" class="rating-reviews">(5 reseñas)</a>
                                    </div>
                                    <div class="product-price">
                                        <ins class="new-price">${{ number_format($producto->price, 2) }}</ins>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Paginación -->
                <div class="toolbox toolbox-pagination justify-content-between">
                    <p class="showing-info mb-2 mb-sm-0">
                        Mostrando {{ $productos->firstItem() }}-{{ $productos->lastItem() }} de {{ $productos->total() }} resultados
                    </p>
                    {{ $productos->links() }}
                </div>
                
            @else
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <h4>No hay productos disponibles</h4>
                            <p>Actualmente no hay productos en la subsubcategoría "{{ $subsubcategoria->subcategory_name }}".</p>
                            <a href="{{ route('subcategoria', ['categorySlug' => $categoria->category_slug, 'subcategorySlug' => $subcategoria->subcategory_slug]) }}" class="btn btn-primary btn-rounded">
                                Ver todos los productos de {{ $subcategoria->subcategory_name }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            
        </div>
    </div>
    <!-- End of Page Content -->
</main>
<!-- End of Main -->

<script>
function agregarAlCarrito(productId) {
    fetch(`{{ url('/carrito/agregar') }}/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar contador del carrito si existe
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.cart_count;
            }
            alert('Producto agregado al carrito');
        } else {
            alert('Error al agregar producto al carrito');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al agregar producto al carrito');
    });
}
</script>
@endsection
