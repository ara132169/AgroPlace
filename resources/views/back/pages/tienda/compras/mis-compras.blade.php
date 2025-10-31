{{-- resources/views/back/pages/tienda/compras/mis-compras.blade.php --}}
@extends('back.layout.pages-layout')
@section('PageTitle', 'Mis Compras')
@section('content')

<style>
    .purchases-container {
        background: white;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        font-family: 'Inter', sans-serif;
    }

    .purchases-header {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 30px;
        text-align: center;
    }

    .purchases-header h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 600;
    }

    .purchases-header p {
        margin: 10px 0 0 0;
        opacity: 0.9;
        font-size: 16px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: #f8f9fa;
        border-radius: 10px;
        margin: 30px 0;
    }

    .empty-state i {
        font-size: 64px;
        color: #2563eb;
        margin-bottom: 20px;
        opacity: 0.7;
    }

    .empty-state h3 {
        color: #495057;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .empty-state p {
        color: #6c757d;
        font-size: 16px;
        line-height: 1.6;
        max-width: 500px;
        margin: 0 auto 20px;
    }

    .btn-shop {
        background: #2563eb;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-block;
        margin-top: 10px;
    }

    .btn-shop:hover {
        background: #1d4ed8;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        border-left: 4px solid #2563eb;
    }

    .stat-card h3 {
        font-size: 28px;
        font-weight: 700;
        color: #2563eb;
        margin: 0 0 5px 0;
    }

    .stat-card p {
        color: #6c757d;
        margin: 0;
        font-size: 14px;
        font-weight: 500;
    }

    .purchases-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .purchases-table thead th {
        background: #f8f9fa;
        color: #495057;
        padding: 15px;
        font-weight: 600;
        text-align: left;
        border-bottom: 2px solid #dee2e6;
    }

    .purchases-table tbody td {
        padding: 15px;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .purchases-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .order-id {
        font-weight: 600;
        color: #2563eb;
        font-size: 16px;
    }

    .order-date {
        color: #6c757d;
        font-size: 14px;
    }

    .seller-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .seller-tag {
        background: #e3f2fd;
        color: #1976d2;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .order-status {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pendiente {
        background: #fff3cd;
        color: #856404;
    }

    .status-pagado {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-enviado {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-completado {
        background: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .btn-view {
        background: #2563eb;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        background: #1d4ed8;
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }

    .pagination-wrapper {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }

    @media (max-width: 768px) {
        .purchases-table thead {
            display: none;
        }
        
        .purchases-table tbody tr {
            display: block;
            border: 1px solid #dee2e6;
            margin-bottom: 15px;
            border-radius: 8px;
            padding: 15px;
        }
        
        .purchases-table tbody td {
            display: block;
            padding: 8px 0;
            border: none;
            text-align: left;
        }
        
        .purchases-table tbody td:before {
            content: attr(data-label) ": ";
            font-weight: 600;
            color: #2563eb;
            display: inline-block;
            width: 100px;
        }

        .seller-tags {
            margin-top: 5px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="purchases-container">
    <!-- Header -->
    <div class="purchases-header">
        <h2><i class="fa fa-shopping-cart"></i> Mis Compras</h2>
        <p>Historial de compras realizadas como cliente</p>
    </div>

    @if($compras->count() == 0)
        <!-- Estado vacío - Sin compras -->
        <div class="empty-state">
            <i class="fa fa-shopping-bag"></i>
            <h3>Aún no has realizado compras</h3>
            <p>Explora nuestro catálogo de productos y realiza tu primera compra. Podrás ver todas tus compras aquí.</p>
            <a href="{{ route('inicio') }}" class="btn-shop">
                <i class="fa fa-shopping-cart"></i> Ir a la Tienda
            </a>
        </div>
    @else
        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <h3>{{ $compras->total() }}</h3>
                <p>Total de Compras</p>
            </div>
            <div class="stat-card">
                <h3>${{ number_format($compras->sum('total'), 2) }}</h3>
                <p>Dinero Gastado</p>
            </div>
            <div class="stat-card">
                <h3>{{ $compras->where('status', 'completado')->count() }}</h3>
                <p>Compras Completadas</p>
            </div>
            <div class="stat-card">
                <h3>{{ $compras->where('status', 'pendiente')->count() + $compras->where('status', 'pagado')->count() + $compras->where('status', 'enviado')->count() }}</h3>
                <p>Compras Pendientes</p>
            </div>
        </div>

        <!-- Tabla de compras -->
        <div class="table-responsive">
            <table class="purchases-table">
                <thead>
                    <tr>
                        <th>Pedido</th>
                        <th>Fecha</th>
                        <th>Vendedores</th>
                        <th>Productos</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($compras as $compra)
                    <tr>
                        <td data-label="Pedido">
                            <div class="order-id">#{{ $compra->id }}</div>
                            <div class="order-date">{{ \Carbon\Carbon::parse($compra->created_at)->format('j M Y, g:i A') }}</div>
                        </td>
                        <td data-label="Fecha">
                            {{ \Carbon\Carbon::parse($compra->created_at)->format('j \d\e F \d\e Y') }}
                        </td>
                        <td data-label="Vendedores">
                            <div class="seller-tags">
                                @if($compra->vendedores)
                                    @foreach(explode(',', $compra->vendedores) as $vendedor)
                                        <span class="seller-tag">{{ trim($vendedor) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No especificado</span>
                                @endif
                            </div>
                        </td>
                        <td data-label="Productos">
                            <span class="badge badge-info">{{ $compra->items_count }} producto{{ $compra->items_count > 1 ? 's' : '' }}</span>
                        </td>
                        <td data-label="Estado">
                            <span class="order-status 
                                @if($compra->status === 'pendiente') status-pendiente
                                @elseif($compra->status === 'pagado') status-pagado  
                                @elseif($compra->status === 'enviado') status-enviado  
                                @elseif($compra->status === 'completado') status-completado
                                @else status-cancelled
                                @endif">
                                @if($compra->status === 'pendiente')
                                    Pendiente
                                @elseif($compra->status === 'pagado')
                                    Pagado
                                @elseif($compra->status === 'enviado')
                                    Enviado
                                @elseif($compra->status === 'completado')
                                    Completado
                                @else
                                    Cancelado
                                @endif
                            </span>
                        </td>
                        <td data-label="Total">
                            <strong>${{ number_format($compra->total, 2) }}</strong>
                        </td>
                        <td data-label="Acciones">
                            <a href="{{ route('tienda.compra.detalle', $compra->id) }}" class="btn-view">
                                VER DETALLE
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($compras->hasPages())
        <div class="pagination-wrapper">
            {{ $compras->links() }}
        </div>
        @endif
    @endif
</div>

@endsection