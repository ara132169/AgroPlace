{{-- resources/views/back/pages/admin/ventas/todas-ventas.blade.php --}}
@extends('back.layout.pages-layout')
@section('PageTitle', 'Todas las Ventas')
@section('content')

<style>
    /* Variables CSS para diseño limpio y simple */
    :root {
        --primary-color: #1254a1;
        --text-primary: #2c3e50;
        --text-secondary: #6c757d;
        --bg-white: #ffffff;
        --bg-light: #f8f9fa;
        --border-light: #e8ecef;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --border-radius: 8px;
        --font-family: 'Inter', sans-serif;
    }

    .sales-container {
        background: var(--bg-white);
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        font-family: var(--font-family);
    }

    .sales-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-light);
    }

    .sales-header .icon {
        background: var(--bg-light);
        border-radius: 8px;
        padding: 0.75rem;
        color: var(--primary-color);
        font-size: 1.5rem;
    }

    .sales-header h1 {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.75rem;
        font-weight: 600;
        font-family: var(--font-family);
    }

    .sales-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--bg-light);
        padding: 1.5rem;
        border-radius: var(--border-radius);
        text-align: center;
        border-left: 4px solid var(--primary-color);
    }

    .stat-card h3 {
        margin: 0;
        color: var(--primary-color);
        font-size: 1.5rem;
        font-weight: 600;
    }

    .stat-card p {
        margin: 0.5rem 0 0 0;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    /* Estilos para estadísticas de comisiones */
    .commission-stats {
        background: var(--bg-light);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-light);
    }

    .commission-header {
        margin-bottom: 1rem;
        text-align: center;
    }

    .commission-header h4 {
        margin: 0;
        color: var(--text-primary);
        font-size: 1.2rem;
        font-weight: 600;
    }

    .commission-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
    }

    .commission-card {
        background: var(--bg-white);
        padding: 1.25rem;
        border-radius: var(--border-radius);
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border: 1px solid var(--border-light);
    }

    .commission-card.earned {
        border-left: 4px solid var(--success-color);
    }

    .commission-card.pending {
        border-left: 4px solid var(--warning-color);
    }

    .commission-card.total {
        border-left: 4px solid var(--primary-color);
    }

    .commission-card.percentage {
        border-left: 4px solid #6f42c1;
    }

    .commission-card h3 {
        margin: 0 0 0.5rem 0;
        font-size: 1.4rem;
        font-weight: 600;
    }

    .commission-card.earned h3 {
        color: var(--success-color);
    }

    .commission-card.pending h3 {
        color: var(--warning-color);
    }

    .commission-card.total h3 {
        color: var(--primary-color);
    }

    .commission-card.percentage h3 {
        color: #6f42c1;
    }

    .commission-card p {
        margin: 0;
        color: var(--text-secondary);
        font-size: 0.85rem;
        font-weight: 500;
    }

    .sales-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
        font-family: var(--font-family);
        font-size: 0.85rem;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .sales-table thead th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 0.5rem;
        font-weight: 600;
        text-align: left;
        font-size: 0.8rem;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .sales-table tbody tr {
        border-bottom: 1px solid #f1f3f4;
        transition: all 0.2s ease;
    }

    .sales-table tbody tr:hover {
        background-color: #f8f9ff;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .sales-table tbody td {
        padding: 0.6rem 0.5rem;
        vertical-align: middle;
        color: var(--text-primary);
    }

    /* Estilos compactos para columnas específicas */
    .sales-table .col-id {
        width: 80px;
        font-weight: 600;
        color: var(--primary-color);
    }

    .sales-table .col-date {
        width: 90px;
        font-size: 0.8rem;
    }

    .sales-table .col-buyer {
        width: 160px;
    }

    .sales-table .col-type {
        width: 80px;
        text-align: center;
    }

    .sales-table .col-sellers {
        width: 150px;
    }

    .sales-table .col-items {
        width: 70px;
        text-align: center;
    }

    .sales-table .col-status {
        width: 100px;
        text-align: center;
    }

    .sales-table .col-total {
        width: 90px;
        text-align: right;
        font-weight: 600;
    }

    .sales-table .col-seller-amount {
        width: 100px;
        text-align: right;
    }

    .sales-table .col-commission {
        width: 100px;
        text-align: right;
    }

    .sales-table .col-actions {
        width: 80px;
        text-align: center;
    }

    .order-status {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .status-processing {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Badges compactos */
    .badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 10px;
        font-size: 0.7rem;
        font-weight: 600;
        text-align: center;
        white-space: nowrap;
    }

    .badge-primary {
        background: #e3f2fd;
        color: #1976d2;
        border: 1px solid #bbdefb;
    }

    .badge-success {
        background: #e8f5e8;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }

    .badge-info {
        background: #e1f5fe;
        color: #0277bd;
        border: 1px solid #b3e5fc;
    }

    /* Seller tags más pequeños */
    .seller-tag {
        display: inline-block;
        background: #f1f3f4;
        color: #5f6368;
        padding: 2px 6px;
        border-radius: 8px;
        font-size: 0.7rem;
        margin-right: 4px;
        margin-bottom: 2px;
        font-weight: 500;
    }

    /* Botón de acción más compacto */
    .btn-view {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
        display: inline-block;
    }

    .btn-view:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
        color: white;
        text-decoration: none;
    }

    /* Montos y comisiones */
    .amount-success {
        color: #2e7d32;
        font-weight: 600;
    }

    .amount-warning {
        color: #f57c00;
        font-weight: 600;
    }

    .amount-primary {
        color: #1976d2;
        font-weight: 600;
    }

    .amount-small {
        font-size: 0.7rem;
        opacity: 0.8;
        display: block;
        margin-top: 2px;
    }

    /* Información del comprador más compacta */
    .buyer-info {
        line-height: 1.2;
    }

    .buyer-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.8rem;
    }

    .buyer-email {
        color: #6c757d;
        font-size: 0.7rem;
        margin-top: 2px;
    }
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

    .btn-view {
        background: var(--primary-color);
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s ease;
        font-family: var(--font-family);
    }

    .btn-view:hover {
        background: #0e4082;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }

    .seller-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }

    .seller-tag {
        background: var(--primary-color);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-primary {
        background: #007bff;
        color: white;
    }

    .badge-success {
        background: #28a745;
        color: white;
    }

    .badge-info {
        background: #17a2b8;
        color: white;
    }

    .empty-sales {
        text-align: center;
        padding: 3rem 1rem;
        color: var(--text-secondary);
        font-family: var(--font-family);
    }

    .empty-sales i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: var(--text-secondary);
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

    /* Estilos para columnas de comisiones */
    .commission-cell {
        text-align: center;
        font-size: 0.9rem;
    }
    
    .commission-earned {
        color: var(--success-color);
        font-weight: 600;
    }
    
    .commission-pending {
        color: var(--primary-color);
        font-weight: 500;
    }
    
    .seller-payment {
        text-align: center;
    }
    
    .payment-completed {
        color: var(--success-color);
    }
    
    .payment-pending {
        color: var(--warning-color);
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

        .seller-tags {
            margin-top: 0.5rem;
        }
    }

    /* Estilos para el formulario de búsqueda y filtros */
    .search-filters-container {
        background: var(--bg-light);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid var(--border-light);
    }

    .search-filters-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
        color: var(--text-primary);
        font-weight: 600;
    }

    .search-filters-header i {
        color: var(--primary-color);
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .filter-group input,
    .filter-group select {
        padding: 0.5rem;
        border: 1px solid var(--border-light);
        border-radius: 4px;
        font-size: 0.9rem;
        background: white;
        transition: border-color 0.2s ease;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(18, 84, 161, 0.1);
    }

    .search-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .btn-search {
        background: var(--primary-color);
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-search:hover {
        background: #0f3f73;
    }

    .btn-clear {
        background: var(--text-secondary);
        color: white;
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-clear:hover {
        background: #5a6268;
    }

    .active-filters {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-light);
    }

    .filter-tag {
        display: inline-block;
        background: var(--primary-color);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .filter-tag .close {
        margin-left: 0.5rem;
        cursor: pointer;
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .filters-grid {
            grid-template-columns: 1fr;
        }
        
        .search-actions {
            justify-content: stretch;
        }
        
        .btn-search,
        .btn-clear {
            flex: 1;
            justify-content: center;
        }
    }
</style>

<div class="sales-container">
    <!-- Header simple con icono -->
    <div class="sales-header">
        <div class="icon">
            <i class="fa fa-chart-bar"></i>
        </div>
        <h1>Todas las Ventas de la Plataforma</h1>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="sales-stats">
        <div class="stat-card">
            <h3>{{ $ventas->total() }}</h3>
            <p>Total de Ventas</p>
        </div>
        <div class="stat-card">
            <h3>${{ number_format($ventas->sum('total'), 2) }}</h3>
            <p>Ingresos Totales</p>
        </div>
        <div class="stat-card">
            <h3>{{ $ventas->where('status', 'completado')->count() }}</h3>
            <p>Ventas Completadas</p>
        </div>
        <div class="stat-card">
            <h3>{{ $ventas->where('status', 'pending')->count() }}</h3>
            <p>Ventas Pendientes</p>
        </div>
    </div>

    <!-- Estadísticas de comisiones -->
    <div class="commission-stats">
        <div class="commission-header">
            <h4><i class="fa fa-chart-pie text-success"></i> Resumen de Comisiones (15%)</h4>
        </div>
        <div class="commission-grid">
            <div class="commission-card earned">
                @php
                    $comisionesCobradas = $ventas->whereNotNull('platform_fee')->sum('platform_fee');
                @endphp
                <h3>${{ number_format($comisionesCobradas, 2) }}</h3>
                <p>✅ Comisiones Cobradas</p>
            </div>
            <div class="commission-card pending">
                @php
                    $comisionesPendientes = $ventas->whereNull('platform_fee')->sum(function($venta) {
                        return $venta->total * 0.15;
                    });
                @endphp
                <h3>${{ number_format($comisionesPendientes, 2) }}</h3>
                <p>⏳ Comisiones Pendientes</p>
            </div>
            <div class="commission-card total">
                @php
                    $comisionesTotal = $comisionesCobradas + $comisionesPendientes;
                @endphp
                <h3>${{ number_format($comisionesTotal, 2) }}</h3>
                <p>💰 Total Comisiones</p>
            </div>
            <div class="commission-card percentage">
                @php
                    $porcentajeCobrado = $comisionesTotal > 0 ? ($comisionesCobradas / $comisionesTotal * 100) : 0;
                @endphp
                <h3>{{ number_format($porcentajeCobrado, 1) }}%</h3>
                <p>📊 Porcentaje Cobrado</p>
            </div>
        </div>
    </div>

    <!-- Formulario de búsqueda y filtros -->
    <div class="search-filters-container">
        <div class="search-filters-header">
            <i class="fa fa-search"></i>
            <span>Buscar y Filtrar Ventas</span>
        </div>
        
        <form method="GET" action="{{ route('admin.ventas') }}">>
            <div class="filters-grid">
                <!-- Búsqueda general -->
                <div class="filter-group">
                    <label for="search">Búsqueda General</label>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Buscar por orden, cliente, vendedor..."
                    >
                </div>

                <!-- Número de orden específico -->
                <div class="filter-group">
                    <label for="order_id">Número de Orden</label>
                    <input 
                        type="number" 
                        id="order_id" 
                        name="order_id" 
                        value="{{ request('order_id') }}" 
                        placeholder="Ej: 123"
                    >
                </div>

                <!-- Filtro por vendedor -->
                <div class="filter-group">
                    <label for="seller_filter">Vendedor</label>
                    <input 
                        type="text" 
                        id="seller_filter" 
                        name="seller_filter" 
                        value="{{ request('seller_filter') }}" 
                        placeholder="Nombre del vendedor"
                    >
                </div>

                <!-- Filtro por comprador -->
                <div class="filter-group">
                    <label for="buyer_filter">Comprador</label>
                    <input 
                        type="text" 
                        id="buyer_filter" 
                        name="buyer_filter" 
                        value="{{ request('buyer_filter') }}" 
                        placeholder="Nombre del comprador"
                    >
                </div>

                <!-- Tipo de comprador -->
                <div class="filter-group">
                    <label for="buyer_type">Tipo de Comprador</label>
                    <select id="buyer_type" name="buyer_type">
                        <option value="">Todos los tipos</option>
                        <option value="client" {{ request('buyer_type') == 'client' ? 'selected' : '' }}>Cliente</option>
                        <option value="seller" {{ request('buyer_type') == 'seller' ? 'selected' : '' }}>Vendedor</option>
                    </select>
                </div>

                <!-- Estado de la orden -->
                <div class="filter-group">
                    <label for="status">Estado</label>
                    <select id="status" name="status">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Procesando</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Enviado</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Entregado</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                </div>

                <!-- Fecha desde -->
                <div class="filter-group">
                    <label for="date_from">Fecha Desde</label>
                    <input 
                        type="date" 
                        id="date_from" 
                        name="date_from" 
                        value="{{ request('date_from') }}"
                    >
                </div>

                <!-- Fecha hasta -->
                <div class="filter-group">
                    <label for="date_to">Fecha Hasta</label>
                    <input 
                        type="date" 
                        id="date_to" 
                        name="date_to" 
                        value="{{ request('date_to') }}"
                    >
                </div>
            </div>

            <div class="search-actions">
                <button type="submit" class="btn-search">
                    <i class="fa fa-search"></i>
                    Buscar
                </button>
                <a href="{{ route('admin.ventas') }}" class="btn-clear">
                    <i class="fa fa-times"></i>
                    Limpiar Filtros
                </a>
            </div>
        </form>

        @if(request()->hasAny(['search', 'order_id', 'seller_filter', 'buyer_filter', 'buyer_type', 'status', 'date_from', 'date_to']))
            <div class="active-filters">
                <strong>Filtros activos:</strong>
                @if(request('search'))
                    <span class="filter-tag">
                        Búsqueda: {{ request('search') }}
                        <span class="close" onclick="removeFilter('search')">&times;</span>
                    </span>
                @endif
                @if(request('order_id'))
                    <span class="filter-tag">
                        Orden: #{{ request('order_id') }}
                        <span class="close" onclick="removeFilter('order_id')">&times;</span>
                    </span>
                @endif
                @if(request('seller_filter'))
                    <span class="filter-tag">
                        Vendedor: {{ request('seller_filter') }}
                        <span class="close" onclick="removeFilter('seller_filter')">&times;</span>
                    </span>
                @endif
                @if(request('buyer_filter'))
                    <span class="filter-tag">
                        Comprador: {{ request('buyer_filter') }}
                        <span class="close" onclick="removeFilter('buyer_filter')">&times;</span>
                    </span>
                @endif
                @if(request('buyer_type'))
                    <span class="filter-tag">
                        Tipo: {{ request('buyer_type') == 'client' ? 'Cliente' : 'Vendedor' }}
                        <span class="close" onclick="removeFilter('buyer_type')">&times;</span>
                    </span>
                @endif
                @if(request('status'))
                    <span class="filter-tag">
                        Estado: {{ ucfirst(request('status')) }}
                        <span class="close" onclick="removeFilter('status')">&times;</span>
                    </span>
                @endif
                @if(request('date_from'))
                    <span class="filter-tag">
                        Desde: {{ request('date_from') }}
                        <span class="close" onclick="removeFilter('date_from')">&times;</span>
                    </span>
                @endif
                @if(request('date_to'))
                    <span class="filter-tag">
                        Hasta: {{ request('date_to') }}
                        <span class="close" onclick="removeFilter('date_to')">&times;</span>
                    </span>
                @endif
            </div>
        @endif
    </div>

    @if($ventas && $ventas->count() > 0)
        <!-- Tabla optimizada y compacta -->
        <table class="sales-table">
            <thead>
                <tr>
                    <th class="col-id">ID</th>
                    <th class="col-date">Fecha</th>
                    <th class="col-buyer">Comprador</th>
                    <th class="col-type">Tipo</th>
                    <th class="col-sellers">Vendedores</th>
                    <th class="col-items">Items</th>
                    <th class="col-status">Estado</th>
                    <th class="col-total">Total</th>
                    <th class="col-seller-amount">Al Vendedor</th>
                    <th class="col-commission">Comisión</th>
                    <th class="col-actions">Ver</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $venta)
                <tr>
                    <td class="col-id" data-label="ID">
                        #{{ $venta->id }}
                    </td>
                    <td class="col-date" data-label="Fecha">
                        {{ \Carbon\Carbon::parse($venta->created_at)->format('j M') }}
                    </td>
                    <td class="col-buyer" data-label="Comprador">
                        <div class="buyer-info">
                            <div class="buyer-name">{{ Str::limit($venta->client_name, 20) }}</div>
                            <div class="buyer-email">{{ Str::limit($venta->client_email, 25) }}</div>
                        </div>
                    </td>
                    <td class="col-type" data-label="Tipo">
                        <span class="badge badge-{{ $venta->buyer_type === 'client' ? 'primary' : 'success' }}">
                            {{ $venta->buyer_type === 'client' ? 'Cliente' : 'Vendor' }}
                        </span>
                    </td>
                    <td class="col-sellers" data-label="Vendedores">
                        <div class="seller-tags">
                            @if($venta->vendedores)
                                @foreach(array_slice(explode(',', $venta->vendedores), 0, 2) as $vendedor)
                                    <span class="seller-tag">{{ Str::limit(trim($vendedor), 12) }}</span>
                                @endforeach
                                @if(count(explode(',', $venta->vendedores)) > 2)
                                    <span class="seller-tag">+{{ count(explode(',', $venta->vendedores)) - 2 }}</span>
                                @endif
                            @else
                                <span class="text-muted" style="font-size: 0.7rem;">N/A</span>
                            @endif
                        </div>
                    </td>
                    <td class="col-items" data-label="Items">
                        <span class="badge badge-info">{{ $venta->items_count }}</span>
                    </td>
                    <td class="col-status" data-label="Estado">
                        <span class="order-status 
                            @if($venta->status === 'pendiente') status-pending
                            @elseif($venta->status === 'pagado') status-processing  
                            @elseif($venta->status === 'enviado') status-processing  
                            @elseif($venta->status === 'completado') status-completed
                            @else status-cancelled
                            @endif">
                            @if($venta->status === 'pendiente')
                                Pend
                            @elseif($venta->status === 'pagado')
                                Pago
                            @elseif($venta->status === 'enviado')
                                Env
                            @elseif($venta->status === 'completado')
                                Ok
                            @else
                                X
                            @endif
                        </span>
                    </td>
                    <td class="col-total" data-label="Total">
                        ${{ number_format($venta->total, 0) }}
                    </td>
                    <td class="col-seller-amount" data-label="Al Vendedor">
                        @if($venta->seller_amount)
                            <span class="amount-success">${{ number_format($venta->seller_amount, 0) }}</span>
                            <small class="amount-small">✓ 85%</small>
                        @else
                            <span class="amount-warning">${{ number_format($venta->total * 0.85, 0) }}</span>
                            <small class="amount-small">⏳ 85%</small>
                        @endif
                    </td>
                    <td class="col-commission" data-label="Comisión">
                        @if($venta->platform_fee)
                            <span class="amount-success">${{ number_format($venta->platform_fee, 0) }}</span>
                            <small class="amount-small">✓ 15%</small>
                        @else
                            <span class="amount-primary">${{ number_format($venta->total * 0.15, 0) }}</span>
                            <small class="amount-small">⏳ 15%</small>
                        @endif
                    </td>
                    <td class="col-actions" data-label="Ver">
                        <a href="{{ route('admin.venta.detalle', $venta->id) }}" class="btn-view">
                            Ver
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="d-flex justify-content-center">
            {{ $ventas->links() }}
        </div>
    @else
        <!-- Estado vacío mejorado -->
        <div class="empty-sales">
            <div style="text-align: center; padding: 3rem 1rem; color: #6c757d;">
                <i class="fa fa-chart-bar" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <h4 style="margin-bottom: 0.5rem; color: #495057;">No hay ventas que mostrar</h4>
                <p style="margin: 0; font-size: 0.9rem;">Intenta ajustar los filtros o espera a que se realicen nuevas ventas.</p>
            </div>
        </div>
    @endif

    <!-- Paginación mejorada -->
    @if($ventas && $ventas->hasPages())
        <div style="display: flex; justify-content: center; margin-top: 2rem;">
            <div class="custom-pagination">
                {{ $ventas->appends(request()->query())->links() }}
            </div>
        </div>
    @endif

</div>

<!-- CSS adicional para controlar iconos de paginación -->
<style>
    /* Sobrescribir estilos de paginación de Laravel */
    .custom-pagination nav {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .custom-pagination .flex {
        justify-content: center !important;
        align-items: center;
        gap: 0.25rem;
    }

    .custom-pagination .flex-1 {
        display: none; /* Ocultar texto "Showing 1 to 15..." */
    }

    .custom-pagination nav div:first-child p {
        display: none; /* Ocultar texto de resultados */
    }

    /* Estilos específicos para los enlaces de paginación */
    .custom-pagination a,
    .custom-pagination span {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        padding: 6px 12px !important;
        margin: 0 2px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        background-color: white;
        color: #6c757d !important;
        text-decoration: none;
        font-size: 0.8rem !important;
        min-width: 35px;
        height: 35px;
        transition: all 0.2s ease;
    }

    .custom-pagination a:hover {
        background-color: #f8f9fa !important;
        border-color: #adb5bd !important;
        color: #495057 !important;
        transform: translateY(-1px);
    }

    .custom-pagination .bg-blue-600,
    .custom-pagination .bg-indigo-600,
    .custom-pagination [aria-current="page"] {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: white !important;
    }

    .custom-pagination .text-gray-500 {
        background-color: #f8f9fa !important;
        color: #adb5bd !important;
        cursor: not-allowed;
    }

    /* Controlar iconos SVG específicamente */
    .custom-pagination svg {
        width: 14px !important;
        height: 14px !important;
        fill: currentColor;
    }

    /* Ocultar texto "Previous" y "Next" si existe */
    .custom-pagination .hidden {
        display: none !important;
    }

    /* Centrar completamente la navegación */
    .custom-pagination nav > div {
        margin: 0 auto;
        display: flex;
        justify-content: center;
    }

    .custom-pagination .sm\\:flex-1 {
        display: none !important;
    }

    .custom-pagination .sm\\:flex {
        display: flex !important;
        justify-content: center !important;
    }

    /* Forzar tamaño pequeño de iconos SVG */
    .custom-pagination svg,
    nav[aria-label="Pagination Navigation"] svg {
        width: 12px !important;
        height: 12px !important;
        fill: currentColor !important;
    }

    /* Estilo adicional para asegurar iconos pequeños */
    nav[role="navigation"] svg {
        width: 12px !important;
        height: 12px !important;
    }

    /* Hacer la paginación más compacta */
    nav[role="navigation"] span,
    nav[role="navigation"] a {
        padding: 4px 8px !important;
        font-size: 0.8rem !important;
        min-width: 30px !important;
        height: 30px !important;
    }
</style>

<!-- Estilos responsivos -->
<style>
    /* Responsive para tablets */
    @media (max-width: 1024px) {
        .sales-table {
            font-size: 0.8rem;
        }
        
        .sales-table th, .sales-table td {
            padding: 0.5rem 0.4rem;
        }
        
        .col-buyer, .col-sellers {
            width: auto;
        }
        
        .buyer-email {
            display: none;
        }
    }

    /* Responsive para móviles */
    @media (max-width: 768px) {
        .sales-table {
            font-size: 0.75rem;
        }
        
        .sales-table, .sales-table thead, .sales-table tbody, .sales-table th, .sales-table td, .sales-table tr {
            display: block;
        }
        
        .sales-table thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }
        
        .sales-table tr {
            border: 1px solid #f1f3f4;
            margin-bottom: 1rem;
            border-radius: 8px;
            padding: 0.5rem;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .sales-table td {
            border: none;
            position: relative;
            padding: 0.5rem 0.5rem 0.5rem 40%;
            text-align: left;
        }
        
        .sales-table td:before {
            content: attr(data-label) ": ";
            position: absolute;
            left: 0.5rem;
            width: 35%;
            text-align: left;
            font-weight: 600;
            color: #6c757d;
            font-size: 0.7rem;
        }
        
        .col-id td:before { content: "ID: "; }
        .col-date td:before { content: "Fecha: "; }
        .col-buyer td:before { content: "Comprador: "; }
        .col-type td:before { content: "Tipo: "; }
        .col-sellers td:before { content: "Vendedores: "; }
        .col-items td:before { content: "Items: "; }
        .col-status td:before { content: "Estado: "; }
        .col-total td:before { content: "Total: "; }
        .col-seller-amount td:before { content: "Al Vendedor: "; }
        .col-commission td:before { content: "Comisión: "; }
        .col-actions td:before { content: "Acción: "; }
    }

    /* Estilos para paginación compacta */
    .pagination {
        margin: 0;
        justify-content: center;
    }

    .pagination .page-link {
        padding: 0.375rem 0.75rem;
        margin: 0 2px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        color: #6c757d;
        text-decoration: none;
        font-size: 0.875rem;
        line-height: 1.5;
        transition: all 0.15s ease-in-out;
    }

    .pagination .page-link:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
        color: #495057;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }

    .pagination .page-item.disabled .page-link {
        background-color: transparent;
        border-color: #dee2e6;
        color: #6c757d;
        cursor: not-allowed;
    }

    /* Reducir tamaño de iconos de navegación */
    .pagination .page-link svg,
    .pagination .page-link i {
        font-size: 0.75rem !important;
        width: 12px !important;
        height: 12px !important;
    }

    /* Estilo para flechas de texto en caso de que no sean SVG */
    .pagination .page-link:contains("Previous"),
    .pagination .page-link:contains("Next") {
        font-size: 0.8rem;
    }

    .pagination nav {
        margin: 0 auto;
    }

    .pagination .flex {
        justify-content: center;
        gap: 0.5rem;
    }
</style>

<script>
    // Función para eliminar filtros individuales
    function removeFilter(filterName) {
        const url = new URL(window.location);
        url.searchParams.delete(filterName);
        window.location.href = url.toString();
    }

    // Función para limpiar todos los filtros
    function clearAllFilters() {
        window.location.href = "{{ route('admin.ventas') }}";
    }

    // Auto-submit del formulario cuando se cambian los selectores
    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('#buyer_type, #status');
        selects.forEach(select => {
            select.addEventListener('change', function() {
                // Auto-submit con un pequeño delay para mejor UX
                setTimeout(() => {
                    this.closest('form').submit();
                }, 100);
            });
        });

        // Auto-submit para campos de fecha
        const dateInputs = document.querySelectorAll('#date_from, #date_to');
        dateInputs.forEach(input => {
            input.addEventListener('change', function() {
                setTimeout(() => {
                    this.closest('form').submit();
                }, 100);
            });
        });

        // Función de búsqueda en tiempo real (con debounce)
        let searchTimeout;
        const searchInput = document.getElementById('search');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (this.value.length >= 3 || this.value.length === 0) {
                        this.closest('form').submit();
                    }
                }, 500); // 500ms de delay
            });
        }
    });

    // Función para exportar resultados (opcional para futuro)
    function exportResults() {
        // Agregar parámetro de exportación a la URL actual
        const url = new URL(window.location);
        url.searchParams.set('export', 'excel');
        window.open(url.toString(), '_blank');
    }
</script>

@endsection