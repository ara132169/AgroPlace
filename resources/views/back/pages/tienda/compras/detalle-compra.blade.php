{{-- resources/views/back/pages/tienda/compras/detalle-compra.blade.php --}}
@extends('back.layout.pages-layout')
@section('PageTitle', 'Detalle de Compra #' . $order->id)
@section('content')

<style>
    .purchase-detail-container {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        border: 1px solid #e5e7eb;
    }

    .purchase-header {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .purchase-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="white" opacity="0.1"/><circle cx="80" cy="80" r="1" fill="white" opacity="0.1"/><circle cx="40" cy="60" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        pointer-events: none;
    }

    .purchase-header h2 {
        margin: 0;
        font-size: 1.875rem;
        font-weight: 700;
        position: relative;
        z-index: 1;
    }

    .purchase-header p {
        margin: 0.75rem 0 0 0;
        opacity: 0.9;
        font-size: 1rem;
        position: relative;
        z-index: 1;
    }

    .order-status {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 0.75rem;
        position: relative;
        z-index: 1;
        letter-spacing: 0.5px;
    }

    .status-pendiente {
        background: rgba(251, 191, 36, 0.2);
        color: #92400e;
        border: 1px solid rgba(251, 191, 36, 0.3);
    }

    .status-confirmed {
        background: rgba(37, 99, 235, 0.2);
        color: #1e40af;
        border: 1px solid rgba(37, 99, 235, 0.3);
    }

    .status-cancelled {
        background: rgba(239, 68, 68, 0.2);
        color: #991b1b;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .info-section {
        background: linear-gradient(145deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        border-left: 4px solid #2563eb;
        border: 1px solid #e2e8f0;
    }

    .info-section h4 {
        color: #2563eb;
        margin-bottom: 1rem;
        font-weight: 700;
        font-size: 1.125rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .info-row {
        display: flex;
        margin-bottom: 0.75rem;
        align-items: center;
    }

    .info-label {
        font-weight: 600;
        color: #374151;
        min-width: 140px;
        margin-right: 0.75rem;
        font-size: 0.875rem;
    }

    .info-value {
        color: #111827;
        font-size: 0.875rem;
    }
        width: 120px;
        margin-right: 10px;
    }

    .info-value {
        color: #212529;
    }

    .purchase-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 2rem;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
    }

    .purchase-table thead th {
        background: linear-gradient(145deg, #f9fafb 0%, #f3f4f6 100%);
        color: #374151;
        padding: 1rem 1.25rem;
        font-weight: 700;
        text-align: left;
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .purchase-table tbody td {
        padding: 1.25rem;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
        background: white;
        transition: background-color 0.2s ease;
    }

    .purchase-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .purchase-table tbody tr:last-child td {
        border-bottom: none;
    }

    .product-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .product-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        flex-shrink: 0;
    }

    .product-details h6 {
        margin: 0 0 0.25rem 0;
        font-size: 0.875rem;
        font-weight: 700;
        color: #111827;
        line-height: 1.4;
    }

    .product-details p {
        margin: 0;
        font-size: 0.75rem;
        color: #6b7280;
    }

    .price-cell {
        font-weight: 700;
        color: #2563eb;
        font-size: 1rem;
    }

    .quantity-badge {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        padding: 0.375rem 0.75rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        min-width: 40px;
        text-align: center;
    }

    .seller-info {
        background: linear-gradient(145deg, #dbeafe 0%, #bfdbfe 100%);
        padding: 0.75rem;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        border: 1px solid #93c5fd;
    }

    .seller-info h6 {
        margin: 0 0 0.25rem 0;
        color: #2563eb;
        font-weight: 700;
        font-size: 0.875rem;
    }

    .seller-info p {
        margin: 0;
        font-size: 0.75rem;
        color: #475569;
    }

    .purchase-summary {
        background: linear-gradient(145deg, #eff6ff 0%, #dbeafe 100%);
        padding: 1.5rem;
        border-radius: 12px;
        border-left: 4px solid #2563eb;
        margin-bottom: 2rem;
        border: 1px solid #93c5fd;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        padding: 0.5rem 0;
        font-size: 0.875rem;
    }

    .summary-row.total {
        border-top: 2px solid #2563eb;
        padding-top: 1rem;
        margin-top: 1rem;
        font-weight: 700;
        font-size: 1.125rem;
        color: #2563eb;
    }

    .vendors-summary {
        background: linear-gradient(145deg, #fef3c7 0%, #fde68a 100%);
        padding: 1.5rem;
        border-radius: 12px;
        border-left: 4px solid #f59e0b;
        margin-bottom: 2rem;
        border: 1px solid #fcd34d;
    }

    .vendor-card {
        background: white;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .vendor-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }

    .vendor-card:last-child {
        margin-bottom: 0;
    }

    .vendor-name {
        font-weight: 700;
        color: #f59e0b;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .btn-back {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        padding: 0.875rem 1.5rem;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        margin-top: 1.5rem;
        font-size: 0.875rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .btn-back:hover {
        background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }

    .btn-back i {
        margin-right: 0.5rem;
    }
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

    .purchase-summary {
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
        .purchase-detail-container {
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .purchase-header {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .purchase-header h2 {
            font-size: 1.5rem;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .purchase-table {
            font-size: 0.875rem;
        }
        
        .purchase-table thead {
            display: none;
        }
        
        .purchase-table tbody tr {
            display: block;
            border: 1px solid #e5e7eb;
            margin-bottom: 1rem;
            border-radius: 8px;
            padding: 1rem;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        
        .purchase-table tbody td {
            display: block;
            padding: 0.5rem 0;
            border: none;
            text-align: left;
        }
        
        .purchase-table tbody td:before {
            content: attr(data-label) ": ";
            font-weight: 700;
            color: #2563eb;
            display: inline-block;
            width: 100%;
            margin-bottom: 0.25rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .product-info {
            flex-direction: column;
            text-align: center;
            gap: 0.75rem;
        }
        
        .product-image {
            align-self: center;
        }
        
        .info-section {
            padding: 1rem;
        }
        
        .purchase-summary, .vendors-summary {
            padding: 1rem;
        }
        
        .vendor-card {
            padding: 0.75rem;
        }
    }

    @media (max-width: 480px) {
        .purchase-header h2 {
            font-size: 1.25rem;
        }
        
        .purchase-header p {
            font-size: 0.875rem;
        }
        
        .btn-back {
            width: 100%;
            justify-content: center;
        }
    }
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

<div class="purchase-detail-container">
    <!-- Header de la compra -->
    <div class="purchase-header">
        <h2>Compra #{{ $order->id }}</h2>
        <p>Pedido realizado el {{ \Carbon\Carbon::parse($order->created_at)->format('j \d\e F \d\e Y \a \l\a\s g:i A') }}</p>
        <span class="order-status 
            @if($order->status === 'pendiente') status-pendiente
            @elseif($order->status === 'pagado') status-pagado  
            @elseif($order->status === 'enviado') status-enviado  
            @elseif($order->status === 'completado') status-completado
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

    <!-- Información de entrega -->
    <div class="info-grid">
        <div class="info-section">
            <h4><i class="fa fa-truck"></i> Información de Entrega</h4>
            <div class="info-row">
                <span class="info-label">Nombre:</span>
                <span class="info-value">{{ $order->shipping_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $order->shipping_email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Teléfono:</span>
                <span class="info-value">{{ $order->shipping_phone }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Dirección:</span>
                <span class="info-value">{{ $order->shipping_address }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Ciudad:</span>
                <span class="info-value">{{ $order->shipping_city }}, {{ $order->shipping_state }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">CP:</span>
                <span class="info-value">{{ $order->shipping_cp }}</span>
            </div>
        </div>

        <div class="info-section">
            <h4><i class="fa fa-info-circle"></i> Estado del Pedido</h4>
            <div class="info-row">
                <span class="info-label">Estado:</span>
                <span class="info-value">
                    @if($order->status === 'pendiente')
                        Preparando pedido
                    @elseif($order->status === 'pagado')
                        Pagado - Preparando envío
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
            <i class="fa fa-store"></i> Vendedores de tu Pedido
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
    <div class="purchase-details-wrapper">
        <h4 class="mb-4" style="color: #28a745; font-weight: 600;">
            <i class="fa fa-box"></i> Productos Comprados
        </h4>
        
        <div class="table-responsive">
            <table class="purchase-table">
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
        <div class="purchase-summary">
            <h5 style="color: #28a745; margin-bottom: 15px;">
                <i class="fa fa-calculator"></i> Resumen de tu Compra
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
            <div class="summary-row total">
                <span>Total pagado:</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Botón de regreso -->
    <a href="{{ route('tienda.compras') }}" class="btn-back">
        <i class="fa fa-arrow-left"></i> Volver a Mis Compras
    </a>
</div>

@endsection