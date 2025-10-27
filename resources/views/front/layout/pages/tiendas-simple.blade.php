<!DOCTYPE html>
<html lang="es">
<head>
    @include('front.layout.inc.head')
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
                    
                    <h2>Tiendas Disponibles</h2>
                    <p>Total de tiendas: {{ $tiendas ? $tiendas->count() : 0 }}</p>

                    <!-- Start of Vendor Store -->
                    <div class="row">
                        @if($tiendas && $tiendas->count() > 0)
                            @foreach($tiendas as $tienda)
                            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>{{ $tienda->shop_name }}</h5>
                                    </div>
                                    <div class="card-body">
                                        @if($tienda->shop_address)
                                        <p><strong>Dirección:</strong> {{ $tienda->shop_address }}</p>
                                        @endif
                                        
                                        @if($tienda->seller)
                                        <p><strong>Vendedor:</strong> {{ $tienda->seller->name }}</p>
                                        @endif
                                        
                                        @if($tienda->shop_phone)
                                        <p><strong>Teléfono:</strong> {{ $tienda->shop_phone }}</p>
                                        @endif
                                        
                                        <p><strong>Miembro desde:</strong> {{ $tienda->created_at->format('M Y') }}</p>
                                        <p><strong>Productos:</strong> {{ $tienda->products_count ?? 0 }}</p>
                                        
                                        <a href="{{ route('tienda.detalle', $tienda->id) }}" class="btn btn-primary">Ver Tienda</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h4>No hay tiendas disponibles</h4>
                                    <p>Aún no se han registrado tiendas en la plataforma.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- End of Vendor Store -->

                    <!-- Pagination -->
                    @if($tiendas && $tiendas->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $tiendas->links() }}
                        </div>
                    @endif

                    <!-- Sección de Vendedores Recientes -->
                    @if(isset($vendedores) && $vendedores->count() > 0)
                    <div class="mt-5">
                        <h3>Vendedores Recientes</h3>
                        <div class="row">
                            @foreach ($vendedores as $vendedor)
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $vendedor->name }}</h6>
                                        <p class="card-text">@{{ $vendedor->username }}</p>
                                        <a href="{{ route('perfil.vendedor', $vendedor->username) }}" class="btn btn-sm btn-outline-primary">Ver Perfil</a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
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

    <!-- Plugin JS File -->
    <script src="/front/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/front/assets/js/main.min.js"></script>

</body>
</html>