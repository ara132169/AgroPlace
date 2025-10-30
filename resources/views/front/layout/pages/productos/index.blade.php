@extends('front.layout.app')
@section('pageTitle', 'Productos')
@section('bodyClass', 'shop-fullwidth-banner')
@section('content')

    <!-- Mensajes Flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin: 20px;">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            // Auto-hide después de 3 segundos
            setTimeout(function() {
                var alert = document.querySelector('.alert-success');
                if (alert) {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.remove();
                    }, 300);
                }
            }, 3000);
        </script>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="margin: 20px;">
            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="body-content outer-top-bd">
        <div class="container">
            <div class="row">
                <!-- SIDEBAR -->
                <div class="col-md-3 sidebar">
                    <div class="sidebar-module-container">
                        <!-- FILTROS -->
                        <div class="sidebar-widget">
                            <h3 class="section-title">Filtrar Productos</h3>
                            <div class="sidebar-widget-body">
                                <form method="GET" action="{{ route('productos.index') }}" id="filter-form">
                                    <!-- Filtro por búsqueda -->
                                    <div class="form-group">
                                        <label>Buscar:</label>
                                        <input type="text" 
                                               name="search" 
                                               class="form-control" 
                                               placeholder="Buscar productos..."
                                               value="{{ request('search') }}">
                                    </div>

                                    <!-- Filtro por categoría -->
                                    <div class="form-group">
                                        <label>Categoría:</label>
                                        <select name="categoria" class="form-control" id="categoria-select">
                                            <option value="">Todas las categorías</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id }}" 
                                                        {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                                    {{ $categoria->category_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Filtro por subcategoría -->
                                    <div class="form-group">
                                        <label>Subcategoría:</label>
                                        <select name="subcategoria" class="form-control">
                                            <option value="">Todas las subcategorías</option>
                                            @foreach($subcategorias as $subcategoria)
                                                <option value="{{ $subcategoria->id }}" 
                                                        {{ request('subcategoria') == $subcategoria->id ? 'selected' : '' }}>
                                                    {{ $subcategoria->subcategory_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Filtro por vendedor -->
                                    <div class="form-group">
                                        <label>Vendedor:</label>
                                        <select name="vendedor" class="form-control">
                                            <option value="">Todos los vendedores</option>
                                            @foreach($vendedores as $vendedor)
                                                <option value="{{ $vendedor->id }}" 
                                                        {{ request('vendedor') == $vendedor->id ? 'selected' : '' }}>
                                                    {{ $vendedor->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Filtro por precio -->
                                    <div class="form-group">
                                        <label>Rango de Precio:</label>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="number" 
                                                       name="precio_min" 
                                                       class="form-control" 
                                                       placeholder="Mín ${{ $precioMin }}"
                                                       value="{{ request('precio_min') }}"
                                                       min="{{ $precioMin }}"
                                                       max="{{ $precioMax }}">
                                            </div>
                                            <div class="col-md-6">
                                                <input type="number" 
                                                       name="precio_max" 
                                                       class="form-control" 
                                                       placeholder="Máx ${{ $precioMax }}"
                                                       value="{{ request('precio_max') }}"
                                                       min="{{ $precioMin }}"
                                                       max="{{ $precioMax }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Filtrar</button>
                                        <a href="{{ route('productos.index') }}" class="btn btn-secondary">Limpiar</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- WIDGET DE CATEGORÍAS -->
                        <div class="sidebar-widget">
                            <h3 class="section-title">Categorías</h3>
                            <div class="sidebar-widget-body">
                                <div class="accordion">
                                    @foreach($categorias as $categoria)
                                        <div class="accordion-group">
                                            <div class="accordion-heading">
                                                <a class="accordion-toggle" 
                                                   href="{{ route('productos.index', ['categoria' => $categoria->id]) }}">
                                                    {{ $categoria->category_name }}
                                                    <span class="counts">({{ $categoria->products_count ?? 0 }})</span>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CONTENIDO PRINCIPAL -->
                <div class="col-md-9">
                    <!-- BANNER MODERNO -->
                    <div class="products-banner-container">
                        <div class="products-hero-banner">
                            <div class="banner-content">
                                <div class="banner-text">
                                    <h1 class="banner-title">Descubre Nuestros Productos</h1>
                                    <p class="banner-subtitle">Encuentra los mejores insumos agrícolas para tu negocio</p>
                                    <div class="banner-stats">
                                        <div class="stat-item">
                                            <span class="stat-number">{{ $productos->total() }}</span>
                                            <span class="stat-label">Productos</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-number">{{ $categorias->count() }}</span>
                                            <span class="stat-label">Categorías</span>
                                        </div>
                                        <div class="stat-item">
                                            <span class="stat-number">{{ $vendedores->count() }}</span>
                                            <span class="stat-label">Vendedores</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="banner-illustration">
                                    <i class="fa fa-seedling"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BARRA DE HERRAMIENTAS MODERNA -->
                    <div class="products-toolbar-container">
                        <div class="products-toolbar">
                            <div class="toolbar-left">
                                <div class="view-toggle">
                                    <span class="view-label">Vista:</span>
                                    <button class="view-btn active" data-view="grid" title="Vista en cuadrícula">
                                        <i class="fa fa-th"></i>
                                    </button>
                                    <button class="view-btn" data-view="list" title="Vista en lista">
                                        <i class="fa fa-list"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="toolbar-center">
                                <div class="results-info">
                                    <span class="results-text">
                                        Mostrando <strong>{{ $productos->firstItem() ?? 0 }} - {{ $productos->lastItem() ?? 0 }}</strong> 
                                        de <strong>{{ $productos->total() }}</strong> productos
                                    </span>
                                </div>
                            </div>

                            <div class="toolbar-right">
                                <div class="sort-container">
                                    <label class="sort-label">Ordenar por:</label>
                                    <select class="sort-select" onchange="changeSort(this.value)">
                                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>
                                            Más recientes
                                        </option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                                            Nombre A-Z
                                        </option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                                            Nombre Z-A
                                        </option>
                                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                                            Precio: Menor a Mayor
                                        </option>
                                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                                            Precio: Mayor a Menor
                                        </option>
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                                            Más nuevos
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                            <!-- PRODUCTOS GRID -->
                            <div class="search-result">
                                <div class="row product-grid-holder">
                                    @forelse($productos as $producto)
                                        <div class="col-sm-6 col-md-4 wow fadeInUp" data-wow-delay="0.1s">
                                            <div class="product-card modern-card">
                                                <div class="product-image-container">
                                                    <div class="product-image">
                                                        <div class="image-wrapper">
                                                            <a href="{{ route('producto.index', $producto->slug) }}">
                                                                @if($producto->product_image)
                                                                    <img src="{{ asset('images/products/' . $producto->product_image) }}" 
                                                                         alt="{{ $producto->name }}" class="product-img">
                                                                @elseif($producto->images->isNotEmpty())
                                                                    <img src="{{ asset('images/products/' . $producto->images->first()->image) }}" 
                                                                         alt="{{ $producto->name }}" class="product-img">
                                                                @else
                                                                    <img src="{{ asset('front/assets/images/products/default/1-1.jpg') }}" 
                                                                         alt="{{ $producto->name }}" class="product-img">
                                                                @endif
                                                            </a>
                                                            
                                                            <!-- Overlay con botones de acción -->
                                                            <div class="product-overlay">
                                                                <div class="action-buttons">
                                                                    <button class="btn-action btn-cart" 
                                                                            onclick="addToCart({{ $producto->id }})"
                                                                            title="Agregar al carrito">
                                                                        <i class="fa fa-shopping-cart"></i>
                                                                    </button>
                                                                    <a href="{{ route('producto.index', $producto->slug) }}" 
                                                                       class="btn-action btn-view" 
                                                                       title="Ver producto">
                                                                        <i class="fa fa-eye"></i>
                                                                    </a>
                                                                    <button class="btn-action btn-wishlist" 
                                                                            title="Lista de deseos">
                                                                        <i class="fa fa-heart"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Badge de descuento -->
                                                        @if($producto->compare_price && $producto->compare_price > $producto->price)
                                                            <div class="product-badge sale-badge">
                                                                -{{ round((($producto->compare_price - $producto->price) / $producto->compare_price) * 100) }}%
                                                            </div>
                                                        @endif

                                                        <!-- Badge nuevo (si el producto tiene menos de 30 días) -->
                                                        @if($producto->created_at->diffInDays(now()) <= 30)
                                                            <div class="product-badge new-badge">
                                                                NUEVO
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="product-content">
                                                        <!-- Categoría -->
                                                        <div class="product-category">
                                                            @if(isset($producto->category) && is_object($producto->category) && isset($producto->category->category_name))
                                                                <span class="category-tag">{{ $producto->category->category_name }}</span>
                                                            @endif
                                                        </div>

                                                        <!-- Título del producto -->
                                                        <h3 class="product-title">
                                                            <a href="{{ route('producto.index', $producto->slug) }}">
                                                                {{ Str::limit($producto->name, 50) }}
                                                            </a>
                                                        </h3>

                                                        <!-- Rating -->
                                                        <div class="product-rating">
                                                            <div class="stars">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    <i class="fa fa-star {{ $i <= 4 ? 'filled' : '' }}"></i>
                                                                @endfor
                                                                <span class="rating-text">(4.0)</span>
                                                            </div>
                                                        </div>

                                                        <!-- Descripción -->
                                                        <div class="product-description">
                                                            {{ Str::limit($producto->summary, 80) }}
                                                        </div>

                                                        <!-- Información del vendedor -->
                                                        <div class="seller-info">
                                                            <div class="seller-badge">
                                                                <i class="fa fa-store"></i>
                                                                <a href="{{ route('perfil.vendedor', $producto->seller->username) }}">
                                                                    {{ Str::limit($producto->seller->name, 20) }}
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <!-- Precio -->
                                                        <div class="product-price-section">
                                                            <div class="price-container">
                                                                <span class="current-price">${{ number_format($producto->price, 2) }}</span>
                                                                @if($producto->compare_price && $producto->compare_price > $producto->price)
                                                                    <span class="old-price">
                                                                        ${{ number_format($producto->compare_price, 2) }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            
                                                            <!-- Botón de compra rápida -->
                                                            <button class="btn-quick-buy" onclick="addToCart({{ $producto->id }})">
                                                                <i class="fa fa-plus"></i> Agregar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-md-12">
                                            <div class="no-products-found text-center">
                                                <h3>No se encontraron productos</h3>
                                                <p>Intenta ajustar tus filtros de búsqueda</p>
                                                <a href="{{ route('productos.index') }}" class="btn btn-primary">
                                                    Ver todos los productos
                                                </a>
                                            </div>
                                        </div>
                                    @endforelse
                                </div>

                                <!-- PAGINACIÓN -->
                                @if($productos->hasPages())
                                    <div class="clearfix filters-container">
                                        <div class="text-right">
                                            <div class="pagination-container">
                                                {{ $productos->links() }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ESTILOS MEJORADOS -->
    <style>
        /* ===== VARIABLES DE COLOR ===== */
        :root {
            --primary-color: #1254a1;        /* Azul principal del tema */
            --primary-dark: #0e4082;         /* Azul más oscuro */
            --secondary-color: #2c3e50;      /* Gris azulado */
            --accent-color: #e74c3c;         /* Rojo para ofertas */
            --warning-color: #f39c12;        /* Amarillo para rating */
            --success-color: #27ae60;        /* Verde para éxito */
            --light-gray: #f8f9fa;
            --medium-gray: #dee2e6;
            --dark-gray: #6c757d;
            --white: #ffffff;
            --shadow-light: 0 2px 10px rgba(18, 84, 161, 0.1);
            --shadow-medium: 0 4px 20px rgba(18, 84, 161, 0.15);
            --shadow-dark: 0 8px 30px rgba(18, 84, 161, 0.2);
            --border-radius: 8px;             /* Menos redondeado, más acorde al tema */
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== BANNER MODERNO ===== */
        .products-banner-container {
            margin-bottom: 30px;
        }

        .products-hero-banner {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border-radius: var(--border-radius);
            padding: 40px;
            color: var(--white);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-medium);
        }

        .products-hero-banner::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(30px, -30px);
        }

        .products-hero-banner::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            transform: translate(-50px, 50px);
        }

        .banner-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .banner-text {
            flex: 1;
        }

        .banner-title {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .banner-subtitle {
            font-size: 16px;
            margin-bottom: 25px;
            opacity: 0.9;
        }

        .banner-stats {
            display: flex;
            gap: 30px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            display: block;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .banner-illustration {
            font-size: 80px;
            opacity: 0.3;
            margin-left: 30px;
        }

        /* ===== TOOLBAR MODERNA ===== */
        .products-toolbar-container {
            margin-bottom: 30px;
        }

        .products-toolbar {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 20px 25px;
            box-shadow: var(--shadow-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .toolbar-left, .toolbar-center, .toolbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .view-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .view-label {
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 14px;
        }

        .view-btn {
            width: 40px;
            height: 40px;
            border: 2px solid var(--medium-gray);
            background: var(--white);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            color: var(--dark-gray);
        }

        .view-btn.active,
        .view-btn:hover {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: var(--white);
        }

        .results-info {
            padding: 10px 20px;
            background: var(--light-gray);
            border-radius: 8px;
        }

        .results-text {
            font-size: 14px;
            color: var(--secondary-color);
        }

        .sort-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sort-label {
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 14px;
            margin: 0;
        }

        .sort-select {
            border: 2px solid var(--medium-gray);
            border-radius: 8px;
            padding: 10px 15px;
            background: var(--white);
            color: var(--secondary-color);
            font-size: 14px;
            min-width: 200px;
            transition: var(--transition);
        }

        .sort-select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        }
                }

        /* ===== CONTENEDOR PRINCIPAL ===== */
        .no-products-found {
            padding: 80px 20px;
            background: linear-gradient(135deg, var(--light-gray) 0%, #e9ecef 100%);
            border-radius: var(--border-radius);
            margin: 40px 0;
            text-align: center;
            box-shadow: var(--shadow-light);
        }

        .no-products-found h3 {
            color: var(--secondary-color);
            font-weight: 600;
            margin-bottom: 15px;
        }

        /* ===== TARJETA DE PRODUCTO MODERNA ===== */
        .product-card.modern-card {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            transition: var(--transition);
            overflow: hidden;
            margin-bottom: 30px;
            position: relative;
        }

        .product-card.modern-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-medium);
        }

        /* ===== CONTENEDOR DE IMAGEN ===== */
        .product-image-container {
            position: relative;
            overflow: hidden;
        }

        .image-wrapper {
            position: relative;
            overflow: hidden;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        .product-img {
            width: 100%;
            height: 280px;
            object-fit: cover;
            transition: var(--transition);
            display: block;
        }

        .product-card:hover .product-img {
            transform: scale(1.05);
        }

        /* ===== OVERLAY DE ACCIONES ===== */
        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(18, 84, 161, 0.9), rgba(14, 64, 130, 0.9));
            opacity: 0;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product-card:hover .product-overlay {
            opacity: 1;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            transform: translateY(20px);
            transition: var(--transition);
        }

        .product-card:hover .action-buttons {
            transform: translateY(0);
        }

        .btn-action {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: none;
            background: var(--white);
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
            font-size: 18px;
            box-shadow: var(--shadow-light);
        }

        .btn-action:hover {
            background: var(--primary-color);
            color: var(--white);
            transform: scale(1.1);
            text-decoration: none;
        }

        /* ===== BADGES ===== */
        .product-badge {
            position: absolute;
            top: 15px;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            z-index: 2;
        }

        .sale-badge {
            right: 15px;
            background: linear-gradient(135deg, var(--accent-color), #c0392b);
            color: var(--white);
            box-shadow: var(--shadow-light);
        }

        .new-badge {
            left: 15px;
            background: linear-gradient(135deg, var(--warning-color), #d68910);
            color: var(--white);
            box-shadow: var(--shadow-light);
        }

        /* ===== CONTENIDO DEL PRODUCTO ===== */
        .product-content {
            padding: 25px;
        }

        .product-category {
            margin-bottom: 10px;
        }

        .category-tag {
            display: inline-block;
            padding: 4px 12px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            border-radius: 15px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .product-title {
            margin: 15px 0 10px;
            font-size: 18px;
            font-weight: 600;
            line-height: 1.3;
            height: 50px;
            display: flex;
            align-items: center;
        }

        .product-title a {
            color: var(--secondary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .product-title a:hover {
            color: var(--primary-color);
            text-decoration: none;
        }

        /* ===== RATING ===== */
        .product-rating {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .stars {
            display: flex;
            align-items: center;
            gap: 2px;
        }

        .stars i {
            font-size: 14px;
            color: var(--medium-gray);
        }

        .stars i.filled {
            color: var(--warning-color);
        }

        .rating-text {
            font-size: 12px;
            color: var(--dark-gray);
            margin-left: 5px;
        }

        /* ===== DESCRIPCIÓN ===== */
        .product-description {
            color: var(--dark-gray);
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 20px;
            height: 42px;
            overflow: hidden;
        }

        /* ===== INFORMACIÓN DEL VENDEDOR ===== */
        .seller-info {
            margin-bottom: 20px;
            padding-top: 15px;
            border-top: 1px solid var(--medium-gray);
        }

        .seller-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--dark-gray);
            font-size: 13px;
        }

        .seller-badge i {
            color: var(--primary-color);
        }

        .seller-badge a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .seller-badge a:hover {
            color: var(--primary-color);
            text-decoration: none;
        }

        /* ===== SECCIÓN DE PRECIO ===== */
        .product-price-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .price-container {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .current-price {
            font-size: 22px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .old-price {
            font-size: 14px;
            color: var(--dark-gray);
            text-decoration: line-through;
        }

        /* ===== BOTÓN DE COMPRA RÁPIDA ===== */
        .btn-quick-buy {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            border: none;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
            box-shadow: var(--shadow-light);
        }

        .btn-quick-buy:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        .btn-quick-buy i {
            font-size: 12px;
        }

        /* ===== SIDEBAR ===== */
        .sidebar-widget {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            margin-bottom: 25px;
            overflow: hidden;
        }

        .sidebar-widget h3 {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: var(--white);
            padding: 20px;
            margin: 0;
            font-size: 16px;
            font-weight: 600;
        }

        .sidebar-widget-body {
            padding: 25px;
        }

        .sidebar-widget .form-group {
            margin-bottom: 20px;
        }

        .sidebar-widget label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            color: var(--secondary-color);
            font-size: 14px;
        }

        .sidebar-widget .form-control {
            border: 2px solid var(--medium-gray);
            border-radius: 8px;
            padding: 12px 15px;
            transition: var(--transition);
            font-size: 14px;
        }

        .sidebar-widget .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
            outline: none;
        }

        .counts {
            float: right;
            color: var(--primary-color);
            font-size: 12px;
            font-weight: 500;
        }

        /* ===== TOOLBAR ===== */
        .toolbar {
            background: var(--white);
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: var(--shadow-light);
        }

        /* ===== BOTONES ===== */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: var(--medium-gray);
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            transition: var(--transition);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .product-price-section {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn-quick-buy {
                width: 100%;
                justify-content: center;
            }
            
            .action-buttons {
                gap: 10px;
            }
            
            .btn-action {
                width: 45px;
                height: 45px;
                font-size: 16px;
            }

            /* Banner responsivo */
            .banner-content {
                flex-direction: column;
                text-align: center;
                gap: 20px;
            }

            .banner-title {
                font-size: 24px;
            }

            .banner-stats {
                justify-content: center;
                gap: 20px;
            }

            .banner-illustration {
                font-size: 60px;
                margin: 0;
            }

            /* Toolbar responsivo */
            .products-toolbar {
                flex-direction: column;
                gap: 15px;
            }

            .toolbar-left, .toolbar-center, .toolbar-right {
                width: 100%;
                justify-content: center;
            }

            .sort-select {
                min-width: auto;
                width: 100%;
            }
        }

        /* ===== ANIMACIONES ===== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .wow.fadeInUp {
            animation: fadeInUp 0.6s ease-out;
        }

        /* ===== CORRECCIÓN PARA FOOTER ===== */
        .main {
            min-height: auto;
            padding-bottom: 0;
        }

        .body-content {
            margin-bottom: 30px;
        }

        /* Asegurar que el footer sea visible */
        .footer {
            clear: both;
            margin-top: 50px;
            position: relative;
            z-index: 1;
        }

        /* Evitar que elementos flotantes afecten el footer */
        .container:after {
            content: "";
            display: table;
            clear: both;
        }

        /* ===== ANIMACIÓN DEL CARRITO ===== */
        .cart-bounce {
            animation: cartBounce 0.6s ease-in-out;
        }

        @keyframes cartBounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-8px);
            }
            60% {
                transform: translateY(-4px);
            }
        }

        /* ===== ESTADOS DE BOTONES ===== */
        .btn-quick-buy:disabled,
        .btn-cart:disabled,
        .btn-action:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .btn-quick-buy:disabled:hover,
        .btn-cart:disabled:hover,
        .btn-action:disabled:hover {
            transform: none;
            box-shadow: var(--shadow-light);
        }

        /* Efecto de éxito al agregar al carrito */
        .btn-success-flash {
            background: linear-gradient(135deg, var(--success-color), #229954) !important;
            transform: scale(1.05);
            box-shadow: 0 4px 20px rgba(39, 174, 96, 0.4) !important;
        }

        .btn-action.btn-success-flash {
            background: var(--success-color) !important;
            color: var(--white) !important;
        }
    </style>

    <!-- SCRIPTS -->
    <script>
        function changeSort(sortValue) {
            const url = new URL(window.location);
            url.searchParams.set('sort', sortValue);
            window.location = url;
        }

        function addToCart(productId) {
            console.log('addToCart called with productId:', productId);
            
            // Verificar si el usuario está autenticado
            @if(!auth('client')->check())
                alert('Necesitas iniciar sesión para agregar productos al carrito');
                window.location.href = '/cliente/ingresar';
                return;
            @endif
            
            // Si está autenticado, proceder con la adición al carrito
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/cliente/carrito/agregar/${productId}`;
            form.style.display = 'none';
            
            // Agregar token CSRF
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfInput);
            
            // Agregar cantidad
            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = 'quantity';
            quantityInput.value = '1';
            form.appendChild(quantityInput);
            
            document.body.appendChild(form);
            form.submit();
        }

        function updateCartCount(count) {
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
                
                // Animación de bounce en el contador
                cartCountElement.classList.add('cart-bounce');
                setTimeout(() => {
                    cartCountElement.classList.remove('cart-bounce');
                }, 600);
            }
        }

        // Filtros dinámicos
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, setting up event listeners');
            
            const filterForm = document.getElementById('filter-form');
            if (filterForm) {
                const inputs = filterForm.querySelectorAll('input, select');
                
                inputs.forEach(input => {
                    input.addEventListener('change', function() {
                        // Auto-submit en cambio de select
                        if (this.tagName === 'SELECT') {
                            filterForm.submit();
                        }
                    });
                });
            }

            // Función de prueba global para verificar notificaciones
            window.testNotification = function() {
                alert('¡Esta es una notificación de prueba!');
            };

            // Función de prueba para addToCart
            window.testAddToCart = function() {
                addToCart(999);
            };

            // Función para verificar estado del sistema
            window.checkSystem = function() {
                console.log('=== ESTADO DEL SISTEMA ===');
                console.log('jQuery disponible:', typeof $ !== 'undefined');
                console.log('Elemento cart-count:', document.getElementById('cart-count') ? 'Encontrado' : 'No encontrado');
                console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]') ? 'Disponible' : 'No disponible');
                console.log('=========================');
            };

            console.log('Event listeners setup complete.');
        });
    </script>

@endsection