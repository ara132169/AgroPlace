<header class="header">
            <div class="header-top">
                <div class="container">
                    <div class="header-left">
                       <!-- <p class="welcome-msg">Welcome to Wolmart Store message or remove it!</p> -->
                    </div>
                    <div class="header-right" style="margin-top: 5px; margin-bottom: 5px;">
                        
                        <span class="divider d-lg-show"></span>
                        <a href="#" class="d-lg-show">Blog</a>
                        <a href="https://agro-marketmx.com/contacto" class="d-lg-show">Contacto</a>
                       
                        @auth('client')
                        <span class="d-lg-show login">
                            <i class="w-icon-account"></i>Bienvenido, {{ Auth::guard('client')->user()->name }}
                        </span>
                        @endauth

                        @auth('seller')
                            <span class="d-lg-show login">
                                <i class="w-icon-user"></i>Bienvenido, {{ Auth::guard('seller')->user()->name }}
                            </span>
                        @endauth

                        @guest('client')
                            @guest('seller')
                            <a href="{{ url('/cliente/ingresar') }}" class="d-lg-show login sign-in">
                                <i class="w-icon-account"></i>Ingresar como usuario
                            </a>
                            <span class="delimiter d-lg-show">/</span>
                            <a href="{{ url('/cliente/registrarse') }}" class="ml-0 d-lg-show login register">Registrarse</a>
                            @endguest
                        @endguest

                    </div>
                </div>
            </div>
            <!-- End of Header Top -->

            <div class="header-middle">
                <div class="container">
                    <div class="header-left mr-md-4">
                        <a href="#" class="mobile-menu-toggle w-icon-hamburger" aria-label="menu-toggle">
                        </a>
                         <a href="{{ url('/') }}" class="logo ml-lg-0">
                            <img src="/images/site/{{ get_settings()->site_logo}}" alt="logo" width="174" height="65" />
                        </a>
                        <form method="get" action="#" class="input-wrapper header-search hs-expanded hs-round d-none d-md-flex">
                            <div class="select-box">
                                <select id="category" name="category">
                                @if(count(get_categories())>0)
                                @foreach(get_categories() as $category)
                                    <option value="">{{$category->category_name}}
                                                @if( count($category->subcategories) > 0 )
                                                    
                                                @endif</option>
                                @endforeach
                                @endif()   
                                  
                                </select>
                            </div>
                            <input type="text" class="form-control" name="search" id="search"
                                placeholder="Buscar..." required />
                            <button class="btn btn-search" type="submit"><i class="w-icon-search"></i>
                            </button>
                        </form>
                    </div>
                    <div class="header-right ml-4">
                        <div class="header-call d-xs-show d-lg-flex align-items-center">
                            <a href="tel:#" class="w-icon-call"></a>
                            <div class="call-info d-lg-show">
                                <h4 class="chat font-weight-normal font-size-md text-normal ls-normal text-light mb-0">
                                    <a href="mailto:#" class="text-capitalize">¡Contáctanos!</a> </h4>
                                <a href="tel:#" class="phone-number font-weight-bolder ls-50">+52 2221123234</a>
                            </div>
                        </div>
                        <!-- <a class="wishlist label-down link d-xs-show" href="#">
                            <i class="w-icon-heart"></i>
                            <span class="wishlist-label d-lg-show">Lista de deseo</span>
                        </a> -->

                        <div class="dropdown cart-dropdown cart-offcanvas mr-0 mr-lg-2">
                    <div class="cart-overlay"></div>
                    <a href="{{ route('cliente.carrito') }}" class="cart-toggle label-down link">
                        <i class="w-icon-cart">
                        <span class="cart-count" id="cart-count">

                                {{ session('cart') ? collect(session('cart'))->sum('quantity') : 0 }}
                            </span>
                        </i>
                        <span class="cart-label">Carrito</span>
                    </a>
                    <div class="dropdown-box">
                        <div class="cart-header">
                            <span>Productos Agregados</span>
                            <a href="#" class="btn-close">Cerrar<i class="w-icon-long-arrow-right"></i></a>
                        </div>

                        @php
                            $cartItems = session('cart', []);
                            $subtotal = collect($cartItems)->sum(function ($item) {
                                return $item['price'] * $item['quantity'];
                            });
                        @endphp

                        <div class="products">
                            @forelse($cartItems as $id => $item)
                                <div class="product product-cart">
                                    <div class="product-detail">
                                        <a href="#" class="product-name">
                                            {{ Str::limit($item['name'], 35) }}
                                        </a>
                                        <div class="price-box">
                                            <span class="product-quantity">{{ $item['quantity'] }}</span>
                                            <span class="product-price">
                                                ${{ number_format($item['price'], 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    <figure class="product-media">
                                        <a href="#">
                                        <img src="{{ asset('images/products/' . ($item['image'] ?? 'default.jpg')) }}" alt="{{ $item['name'] }}" width="84" height="94" />
                                        </a>
                                    </figure>
                                    <form method="POST" action="">
                                       
                                        <button class="btn btn-link btn-close"  aria-label="button">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-center">Tu carrito está vacío.</p>
                            @endforelse
                        </div>

                        <div class="cart-total">
                            <label>Subtotal:</label>
                            <span class="price">${{ number_format($subtotal, 2) }}</span>
                        </div>

                        <div class="cart-action">
                            <a href="{{ route('cliente.carrito') }}" class="btn btn-dark btn-outline btn-rounded">Ver Carrito</a>
                            <a href="{{ route('cliente.cliente.checkout') }}" class="btn btn-primary btn-rounded">Finalizar Compra</a>

                        </div>
                    </div>
    </div>
                        <!-- End of Cart Dropdown -->   
                    </div>
                </div>
            </div>
            <!-- End of Header Middle -->

            <div class="header-bottom sticky-content fix-top sticky-header">
                <div class="container">
                    <div class="inner-wrap">
                        <div class="header-left">
                            <div class="dropdown category-dropdown has-border" data-visible="true">
                                <a href="#" class="category-toggle" role="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="true" data-display="static"
                                    title="Browse Categories">
                                    <i class="w-icon-category"></i>
                                    <span>Lista de categorías</span>
                                </a>

                                <div class="dropdown-box">
                                @if(count(get_categories())>0)
                                @foreach(get_categories() as $category)
                                    <ul class="menu vertical-menu category-menu">
                                    <li>
                                            <a href="{{ route('categoria.productos', ['slug' => $category->category_slug]) }}">
                                                <i class="/images/categories/{{$category->category_image}}"></i>  {{$category->category_name}}
                                                @if( count($category->subcategories) > 0 )
                                                @endif
                                            </a>
                                            @if(count($category->subcategories) > 0 )

                                            <ul class="megamenu">
                                            @foreach($category->subcategories as $subcategory )
                                               
                                               @if($subcategory->is_child_of == 0)
                                                <li>
                                                    <h4 class="menu-title">{{ $subcategory->subcategory_name }}</h4>
                                                    <hr class="divider">
                                                    @if(!empty($subcategory->children) && $subcategory->children->count())
                                                    <ul>
                                                    @foreach($subcategory->children as $child_subcategory)
                                                        <li><a href="{{ route('subcategoria.productos', ['slug' => $child_subcategory->subcategory_slug]) }}">{{ $child_subcategory->subcategory_name }}</a>
                                                        </li>
                                                    @endforeach
                                                    </ul>
                                                    @endif
                                                </li>
                                                @endif
                                                @endforeach
                                            </ul>
                                            @endif
                                        </li>
                                    </ul>
                                    @endforeach
                                    @endif()
                                </div>
                            </div>
                            <nav class="main-nav ml-14 ">
                                <ul class="menu">
                                    <li class="{{request()->routeIs('inicio') ? 'active' : ''}}">
                                        <a href="{{ url('/') }}">Inicio</a>
                                    </li>
                                    <li>
                                        <a href="">Productos</a>

                                        <!-- Start of Megamenu -->
                                         
                                        
                                    </li>
                                    <li>
                                        <a href="{{ route('tiendas.index') }}">Tiendas</a>
                                       
                                    </li>
                                    <li>
                                        <a href="blog-mask-grid.html">Blog</a>
                                        
                                    </li>
                                    <li>
                                        <a href="{{ url('nosotros') }}">Nosotros</a>
                                        
                                    </li>
                                    <li>
                                        <a href="{{ url('contacto') }}">Contacto</a>
                                        
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <div class="header-right">
                            <a href="{{ url('/tienda/registrarse') }}" class="d-xl-show"><i class="w-icon-user mr-1"></i>Únete a nuestra plataforma</a>
                            
                        </div>
                    </div>
                </div>
            </div>
        </header>