@extends('front.layout.app')

@section('pageTitle')
{{ $subcategoria->subcategory_name ?? 'Productos' }}
@endsection

@section('content')
<!-- Start of Page Header -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title mb-0">{{ $subcategoria->subcategory_name }}</h1>
    </div>
</div>
<!-- End of Page Header -->

    <!-- Start of Breadcrumb -->
    <nav class="breadcrumb-nav mb-10 pb-1">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ url('/') }}">Inicio</a></li>
                <li><a href="{{ route('categoria.productos', $categoria->category_slug) }}">{{ $categoria->category_name }}</a></li>
                <li>{{ $subcategoria->subcategory_name }}</li>
            </ul>
        </div>
    </nav>
    <!-- End of Breadcrumb -->

    <!-- Start of Page Content -->
    <div class="page-content">
        <div class="container">
            
            @if($productos->count() > 0)
                <div class="shop-content row gutter-lg mb-10">
                    <!-- Start of Sidebar, Shop Sidebar -->
                    <aside class="sidebar shop-sidebar sticky-sidebar-wrapper sidebar-fixed">
                        <!-- Start of Sidebar Overlay -->
                        <div class="sidebar-overlay"></div>
                        <a class="sidebar-close" href="#"><i class="close-icon"></i></a>

                        <!-- Start of Sidebar Content -->
                        <div class="sidebar-content scrollable">
                            <!-- Start of Sticky Sidebar -->
                            <div class="sticky-sidebar">
                                <div class="filter-actions">
                                    <label>Filtrar:</label>
                                    <a href="#" class="btn btn-dark btn-link filter-clean">Limpiar todo</a>
                                </div>
                                
                                <!-- Start of Collapsible widget -->
                                <div class="widget widget-collapsible">
                                    <h3 class="widget-title"><span>Categorías relacionadas</span></h3>
                                    <ul class="widget-body filter-items search-ul">
                                        <li>
                                            <a href="{{ route('categoria.productos', $categoria->category_slug) }}">
                                                {{ $categoria->category_name }} (Ver todas)
                                            </a>
                                        </li>
                                        @if(isset($categoria->subcategories) && $categoria->subcategories->count() > 0)
                                            @foreach($categoria->subcategories as $subcat)
                                                @if($subcat->is_child_of == 0)
                                                    <li class="{{ $subcat->id == $subcategoria->id ? 'active' : '' }}">
                                                        <a href="{{ route('subcategoria', ['categorySlug' => $categoria->category_slug, 'subcategorySlug' => $subcat->subcategory_slug]) }}">
                                                            {{ $subcat->subcategory_name }}
                                                        </a>
                                                        @if($subcat->children && $subcat->children->count() > 0)
                                                            <ul class="ml-4">
                                                                @foreach($subcat->children as $child)
                                                                    <li>
                                                                        <a href="{{ route('subsubcategoria', ['categorySlug' => $categoria->category_slug, 'subcategorySlug' => $subcat->subcategory_slug, 'subsubcategorySlug' => $child->subcategory_slug]) }}">
                                                                            {{ $child->subcategory_name }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                                <!-- End of Collapsible Widget -->
                            </div>
                            <!-- End of Sidebar Content -->
                        </div>
                        <!-- End of Sidebar Content -->
                    </aside>
                    <!-- End of Shop Sidebar -->

                    <!-- Start of Shop Main Content -->
                    <div class="main-content">
                        <nav class="toolbox sticky-toolbox sticky-content fix-top">
                            <div class="toolbox-left">
                                <a href="#" class="btn btn-primary btn-outline btn-rounded left-sidebar-toggle 
                                    btn-icon-left d-block d-lg-none">
                                    <i class="w-icon-category"></i><span>Filtros</span>
                                </a>
                                <div class="toolbox-item toolbox-sort select-box text-dark">
                                    <label>Ordenar por:</label>
                                    <select name="orderby" class="form-control">
                                        <option value="default">Por defecto</option>
                                        <option value="popularity">Más popular</option>
                                        <option value="rating">Mejor valorado</option>
                                        <option value="date">Más reciente</option>
                                        <option value="price-low">Precio: menor a mayor</option>
                                        <option value="price-high">Precio: mayor a menor</option>
                                    </select>
                                </div>
                            </div>
                            <div class="toolbox-right">
                                <div class="toolbox-item toolbox-show select-box">
                                    <select name="count" class="form-control">
                                        <option value="12">Mostrar 12</option>
                                        <option value="24">Mostrar 24</option>
                                        <option value="36">Mostrar 36</option>
                                    </select>
                                </div>
                                <div class="toolbox-item toolbox-layout">
                                    <a href="#" class="icon-mode-grid btn-layout active">
                                        <i class="w-icon-grid"></i>
                                    </a>
                                    <a href="#" class="icon-mode-list btn-layout">
                                        <i class="w-icon-list"></i>
                                    </a>
                                </div>
                            </div>
                        </nav>

                        <div class="product-wrapper row cols-md-3 cols-sm-2 cols-2">
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
                                            @if($producto->compare_price)
                                                <div class="product-label-group">
                                                    <label class="product-label label-discount">
                                                        {{ round((($producto->price - $producto->compare_price) / $producto->price) * 100) }}% OFF
                                                    </label>
                                                </div>
                                            @endif
                                            <div class="product-action-vertical">
                                                <a href="#" class="btn-product-icon btn-cart w-icon-cart" 
                                                   title="Agregar al carrito"
                                                   onclick="event.preventDefault(); agregarAlCarrito({{ $producto->id }})"></a>
                                                <a href="#" class="btn-product-icon btn-wishlist w-icon-heart" 
                                                   title="Agregar a favoritos"></a>
                                                <a href="#" class="btn-product-icon btn-quickview w-icon-search" 
                                                   title="Vista rápida"></a>
                                            </div>
                                        </figure>
                                        <div class="product-details">
                                            <div class="product-cat">
                                                <a href="{{ route('categoria.productos', $categoria->category_slug) }}">
                                                    {{ $categoria->category_name }}
                                                </a>
                                            </div>
                                            <h3 class="product-name">
                                                <a href="{{ route('producto.index', $producto->slug) }}">{{ $producto->name }}</a>
                                            </h3>
                                            <div class="ratings-container">
                                                <div class="ratings-full">
                                                    <span class="ratings" style="width: 100%;"></span>
                                                    <span class="tooltiptext tooltip-top"></span>
                                                </div>
                                                <a href="#" class="rating-reviews">(5 reseñas)</a>
                                            </div>
                                            <div class="product-pa-wrapper">
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
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        <div class="toolbox toolbox-pagination justify-content-between">
                            <p class="showing-info mb-2 mb-sm-0">
                                Mostrando <span>{{ $productos->firstItem() }}-{{ $productos->lastItem() }}</span> de <span>{{ $productos->total() }}</span> productos
                            </p>
                            {{ $productos->links() }}
                        </div>
                    </div>
                    <!-- End of Shop Main Content -->
                </div>
            @else
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-info text-center" style="padding: 3rem;">
                            <i class="w-icon-exclamation-triangle" style="font-size: 3rem; color: #666; margin-bottom: 1rem;"></i>
                            <h4>No hay productos disponibles</h4>
                            <p>Actualmente no hay productos en la subcategoría "{{ $subcategoria->subcategory_name }}".</p>
                            <a href="{{ route('categoria.productos', $categoria->category_slug) }}" class="btn btn-primary btn-rounded mt-3">
                                <i class="w-icon-angle-left"></i> Ver todos los productos de {{ $categoria->category_name }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
          
    </div>
    <!-- End of Page Content -->
            @include('front.layout.inc.footer')


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
            // Mostrar notificación
            if (typeof toastr !== 'undefined') {
                toastr.success('Producto agregado al carrito');
            } else {
                alert('Producto agregado al carrito');
            }
        } else {
            if (typeof toastr !== 'undefined') {
                toastr.error(data.message || 'Error al agregar producto al carrito');
            } else {
                alert('Error al agregar producto al carrito');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof toastr !== 'undefined') {
            toastr.error('Error al agregar producto al carrito');
        } else {
            alert('Error al agregar producto al carrito');
        }
    });
}
</script>
@endsection
