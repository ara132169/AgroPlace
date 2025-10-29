<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">

    <title>Pedido Completado - Agro Marketplace</title>

    <meta name="keywords" content="Marketplace ecommerce" />
    <meta name="description" content="Agro Marketplace - Tu pedido ha sido completado exitosamente">
    <meta name="author" content="Agro Marketplace">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="/front/assets/images/icons/favicon.png">

    <!-- WebFont.js -->
    <script>
        WebFontConfig = {
            google: { families: ['Poppins:400,500,600,700'] }
        };
        (function (d) {
            var wf = d.createElement('script'), s = d.scripts[0];
            wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js';
            wf.async = true;
            s.parentNode.insertBefore(wf, s);
        })(document);
    </script>

    <link rel="preload" href="/front/assets/vendor/fontawesome-free/webfonts/fa-regular-400.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="/front/assets/vendor/fontawesome-free/webfonts/fa-solid-900.woff2" as="font" type="font/woff2"
        crossorigin="anonymous">
    <link rel="preload" href="/front/assets/vendor/fontawesome-free/webfonts/fa-brands-400.woff2" as="font" type="font/woff2"
            crossorigin="anonymous">
    <link rel="preload" href="/front/assets/fonts/wolmart.ttf?png09e" as="font" type="font/ttf" crossorigin="anonymous">

    <!-- Vendor CSS -->
    <link rel="stylesheet" type="text/css" href="/front/assets/vendor/fontawesome-free/css/all.min.css">

    <!-- Plugin CSS -->
    <link rel="stylesheet" type="text/css" href="/front/assets/vendor/magnific-popup/magnific-popup.min.css">

    <!-- Default CSS -->
    <link rel="stylesheet" type="text/css" href="/front/assets/css/style.min.css">
</head>

