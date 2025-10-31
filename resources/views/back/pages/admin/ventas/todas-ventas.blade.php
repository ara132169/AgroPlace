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

    .sales-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 2rem;
        font-family: var(--font-family);
    }

    .sales-table thead th {
        background: var(--bg-light);
        color: var(--text-primary);
        padding: 1rem 0.75rem;
        font-weight: 600;
        text-align: left;
        border-bottom: 1px solid var(--border-light);
        font-size: 0.9rem;
    }

    .sales-table tbody tr {
        border-bottom: 1px solid var(--border-light);
        transition: background-color 0.2s ease;
    }

    .sales-table tbody tr:hover {
        background-color: var(--bg-light);
    }

    .sales-table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
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
        <!-- Tabla simple y limpia -->
        <table class="sales-table">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Fecha</th>
                    <th>Comprador</th>
                    <th>Tipo</th>
                    <th>Vendedores</th>
                    <th>Productos</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ventas as $venta)
                <tr>
                    <td data-label="Pedido">
                        <strong>#{{ $venta->id }}</strong>
                    </td>
                    <td data-label="Fecha">
                        {{ \Carbon\Carbon::parse($venta->created_at)->format('j M Y') }}
                    </td>
                    <td data-label="Comprador">
                        <div>
                            <strong>{{ $venta->client_name }}</strong><br>
                            <small class="text-muted">{{ $venta->client_email }}</small>
                        </div>
                    </td>
                    <td data-label="Tipo">
                        <span class="badge badge-{{ $venta->buyer_type === 'client' ? 'primary' : 'success' }}">
                            {{ $venta->buyer_type === 'client' ? 'Cliente' : 'Vendedor' }}
                        </span>
                    </td>
                    <td data-label="Vendedores">
                        <div class="seller-tags">
                            @if($venta->vendedores)
                                @foreach(explode(',', $venta->vendedores) as $vendedor)
                                    <span class="seller-tag">{{ trim($vendedor) }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">No especificado</span>
                            @endif
                        </div>
                    </td>
                    <td data-label="Productos">
                        <span class="badge badge-info">{{ $venta->items_count }} producto{{ $venta->items_count > 1 ? 's' : '' }}</span>
                    </td>
                    <td data-label="Estado">
                        <span class="order-status 
                            @if($venta->status === 'pendiente') status-pending
                            @elseif($venta->status === 'pagado') status-processing  
                            @elseif($venta->status === 'enviado') status-processing  
                            @elseif($venta->status === 'completado') status-completed
                            @else status-cancelled
                            @endif">
                            @if($venta->status === 'pendiente')
                                Pendiente
                            @elseif($venta->status === 'pagado')
                                Pagado
                            @elseif($venta->status === 'enviado')
                                Enviado
                            @elseif($venta->status === 'completado')
                                Completado
                            @else
                                Cancelado
                            @endif
                        </span>
                    </td>
                    <td data-label="Total">
                        <strong>${{ number_format($venta->total, 2) }}</strong>
                    </td>
                    <td data-label="Acciones">
                        <a href="{{ route('admin.venta.detalle', $venta->id) }}" class="btn-view">
                            VER DETALLE
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
        <!-- Estado vacío simple -->
        <div class="empty-sales">
            <i class="fa fa-chart-bar"></i>
            <h3>¡No hay ventas registradas!</h3>
            <p>Aún no se han realizado ventas en la plataforma.</p>
        </div>
    @endif
</div>

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