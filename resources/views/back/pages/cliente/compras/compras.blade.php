{{-- resources/views/back/pages/cliente/compras/compras.blade.php --}}
@extends('back.layout.pages-layout')
@section('PageTitle', 'Mis Pedidos')
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
    .orders-container {
        background: var(--bg-white);
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-light);
        font-family: var(--font-family);
    }

    /* Header simple con icono */
    .orders-header {
        display: flex;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-light);
    }

    .orders-header .icon {
        background: var(--bg-light);
        border-radius: 8px;
        padding: 12px;
        margin-right: 1rem;
        color: var(--text-secondary);
        font-size: 1.2rem;
    }

    .orders-header h1 {
        color: var(--text-primary);
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        font-family: var(--font-family);
    }

    /* Tabla simple y limpia */
    .orders-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
    }

    .orders-table thead th {
        color: var(--text-secondary);
        font-weight: 500;
        font-size: 0.875rem;
        padding: 1rem 0;
        text-align: left;
        border-bottom: 1px solid var(--border-light);
        font-family: var(--font-family);
    }

    .orders-table tbody tr {
        border-bottom: 1px solid var(--border-light);
        transition: background-color 0.2s ease;
    }

    .orders-table tbody tr:hover {
        background-color: #fafbfc;
    }

    .orders-table tbody td {
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

    /* Botón GO SHOP */
    .btn-go-shop {
        background-color: var(--text-primary);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 4px;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: var(--font-family);
    }

    .btn-go-shop:hover {
        background-color: #1a252f;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }

    .btn-go-shop i {
        font-size: 0.8rem;
    }

    /* Estado vacío simple */
    .empty-orders {
        text-align: center;
        padding: 3rem 2rem;
        color: var(--text-secondary);
    }

    .empty-orders i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--border-light);
    }

    .empty-orders h3 {
        color: var(--text-primary);
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-family: var(--font-family);
    }

    .empty-orders p {
        color: var(--text-secondary);
        margin-bottom: 2rem;
        font-family: var(--font-family);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .orders-container {
            padding: 1rem;
        }

        .orders-table {
            font-size: 0.875rem;
        }

        .orders-table thead {
            display: none;
        }

        .orders-table tbody tr {
            display: block;
            border: 1px solid var(--border-light);
            border-radius: var(--border-radius);
            margin-bottom: 1rem;
            padding: 1rem;
        }

        .orders-table tbody td {
            display: block;
            padding: 0.5rem 0;
            border: none;
        }

        .orders-table tbody td:before {
            content: attr(data-label) ": ";
            font-weight: 600;
            color: var(--text-secondary);
            display: inline-block;
            width: 80px;
        }
    }
</style>

<div class="orders-container">
    <!-- Header simple con icono -->
    <div class="orders-header">
        <div class="icon">
            <i class="fa fa-receipt"></i>
        </div>
        <h1>Orders</h1>
    </div>

    @if($compras && $compras->count() > 0)
        <!-- Tabla simple y limpia -->
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($compras as $compra)
                <tr>
                    <td class="order-number" data-label="Order">
                        #{{ $compra->id }}
                    </td>
                    <td class="order-date" data-label="Date">
                        {{ $compra->created_at->format('F j, Y') }}
                    </td>
                    <td data-label="Status">
                        <span class="order-status 
                            @if($compra->status === 'pending') status-pending
                            @elseif($compra->status === 'processing') status-processing  
                            @elseif($compra->status === 'completed') status-completed
                            @else status-cancelled
                            @endif">
                            @if($compra->status === 'pending')
                                Pending
                            @elseif($compra->status === 'processing')
                                Processing
                            @elseif($compra->status === 'completed')
                                Completed
                            @else
                                Cancelled
                            @endif
                        </span>
                    </td>
                    <td class="order-total" data-label="Total">
                        ${{ number_format($compra->total, 2) }}
                        @if($compra->items_count)
                            <span class="item-count">for {{ $compra->items_count }} item{{ $compra->items_count > 1 ? 's' : '' }}</span>
                        @endif
                    </td>
                    <td data-label="Actions">
                        <a href="{{ route('cliente.pedido.detalle', $compra->id) }}" class="btn-view">
                            VIEW
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Botón GO SHOP -->
        <a href="{{ route('productos.index') }}" class="btn-go-shop">
            <span>GO SHOP</span>
            <i class="fa fa-arrow-right"></i>
        </a>
    @else
        <!-- Estado vacío simple -->
        <div class="empty-orders">
            <i class="fa fa-shopping-bag"></i>
            <h3>No tienes compras todavia!</h3>
            <p>Explora nuestro catálogo y realiza tu primera compra.</p>
            <a href="{{ route('productos.index') }}" class="btn-go-shop">
                <span>GO SHOP</span>
                <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    @endif
</div>

@endsection