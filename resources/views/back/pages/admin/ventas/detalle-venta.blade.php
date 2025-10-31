{{-- resources/views/back/pages/admin/ventas/detalle-venta.blade.php --}}
@extends('back.layout.pages-layout')
@section('PageTitle', 'Detalle de Venta #' . $order->id)
@section('content')

<style>
    .sale-detail-container {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        font-family: 'Inter', sans-serif;
    }

    .sale-header {
        background: linear-gradient(135deg, #1254a1 0%, #0e4082 100%);
        color: white;
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 30px;
        text-align: center;
    }

    .sale-header h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
    }

    .sale-header p {
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

    .info-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        border-left: 4px solid #1254a1;
    }

    .info-section h4 {
        color: #1254a1;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-bottom: 30px;
    }

    .info-row {
        display: flex;
        margin-bottom: 10px;
    }

    .info-label {
        font-weight: 500;
        color: #495057;
        width: 120px;
        margin-right: 10px;
    }

    .info-value {
        color: #212529;
    }

    .sale-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .sale-table thead th {
        background: #f8f9fa;
        color: #495057;
        padding: 15px;
        font-weight: 600;
        text-align: left;
        border-bottom: 2px solid #dee2e6;
    }

    .sale-table tbody td {
        padding: 15px;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .sale-table tbody tr:hover {
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

    .seller-info {
        background: #e3f2fd;
        padding: 10px;
        border-radius: 6px;
        margin-bottom: 10px;
    }

    .seller-info h6 {
        margin: 0 0 5px 0;
        color: #1976d2;
        font-weight: 600;
    }

    .seller-info p {
        margin: 0;
        font-size: 12px;
        color: #666;
    }

    .sale-summary {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #28a745;
        margin-bottom: 30px;
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
        color: #28a745;
    }

    .vendors-summary {
        background: #fff3e0;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #f57c00;
        margin-bottom: 30px;
    }

    .vendor-card {
        background: white;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 15px;
        border: 1px solid #e0e0e0;
    }

    .vendor-card:last-child {
        margin-bottom: 0;
    }

    .vendor-name {
        font-weight: 600;
        color: #f57c00;
        margin-bottom: 5px;
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
        .sale-table thead {
            display: none;
        }
        
        .sale-table tbody tr {
            display: block;
            border: 1px solid #dee2e6;
            margin-bottom: 15px;
            border-radius: 8px;
            padding: 15px;
        }
        
        .sale-table tbody td {
            display: block;
            padding: 8px 0;
            border: none;
            text-align: left;
        }
        
        .sale-table tbody td:before {
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

        .info-row {
            flex-direction: column;
        }

        .info-label {
            width: auto;
            margin-bottom: 5px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="sale-detail-container">
    <!-- Header de la venta -->
    <div class="sale-header">
        <h2>Venta #{{ $order->id }}</h2>
        <p>Pedido realizado el {{ \Carbon\Carbon::parse($order->created_at)->format('j \d\e F \d\e Y \a \l\a\s g:i A') }}</p>
        <span class="order-status 
            @if($order->status === 'pendiente') status-pending
            @elseif($order->status === 'pagado') status-processing  
            @elseif($order->status === 'enviado') status-processing  
            @elseif($order->status === 'completado') status-completed
            @else status-cancelled
            @endif">
            @if($order->status === 'pendiente')
                Pendiente
            @elseif($order->status === 'pagado')
                Pagado
            @elseif($order->status === 'enviado')
                Enviado
            @elseif($order->status === 'completado')
                Completado
            @else
                Cancelado
            @endif
        </span>
    </div>

    <!-- Información del cliente y entrega -->
    <div class="info-grid">
        <div class="info-section">
            <h4><i class="fa fa-user"></i> Información del Comprador</h4>
            <div class="info-row">
                <span class="info-label">Tipo:</span>
                <span class="info-value">
                    <span class="badge badge-{{ $order->buyer_type === 'client' ? 'primary' : 'success' }}">
                        {{ $order->buyer_type === 'client' ? 'Cliente' : 'Vendedor' }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Nombre:</span>
                <span class="info-value">{{ $order->client_name ?? 'Comprador no disponible' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $order->client_email ?? 'No especificado' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Teléfono:</span>
                <span class="info-value">{{ $order->shipping_phone ?: $order->client_phone ?: 'No especificado' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Dirección:</span>
                <span class="info-value">{{ $order->shipping_address ?: $order->client_address ?: 'No especificado' }}</span>
            </div>
        </div>

        <div class="info-section">
            <h4><i class="fa fa-truck"></i> Información de Entrega</h4>
            <div class="info-row">
                <span class="info-label">Método:</span>
                <span class="info-value">Entrega a domicilio</span>
            </div>
            <div class="info-row">
                <span class="info-label">Estado:</span>
                <span class="info-value">
                    @if($order->status === 'pendiente')
                        Preparando pedido
                    @elseif($order->status === 'pagado')
                        Pagado - Preparando
                    @elseif($order->status === 'enviado')
                        En camino
                    @elseif($order->status === 'completado')
                        Entregado
                    @else
                        Cancelado
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Total:</span>
                <span class="info-value"><strong>${{ number_format($order->total, 2) }}</strong></span>
            </div>
        </div>
    </div>

    <!-- Resumen por vendedores -->
    <div class="vendors-summary">
        <h5 style="color: #f57c00; margin-bottom: 15px;">
            <i class="fa fa-store"></i> Resumen por Vendedores
        </h5>
        @foreach($totalesPorVendedor as $vendedor)
        <div class="vendor-card">
            <div class="vendor-name">{{ $vendedor['vendedor'] }}</div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $vendedor['email'] }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Teléfono:</span>
                <span class="info-value">{{ $vendedor['phone'] ?: 'No especificado' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Productos:</span>
                <span class="info-value">{{ $vendedor['productos'] }} producto{{ $vendedor['productos'] > 1 ? 's' : '' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total:</span>
                <span class="info-value"><strong>${{ number_format($vendedor['total'], 2) }}</strong></span>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Productos del pedido -->
    <div class="sale-details-wrapper">
        <h4 class="mb-4" style="color: #1254a1; font-weight: 600;">
            <i class="fa fa-box"></i> Productos del Pedido
        </h4>
        
        <div class="table-responsive">
            <table class="sale-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Vendedor</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orderItems as $item)
                    <tr>
                        <td data-label="Producto">
                            <div class="product-info">
                                @if($item->product_image)
                                    <img src="{{ asset('images/products/' . $item->product_image) }}" 
                                         alt="{{ $item->product_name }}" 
                                         class="product-image">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" 
                                         alt="Sin imagen" 
                                         class="product-image">
                                @endif
                                <div class="product-details">
                                    <h6>{{ $item->product_name ?? 'Producto no disponible' }}</h6>
                                    <p>SKU: {{ $item->product_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td data-label="Vendedor">
                            <div class="seller-info">
                                <h6>{{ $item->seller_name }}</h6>
                                <p>{{ $item->seller_email }}</p>
                                @if($item->seller_phone)
                                    <p>Tel: {{ $item->seller_phone }}</p>
                                @endif
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

        <!-- Resumen financiero -->
        <div class="sale-summary">
            <h5 style="color: #28a745; margin-bottom: 15px;">
                <i class="fa fa-calculator"></i> Resumen Financiero
            </h5>
            @php
                $subtotal = $orderItems->sum(function($item) { 
                    return $item->price * $item->quantity; 
                });
            @endphp
            <div class="summary-row">
                <span>Subtotal productos:</span>
                <span>${{ number_format($subtotal, 2) }}</span>
            </div>
            <div class="summary-row">
                <span>Costo de envío:</span>
                <span>Incluido</span>
            </div>
            <div class="summary-row">
                <span>Comisión plataforma (estimada):</span>
                <span>${{ number_format($subtotal * 0.05, 2) }}</span>
            </div>
            <div class="summary-row total">
                <span>Total del pedido:</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Botón de regreso -->
    <a href="{{ route('admin.ventas') }}" class="btn-back">
        <i class="fa fa-arrow-left"></i> Volver a Todas las Ventas
    </a>
</div>

@endsection