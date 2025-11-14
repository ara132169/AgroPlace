{{-- resources/views/back/pages/tienda/ventas/detalle-venta.blade.php --}}
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

    .customer-info {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        border-left: 4px solid #1254a1;
    }

    .customer-info h4 {
        color: #1254a1;
        margin-bottom: 15px;
        font-weight: 600;
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

    .sale-summary {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #28a745;
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
    }
</style>

<div class="sale-detail-container">
    <!-- Header de la venta -->
    <div class="sale-header">
        <h2>Venta #{{ $order->id }}</h2>
        <p>Pedido realizado el {{ $order->created_at->format('j \d\e F \d\e Y \a \l\a\s g:i A') }}</p>
        <span class="order-status 
            @if($order->status === 'pending') status-pending
            @elseif($order->status === 'processing') status-processing  
            @elseif($order->status === 'completed') status-completed
            @else status-cancelled
            @endif">
            @if($order->status === 'pending')
                Pendiente
            @elseif($order->status === 'processing')
                Procesando
            @elseif($order->status === 'completed')
                Completado
            @else
                Cancelado
            @endif
        </span>
    </div>

    <!-- Información del cliente -->
    <div class="customer-info">
        <h4><i class="fa fa-user"></i> Información del Cliente</h4>
        <div class="info-row">
            <span class="info-label">Nombre:</span>
            <span class="info-value">{{ $order->client->name ?? 'Cliente no disponible' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email:</span>
            <span class="info-value">{{ $order->client->email ?? 'No especificado' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Teléfono:</span>
            <span class="info-value">{{ $order->shipping_phone ?: $order->client->phone ?: 'No especificado' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Dirección:</span>
            <span class="info-value">{{ $order->shipping_address ?: $order->client->address ?: 'No especificado' }}</span>
        </div>
    </div>

    <!-- Productos vendidos -->
    <div class="sale-details-wrapper">
        <h4 class="mb-4" style="color: #1254a1; font-weight: 600;">
            <i class="fa fa-box"></i> Productos Vendidos
        </h4>
        
        <div class="table-responsive">
            <table class="sale-table">
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
                                    <p>SKU: {{ $item->product ? $item->product->id : 'N/A' }}</p>
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

        <!-- Resumen de la venta -->
        <div class="sale-summary">
            <h5 style="color: #28a745; margin-bottom: 15px;">
                <i class="fa fa-calculator"></i> Resumen de Venta
            </h5>
            @php
                $subtotal = $order->items->sum(function($item) { 
                    return $item->price * $item->quantity; 
                });
            @endphp
            <div class="summary-row">
                <span>Total de la venta:</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>
            <div class="summary-row" style="color: #fd7e14; font-weight: 500;">
                <span>💸 Comisión plataforma (15%):</span>
                <span>
                    @if($order->platform_fee)
                        ${{ number_format($order->platform_fee, 2) }}
                    @else
                        ${{ number_format($order->total * 0.15, 2) }} (estimado)
                    @endif
                </span>
            </div>
            <div class="summary-row" style="border-top: 2px solid #e9ecef; margin-top: 10px; padding-top: 15px;">
                <span>Envío:</span>
                <span>Manejado por la plataforma</span>
            </div>
            <div class="summary-row total" style="background: #e8f5e8; padding: 15px; border-radius: 8px; margin-top: 10px;">
                <span style="color: #28a745; font-weight: 600;">💰 Tus ganancias netas:</span>
                <span style="color: #28a745; font-weight: 600;">
                    @if($order->seller_amount)
                        ${{ number_format($order->seller_amount, 2) }}
                    @else
                        ${{ number_format($order->total * 0.85, 2) }} (pendiente)
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Botón de regreso -->
    <a href="{{ route('tienda.ventas') }}" class="btn-back">
        <i class="fa fa-arrow-left"></i> Volver a Ventas
    </a>
</div>

@endsection