<body>
    <div class="page-wrapper">
        <h1 class="d-none">Agro Marketplace - Pedido Completado</h1>
        
        <!-- Start of Header -->
        @include('front.layout.inc.headerdos')
        <!-- End of Header -->

        <!-- Start of Main -->
        <main class="main order">
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb shop-breadcrumb bb-no">
                        <li class="passed"><a href="{{ route('front.layout.pages.cliente.carrito.index') }}">Carrito de Compras</a></li>
                        <li class="passed"><a href="{{ route('cliente.checkout') }}">Checkout</a></li>
                        <li class="active"><a href="#">Pedido Completado</a></li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of PageContent -->
            <div class="page-content mb-10 pb-2">
                <div class="container">
                    @if(session('success'))
                    <div class="order-success text-center font-weight-bolder text-dark">
                        <i class="fas fa-check"></i>
                        Gracias. Tu pedido ha sido recibido.
                    </div>
                    @else
                    <div class="order-success text-center font-weight-bolder text-dark">
                        <i class="fas fa-info-circle"></i>
                        Detalles de tu pedido.
                    </div>
                    @endif
                    <!-- End of Order Success -->

                    <ul class="order-view list-style-none">
                        <li>
                            <label>Número de pedido</label>
                            <strong>{{ $order->id }}</strong>
                        </li>
                        <li>
                            <label>Estado</label>
                            <strong>
                                @switch($order->status)
                                    @case('pendiente')
                                        En espera
                                        @break
                                    @case('confirmed')
                                        Confirmado
                                        @break
                                    @case('cancelled')
                                        Cancelado
                                        @break
                                    @default
                                        {{ ucfirst($order->status) }}
                                @endswitch
                            </strong>
                        </li>
                        <li>
                            <label>Fecha</label>
                            <strong>{{ $order->created_at->format('d \d\e F, Y') }}</strong>
                        </li>
                        <li>
                            <label>Total</label>
                            <strong>${{ number_format($order->total, 2) }}</strong>
                        </li>
                        <li>
                            <label>Método de pago</label>
                            <strong>
                                @if($order->payment_method === 'stripe')
                                    Tarjeta de crédito/débito
                                @else
                                    {{ ucfirst($order->payment_method ?? 'No especificado') }}
                                @endif
                            </strong>
                        </li>
                    </ul>
                    <!-- End of Order View -->

                    <div class="order-details-wrapper mb-5">
                        <h4 class="title text-uppercase ls-25 mb-5">Detalles del Pedido</h4>
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th class="text-dark">Producto</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <a href="{{ route('producto.index', $item->product->slug ?? '#') }}">
                                            {{ $item->product->name ?? 'Producto no disponible' }}
                                        </a>&nbsp;<strong>x {{ $item->quantity }}</strong><br>
                                        @if($item->product && $item->product->shop)
                                            Vendedor: <a href="{{ route('perfil.vendedor', $item->product->shop->seller->username ?? '#') }}">
                                                {{ $item->product->shop->seller->name ?? 'Vendedor' }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Subtotal:</th>
                                    <td>${{ number_format($order->total, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Envío:</th>
                                    <td>Tarifa fija</td>
                                </tr>
                                <tr>
                                    <th>Método de pago:</th>
                                    <td>
                                        @if($order->payment_method === 'stripe')
                                            Tarjeta de crédito/débito
                                        @else
                                            {{ ucfirst($order->payment_method ?? 'No especificado') }}
                                        @endif
                                    </td>
                                </tr>
                                @if($order->stripe_payment_status)
                                <tr>
                                    <th>Estado del pago:</th>
                                    <td>
                                        @switch($order->stripe_payment_status)
                                            @case('pending')
                                                <span class="text-warning">Pendiente</span>
                                                @break
                                            @case('succeeded')
                                                <span class="text-success">Pagado</span>
                                                @break
                                            @case('failed')
                                                <span class="text-danger">Falló</span>
                                                @break
                                            @default
                                                {{ ucfirst($order->stripe_payment_status) }}
                                        @endswitch
                                    </td>
                                </tr>
                                @endif
                                <tr class="total">
                                    <th class="border-no">Total:</th>
                                    <td class="border-no">${{ number_format($order->total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- End of Order Details -->

                    <div id="account-addresses">
                        <div class="row">
                            <div class="col-sm-6 mb-8">
                                <div class="ecommerce-address billing-address">
                                    <h4 class="title title-underline ls-25 font-weight-bold">Dirección de Facturación</h4>
                                    <address class="mb-4">
                                        <table class="address-table">
                                            <tbody>
                                                <tr>
                                                    <th>Nombre:</th>
                                                    <td>{{ $order->shipping_name }}</td>
                                                </tr>
                                                @if($order->shipping_company)
                                                <tr>
                                                    <th>Empresa:</th>
                                                    <td>{{ $order->shipping_company }}</td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <th>Dirección:</th>
                                                    <td>{{ $order->shipping_address }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Ciudad:</th>
                                                    <td>{{ $order->shipping_city }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estado:</th>
                                                    <td>{{ $order->shipping_state }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Código Postal:</th>
                                                    <td>{{ $order->shipping_cp }}</td>
                                                </tr>
                                                <tr>
                                                    <th>País:</th>
                                                    <td>{{ $order->shipping_country }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Teléfono:</th>
                                                    <td>{{ $order->shipping_phone }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email:</th>
                                                    <td>{{ $order->shipping_email }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </address>
                                </div>
                            </div>
                            <div class="col-sm-6 mb-8">
                                <div class="ecommerce-address shipping-address">
                                    <h4 class="title title-underline ls-25 font-weight-bold">Dirección de Envío</h4>
                                    <address class="mb-4">
                                        <table class="address-table">
                                            <tbody>
                                                <tr>
                                                    <th>Nombre:</th>
                                                    <td>{{ $order->shipping_name }}</td>
                                                </tr>
                                                @if($order->shipping_company)
                                                <tr>
                                                    <th>Empresa:</th>
                                                    <td>{{ $order->shipping_company }}</td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <th>Dirección:</th>
                                                    <td>{{ $order->shipping_address }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Ciudad:</th>
                                                    <td>{{ $order->shipping_city }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Estado:</th>
                                                    <td>{{ $order->shipping_state }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Código Postal:</th>
                                                    <td>{{ $order->shipping_cp }}</td>
                                                </tr>
                                                <tr>
                                                    <th>País:</th>
                                                    <td>{{ $order->shipping_country }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Teléfono:</th>
                                                    <td>{{ $order->shipping_phone }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email:</th>
                                                    <td>{{ $order->shipping_email }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Account Address -->

                    <div class="text-center mt-8">
                        <a href="{{ route('index') }}" class="btn btn-dark btn-rounded btn-icon-left mr-4">
                            <i class="w-icon-long-arrow-left"></i>Continuar Comprando
                        </a>
                        @if($order->stripe_payment_status === 'succeeded')
                        <button onclick="window.print()" class="btn btn-primary btn-rounded btn-icon-left">
                            <i class="fas fa-print"></i>Imprimir Pedido
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            <!-- End of PageContent -->
        </main>
        <!-- End of Main -->

        @include('front.layout.inc.footer')
        <!-- End of Footer -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Start of Sticky Footer -->
    @include('front.layout.inc.footermovil')
    <!-- End of Sticky Footer -->
    
    <!-- Start of Scroll Top -->
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> 
        <i class="w-icon-angle-up"></i> 
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"> 
            <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle> 
        </svg> 
    </a>
    <!-- End of Scroll Top -->

    <!-- Start of Mobile Menu -->
    @include('front.layout.inc.mobile-menu')
    <!-- End of Mobile Menu -->

    <!-- Plugin JS File -->
    <script src="/front/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/front/assets/vendor/sticky/sticky.js"></script>
    <script src="/front/assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="/front/assets/js/main.min.js"></script>

    <style>
        .order-success {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 30px;
            font-size: 18px;
        }

        .order-success i {
            display: inline-block;
            width: 60px;
            height: 60px;
            background: #27ae60;
            border-radius: 50%;
            line-height: 60px;
            text-align: center;
            color: white;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .order-view {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .order-view li {
            text-align: center;
            flex: 1;
            min-width: 150px;
            margin-bottom: 20px;
        }

        .order-view label {
            display: block;
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .order-view strong {
            font-size: 16px;
            color: #212529;
            font-weight: 600;
        }

        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .order-table th,
        .order-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            text-align: left;
        }

        .order-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
        }

        .order-table tfoot th {
            background-color: transparent;
            border-top: 2px solid #dee2e6;
        }

        .order-table .total th,
        .order-table .total td {
            font-size: 18px;
            font-weight: bold;
            color: #212529;
        }

        .address-table {
            width: 100%;
        }

        .address-table th {
            font-weight: 600;
            color: #495057;
            padding: 8px 15px 8px 0;
            width: 30%;
        }

        .address-table td {
            padding: 8px 0;
            color: #212529;
        }

        .ecommerce-address {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 25px;
        }

        .title-underline {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .text-success {
            color: #28a745 !important;
        }

        .text-warning {
            color: #ffc107 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        @media (max-width: 768px) {
            .order-view {
                flex-direction: column;
            }
            
            .order-view li {
                text-align: left;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #e9ecef;
                padding-bottom: 15px;
            }
            
            .order-view li:last-child {
                border-bottom: none;
                padding-bottom: 0;
            }
        }

        @media print {
            .btn, .breadcrumb-nav, header, footer, .sticky-footer, .scroll-top, .mobile-menu-wrapper {
                display: none !important;
            }
            
            .page-content {
                margin: 0 !important;
                padding: 20px !important;
            }
        }
    </style>

</body>

</html>