<!DOCTYPE html>
<html lang="es">
<head>
@include('front.layout.inc.head')
<style>
.order-success {
    background: #e8f5e8;
    border: 2px solid #2563eb;
    border-radius: 10px;
    padding: 2rem;
    margin: 2rem 0;
    color: #155724;
}

.order-success i {
    font-size: 3rem;
    color: #2563eb;
    display: block;
    margin-bottom: 1rem;
}

.order-view {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 2rem;
    margin: 2rem 0;
    list-style: none;
}

.order-view li {
    flex-basis: 19%;
    text-align: center;
    padding: 1rem;
    border-right: 1px solid #dee2e6;
}

.order-view li:last-child {
    border-right: none;
}

.order-view label {
    display: block;
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.order-view strong {
    font-size: 1.1rem;
    color: #343a40;
    font-weight: 600;
}

.order-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.order-table th,
.order-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.order-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
}

.order-table tfoot th {
    background-color: transparent;
    font-weight: 600;
}

.order-table .total th,
.order-table .total td {
    font-size: 1.2rem;
    font-weight: bold;
    color: #2563eb;
}

.address-table {
    width: 100%;
}

.address-table th {
    width: 30%;
    font-weight: 600;
    color: #495057;
    padding: 0.5rem 0;
    vertical-align: top;
}

.address-table td {
    padding: 0.5rem 0;
    color: #6c757d;
}

.ecommerce-address {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1.5rem;
}

.title-underline {
    border-bottom: 2px solid #2563eb;
    padding-bottom: 0.5rem;
    margin-bottom: 1.5rem;
}

@media (max-width: 768px) {
    .order-view {
        flex-direction: column;
    }
    
    .order-view li {
        flex-basis: 100%;
        border-right: none;
        border-bottom: 1px solid #dee2e6;
    }
    
    .order-view li:last-child {
        border-bottom: none;
    }
}
</style>
</head>

