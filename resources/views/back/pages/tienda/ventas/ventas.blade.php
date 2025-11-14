{{-- resources/views/back/pages/tienda/ventas/ventas.blade.php --}}
@extends('back.layout.pages-layout')
@section('PageTitle', 'Mis Ventas')
@section('content')

<style>
    /* Variables CSS para diseño limpio y simple */
    :root {
        --primary-color: #1254a1;
        --text-primary: #2c3e50;
        --text-secondary: #7f8c8d;
        --border-light: #e8ecef;
        --bg-white: #ffffff;
        --bg-light: #f8f9fa;
        --font-family: 'Inter', sans-serif;
        --shadow-light: 0 1px 3px rgba(0,0,0,0.1);
        --border-radius: 8px;
    }

    /* Contenedor principal simple */
    .sales-container {
        background: var(--bg-white);
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-light);
        font-family: var(--font-family);
    }

    /* Header simple con icono */
    .sales-header {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-light);
    }

    .sales-header .icon {
        background: var(--bg-light);
        border-radius: 8px;
        padding: 12px;
        margin-right: 1rem;
        color: var(--text-secondary);
        font-size: 1.2rem;
    }

    .sales-header h1 {
        color: var(--text-primary);
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        font-family: var(--font-family);
    }

    /* Tabla simple y limpia */
    .sales-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
    }

    .sales-table thead th {
        color: var(--text-secondary);
        font-weight: 500;
        font-size: 0.875rem;
        padding: 1rem 0;
        text-align: left;
        border-bottom: 1px solid var(--border-light);
        font-family: var(--font-family);
    }

    .sales-table tbody tr {
        border-bottom: 1px solid var(--border-light);
        transition: background-color 0.2s ease;
    }

    .sales-table tbody tr:hover {
        background-color: #fafbfc;
    }

    .sales-table tbody td {
        padding: 1.5rem 0;
        color: var(--text-primary);
        font-size: 0.9rem;
        font-family: var(--font-family);
    }

    /* Estilos específicos de columnas */
    .order-number {
        color: var(--primary-color);
        font-weight: 600;
    }

    .order-date {
        color: var(--text-secondary);
    }

    .customer-name {
        font-weight: 500;
        color: var(--text-primary);
    }

    .order-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: capitalize;
    }

    .status-processing {
        background-color: #e3f2fd;
        color: #1976d2;
    }

    .status-pending {
        background-color: #fff3e0;
        color: #f57c00;
    }

    .status-completed {
        background-color: #e8f5e8;
        color: #2e7d32;
    }

    .status-cancelled {
        background-color: #ffebee;
        color: #c62828;
    }

    .order-total {
        font-weight: 600;
        color: var(--text-primary);
    }

    .item-count {
        color: var(--text-secondary);
        font-size: 0.8rem;
        margin-left: 0.5rem;
    }

    /* Estilos para columnas de comisiones */
    .seller-amount {
        text-align: center;
    }
    
    .amount-received {
        color: #28a745;
        font-weight: 600;
        font-size: 1rem;
    }
    
    .amount-pending {
        color: #6c757d;
        font-weight: 500;
    }
    
    .commission-info {
        text-align: center;
    }
    
    .commission-amount {
        font-weight: 500;
        color: #fd7e14;
    }
    
    .commission-pending {
        font-style: italic;
    }

    /* Botón VIEW simple */
    .btn-view {
        background: transparent;
        border: 1px solid var(--border-light);
        color: var(--text-secondary);
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 500;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
        font-family: var(--font-family);
    }

    .btn-view:hover {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        text-decoration: none;
    }

    /* Estado vacío simple */
    .empty-sales {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
    }

    .empty-sales i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--border-light);
    }

    .empty-sales h3 {
        color: var(--text-primary);
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-family: var(--font-family);
    }

    .empty-sales p {
        color: var(--text-secondary);
        margin-bottom: 2rem;
        font-family: var(--font-family);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sales-container {
            padding: 1rem;
        }

        .sales-table {
            font-size: 0.875rem;
        }

        .sales-table thead {
            display: none;
        }

        .sales-table tbody tr {
            display: block;
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
            padding: 1rem;
        }

        .sales-table tbody td {
            display: block;
            padding: 0.5rem 0;
            border: none;
        }

        .sales-table tbody td:before {
            content: attr(data-label) ": ";
            font-weight: 600;
            color: var(--text-secondary);
            display: inline-block;
            width: 80px;
        }
    }
</style>

<div class="sales-container">
    <!-- Header simple con icono -->
    <div class="sales-header">
        <div class="icon">
            <i class="fa fa-chart-line"></i>
        </div>
        <h1>Ventas</h1>
    </div>

    @if($ventas && $ventas->count() > 0)
        <!-- Tabla simple y limpia -->
        <table class="sales-table">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Productos</th>
                    <th>Estado</th>
                    <th>Total Venta</th>
                    <th>💰 Tu Ganancia</th>
                    <th>📊 Comisión</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $venta)
                <tr>
                    <td class="order-number" data-label="Pedido">
                        #{{ $venta->id }}
                    </td>
                    <td class="order-date" data-label="Fecha">
                        {{ $venta->created_at->format('j M Y') }}
                    </td>
                    <td class="customer-name" data-label="Cliente">
                        {{ $venta->client_name }}
                    </td>
                    <td data-label="Productos">
                        <span class="item-count">{{ $venta->items_count }} producto{{ $venta->items_count > 1 ? 's' : '' }}</span>
                    </td>
                    <td data-label="Estado">
                        <span class="order-status 
                            @if($venta->status === 'pending') status-pending
                            @elseif($venta->status === 'processing') status-processing  
                            @elseif($venta->status === 'completed') status-completed
                            @else status-cancelled
                            @endif">
                            @if($venta->status === 'pending')
                                Pendiente
                            @elseif($venta->status === 'processing')
                                Procesando
                            @elseif($venta->status === 'completed')
                                Completado
                            @else
                                Cancelado
                            @endif
                        </span>
                    </td>
                    <td class="order-total" data-label="Total Venta">
                        ${{ number_format($venta->total, 2) }}
                    </td>
                    <td class="seller-amount" data-label="💰 Tu Ganancia">
                        @if($venta->seller_amount)
                            <span class="amount-received">${{ number_format($venta->seller_amount, 2) }}</span>
                            <small class="text-success d-block">(85% del total)</small>
                        @else
                            <span class="amount-pending">${{ number_format($venta->total * 0.85, 2) }}</span>
                            <small class="text-muted d-block">(Pendiente)</small>
                        @endif
                    </td>
                    <td class="commission-info" data-label="📊 Comisión">
                        @if($venta->platform_fee)
                            <span class="commission-amount text-warning">${{ number_format($venta->platform_fee, 2) }}</span>
                            <small class="text-muted d-block">(15% retenido)</small>
                        @else
                            <span class="commission-pending text-muted">${{ number_format($venta->total * 0.15, 2) }}</span>
                            <small class="text-muted d-block">(15% se retendrá)</small>
                        @endif
                    </td>
                    <td data-label="Acciones">
                        <a href="{{ route('tienda.venta.detalle', $venta->id) }}" class="btn-view">
                            VER
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <!-- Estado vacío simple -->
        <div class="empty-sales">
            <i class="fa fa-chart-line"></i>
            <h3>¡Aún no tienes ventas!</h3>
            <p>No has vendido ningún producto todavía. ¡Sigue promocionando tus productos!</p>
        </div>
    @endif
</div>

@endsection