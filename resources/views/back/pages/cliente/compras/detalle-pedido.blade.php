{{-- resources/views/back/pages/cliente/compras/detalle-pedido.blade.php --}}
@extends('back.layout.pages-layout')
@section('PageTitle', 'Detalle del Pedido #' . $order->id)
@section('content')

<style>
    .order-detail-container {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .order-header {
        background: linear-gradient(135deg, #1254a1 0%, #0e4082 100%);
        color: white;
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 30px;
        text-align: center;
    }

    .order-header h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
    }

    .order-header p {
        margin: 10px 0 0 0;
        opacity: 0.9;
        font-size: 16px;
    }

    .order-status {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 10px;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-processing {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .order-table thead th {
        background: #f8f9fa;
        color: #495057;
        padding: 15px;
        font-weight: 600;
        text-align: left;
        border-bottom: 2px solid #dee2e6;
    }

    .order-table tbody td {
        padding: 15px;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .order-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .product-info {
        display: flex;
        align-items: center;
    }

    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 15px;
        border: 2px solid #e9ecef;
    }

    .product-details h6 {
        margin: 0 0 5px 0;
        font-size: 14px;
        font-weight: 600;
        color: #495057;
    }

    .product-details p {
        margin: 0;
        font-size: 12px;
        color: #6c757d;
    }

    .price-cell {
        font-weight: 600;
        color: #28a745;
        font-size: 16px;
    }

    .quantity-badge {
        background: #1254a1;
        color: white;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .order-summary {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #1254a1;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding: 5px 0;
    }

    .summary-row.total {
        border-top: 2px solid #dee2e6;
        padding-top: 15px;
        margin-top: 15px;
        font-weight: 600;
        font-size: 18px;
        color: #1254a1;
    }

    .addresses-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-top: 30px;
    }

    .address-card {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
    }

    .address-card h5 {
        color: #1254a1;
        margin-bottom: 15px;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .address-card h5 i {
        margin-right: 10px;
        font-size: 18px;
    }

    .address-table {
        width: 100%;
    }

    .address-table td {
        padding: 5px 0;
        border: none;
    }

    .address-table td:first-child {
        font-weight: 500;
        color: #495057;
        width: 30%;
    }

    .btn-back {
        background: #6c757d;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        margin-top: 20px;
    }

    .btn-back:hover {
        background: #5a6268;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }

    .btn-back i {
        margin-right: 8px;
    }

    @media (max-width: 768px) {
        .addresses-section {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .order-table thead {
            display: none;
        }
        
        .order-table tbody tr {
            display: block;
            border: 1px solid #dee2e6;
            margin-bottom: 15px;
            border-radius: 8px;
            padding: 15px;
        }
        
        .order-table tbody td {
            display: block;
            padding: 8px 0;
            border: none;
            text-align: left;
        }
        
        .order-table tbody td:before {
            content: attr(data-label) ": ";
            font-weight: 600;
            color: #1254a1;
            display: inline-block;
            width: 100px;
        }
        
        .product-info {
            flex-direction: column;
            text-align: center;
        }
        
        .product-image {
            margin: 0 0 10px 0;
        }
    }
</style>

<div class="order-detail-container">
    <!-- Header del pedido -->
    <div class="order-header">
        <h2>Pedido #{{ $order->id }}</h2>
        <p>Realizado el {{ $order->created_at->format('d/m/Y \a \l\a\s H:i') }}</p>
        <span class="order-status 
            @if($order->status === 'pending') status-pending
            @elseif($order->status === 'processing') status-processing  
            @elseif($order->status === 'completed') status-completed
            @else status-cancelled
            @endif">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <!-- Detalles del pedido -->
    <div class="order-details-wrapper">
        <h4 class="mb-4" style="color: #1254a1; font-weight: 600;">
            <i class="fa fa-list-alt"></i> Detalles del Pedido
        </h4>
        
        <div class="table-responsive">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td data-label="Producto">
                            <div class="product-info">
                                @if($item->product && $item->product->product_image)
                                    <img src="{{ asset('images/products/' . $item->product->product_image) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="product-image">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" 
                                         alt="Sin imagen" 
                                         class="product-image">
                                @endif
                                <div class="product-details">
                                    <h6>{{ $item->product ? $item->product->name : 'Producto no disponible' }}</h6>
                                    @if($item->product && $item->product->seller)
                                        <p>Vendedor: {{ $item->product->seller->name }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td data-label="Precio" class="price-cell">
                            ${{ number_format($item->price, 2) }}
                        </td>
                        <td data-label="Cantidad">
                            <span class="quantity-badge">{{ $item->quantity }}</span>
                        </td>
                        <td data-label="Subtotal" class="price-cell">
                            ${{ number_format($item->price * $item->quantity, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Resumen del pedido -->
        <div class="order-summary">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>${{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Envío:</span>
                <span>Gratis</span>
            </div>
            <div class="summary-row total">
                <span>Total:</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Direcciones de facturación y envío -->
    <div class="addresses-section">
        <div class="address-card">
            <h5><i class="fa fa-credit-card"></i> Dirección de Facturación</h5>
            <table class="address-table">
                <tr>
                    <td>Nombre:</td>
                    <td>{{ $order->shipping_name ?: $order->client->name }}</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>{{ $order->shipping_email ?: $order->client->email }}</td>
                </tr>
                <tr>
                    <td>Teléfono:</td>
                    <td>{{ $order->shipping_phone ?: $order->client->phone ?: 'No especificado' }}</td>
                </tr>
                <tr>
                    <td>Dirección:</td>
                    <td>{{ $order->shipping_address ?: $order->client->address ?: 'No especificado' }}</td>
                </tr>
            </table>
        </div>

        <div class="address-card">
            <h5><i class="fa fa-truck"></i> Dirección de Envío</h5>
            <table class="address-table">
                <tr>
                    <td>Nombre:</td>
                    <td>{{ $order->shipping_name ?: $order->client->name }}</td>
                </tr>
                <tr>
                    <td>Dirección:</td>
                    <td>{{ $order->shipping_address ?: $order->client->address ?: 'No especificado' }}</td>
                </tr>
                <tr>
                    <td>Ciudad:</td>
                    <td>{{ $order->shipping_city ?: 'No especificado' }}</td>
                </tr>
                <tr>
                    <td>Código Postal:</td>
                    <td>{{ $order->shipping_cp ?: 'No especificado' }}</td>
                </tr>
                <tr>
                    <td>País:</td>
                    <td>{{ $order->shipping_country ?: 'México' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Botón de regreso -->
    <a href="{{ route('cliente.compras') }}" class="btn-back">
        <i class="fa fa-arrow-left"></i> Volver a Mis Pedidos
    </a>
</div>

@endsection