<body class="home">
    <!-- start of .page-wrapper -->
    <div class="page-wrapper">
        <h1 class="d-none">Agro - MarketPlace</h1>
        <!-- Start of Header -->
        @include('front.layout.inc.headerdos')
        <!-- End of Header -->
        <br>

        <!-- Start of Main -->
        <main class="main order">
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb shop-breadcrumb bb-no">
                        <li class="passed"><a href="{{ route('cliente.carrito') }}">Carrito de Compras</a></li>
                        <li class="passed"><a href="{{ route('cliente.checkout') }}">Finalizar Pedido</a></li>
                        <li class="active"><a href="#">Pedido Completado</a></li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of PageContent -->
            <div class="page-content mb-10 pb-2">
                <div class="container">
                    <div class="order-success text-center font-weight-bolder text-dark">
                        <i class="fas fa-check"></i>
                        Gracias. Tu pedido ha sido recibido.
                    </div>
                    <!-- End of Order Success -->

                    <ul class="order-view list-style-none">
                        <li>
                            <label>Número de Pedido</label>
                            <strong>{{ $order->id }}</strong>
                        </li>
                        <li>
                            <label>Estado</label>
                            <strong>
                                @switch($order->status)
                                    @case('pendiente')
                                        En Espera
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
                            <label>Método de Pago</label>
                            <strong>
                                @if($order->payment_method === 'stripe')
                                    Tarjeta de Crédito/Débito
                                @else
                                    {{ ucfirst($order->payment_method) }}
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
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        @if($item->product && $item->product->slug)
                                            <a href="{{ route('producto.index', $item->product->slug) }}">
                                                {{ $item->product->name ?? 'Producto no disponible' }}
                                            </a>
                                        @else
                                            {{ $item->product->name ?? 'Producto no disponible' }}
                                        @endif
                                        &nbsp;<strong>x {{ $item->quantity }}</strong>
                                        @if($item->product && $item->product->seller)
                                        <br>
                                        Vendedor: <a href="#">{{ $item->product->seller->name }}</a>
                                        @endif
                                    </td>
                                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Subtotal:</th>
                                    <td>${{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Envío:</th>
                                    <td>Tarifa fija</td>
                                </tr>
                                <tr>
                                    <th>Método de pago:</th>
                                    <td>
                                        @if($order->payment_method === 'stripe')
                                            Tarjeta de Crédito/Débito
                                        @else
                                            {{ ucfirst($order->payment_method) }}
                                        @endif
                                    </td>
                                </tr>
                                <tr class="total">
                                    <th class="border-no">Total:</th>
                                    <td class="border-no">${{ number_format($order->total, 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- End of Order Details -->

                    @if($order->stripe_payment_status === 'succeeded')
                    <div class="alert alert-success mb-5">
                        <i class="fas fa-check-circle"></i>
                        <strong>¡Pago Confirmado!</strong> Tu pago ha sido procesado exitosamente.
                    </div>
                    @endif

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
                                                <tr>
                                                    <th>Empresa:</th>
                                                    <td>{{ $order->shipping_company ?: 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Dirección:</th>
                                                    <td>{{ $order->shipping_address }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Ciudad:</th>
                                                    <td>{{ $order->shipping_city }}, {{ $order->shipping_state }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Código Postal:</th>
                                                    <td>{{ $order->shipping_cp }}</td>
                                                </tr>
                                                <tr>
                                                    <th>País:</th>
                                                    <td>
                                                        @switch($order->shipping_country)
                                                            @case('MX')
                                                                México
                                                                @break
                                                            @case('US')
                                                                Estados Unidos
                                                                @break
                                                            @case('CA')
                                                                Canadá
                                                                @break
                                                            @default
                                                                {{ $order->shipping_country }}
                                                        @endswitch
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Email:</th>
                                                    <td>{{ $order->shipping_email }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Teléfono:</th>
                                                    <td>{{ $order->shipping_phone }}</td>
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
                                                <tr>
                                                    <th>Empresa:</th>
                                                    <td>{{ $order->shipping_company ?: 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Dirección:</th>
                                                    <td>{{ $order->shipping_address }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Ciudad:</th>
                                                    <td>{{ $order->shipping_city }}, {{ $order->shipping_state }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Código Postal:</th>
                                                    <td>{{ $order->shipping_cp }}</td>
                                                </tr>
                                                <tr>
                                                    <th>País:</th>
                                                    <td>
                                                        @switch($order->shipping_country)
                                                            @case('MX')
                                                                México
                                                                @break
                                                            @case('US')
                                                                Estados Unidos
                                                                @break
                                                            @case('CA')
                                                                Canadá
                                                                @break
                                                            @default
                                                                {{ $order->shipping_country }}
                                                        @endswitch
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Email:</th>
                                                    <td>{{ $order->shipping_email }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Teléfono:</th>
                                                    <td>{{ $order->shipping_phone }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </address>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Account Address -->

                    <div class="row mt-6">
                        <div class="col-md-4">
                            <a href="{{ route('inicio') }}" class="btn btn-dark btn-rounded btn-icon-left">
                                <i class="w-icon-long-arrow-left"></i>Continuar Comprando
                            </a>
                        </div>
                        <div class="col-md-4 text-center">
                            @if(request()->routeIs('order.test.details'))
                                <a href="{{ route('order.test.pdf', $order->id) }}" class="btn btn-success btn-rounded btn-icon-left" target="_blank">
                                    <i class="w-icon-download"></i>Descargar PDF
                                </a>
                            @else
                                <a href="{{ route('cliente.checkout.pdf', $order->id) }}" class="btn btn-success btn-rounded btn-icon-left" target="_blank">
                                    <i class="w-icon-download"></i>Descargar PDF
                                </a>
                            @endif
                        </div>
                        <div class="col-md-4 text-right">
                            <a href="{{ route('cliente.compras') }}" class="btn btn-primary btn-rounded">
                                Ver Mis Pedidos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of PageContent -->
        </main>
        <!-- End of Main -->

        <!-- Footer -->
        @include('front.layout.inc.footer')
        <!-- End of Footer -->
    </div>
    <!-- end of .page-wrapper -->

    <!-- Scripts -->
    @include('front.layout.inc.scripts')
</body>
</html>