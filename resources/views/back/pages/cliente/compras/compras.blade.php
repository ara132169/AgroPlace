{{-- resources/views/back/pages/cliente/compras/compras.blade.php --}}
@extends('back.layout.pages-layout')
@section('PageTitle', 'Mis Pedidos')
@section('content')

<style>
    /* Variables CSS para colores consistentes */
    :root {
        --primary-color: #1254a1;
        --primary-dark: #0e4082;
        --success-color: #28a745;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --info-color: #17a2b8;
        --light-bg: #f8f9fa;
        --border-color: #e9ecef;
        --text-muted: #6c757d;
        --shadow-sm: 0 2px 4px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 16px rgba(0,0,0,0.12);
        --shadow-lg: 0 8px 24px rgba(0,0,0,0.15);
        --border-radius: 12px;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --font-family: 'Inter', sans-serif; /* Fuente del sidebar */
    }

    /* Reset y base */
    .card-box {
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-md);
        border: none;
        overflow: hidden;
        font-family: var(--font-family);
    }

    /* Header principal con gradiente mejorado */
    .orders-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 50%, #0a2d5c 100%);
        color: white;
        padding: 2rem 2.5rem;
        margin: -1.25rem -1.25rem 2rem -1.25rem;
        position: relative;
        overflow: hidden;
        font-family: var(--font-family);
    }

    .orders-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        pointer-events: none;
    }

    .orders-header-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .orders-header-info h1 {
        margin: 0;
        font-size: 1.75rem; /* Reducido de 2.25rem */
        font-weight: 600; /* Reducido de 700 para coincidir con sidebar */
        letter-spacing: -0.025em;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        font-family: var(--font-family);
    }

    .orders-header-info p {
        margin: 0.5rem 0 0 0;
        opacity: 0.9;
        font-size: 0.95rem; /* Reducido de 1.1rem */
        font-weight: 400; /* Ajustado para consistencia */
        font-family: var(--font-family);
    }

    .orders-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        text-align: center;
    }

    .stat-item {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid rgba(255,255,255,0.2);
        font-family: var(--font-family);
    }

    .stat-number {
        font-size: 1.25rem; /* Reducido de 1.5rem */
        font-weight: 600; /* Reducido de 700 */
        display: block;
        font-family: var(--font-family);
    }

    .stat-label {
        font-size: 0.8rem; /* Reducido de 0.875rem */
        opacity: 0.8;
        margin-top: 0.25rem;
        font-weight: 400;
        font-family: var(--font-family);
    }

    /* Tabla moderna mejorada */
    .orders-table-container {
        background: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    .shop-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
        background: white;
    }

    .shop-table thead th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: #495057;
        padding: 1.25rem 1.5rem;
        font-weight: 500; /* Reducido de 600 */
        text-align: left;
        font-size: 0.8rem; /* Reducido de 0.875rem */
        text-transform: uppercase;
        letter-spacing: 0.3px; /* Reducido de 0.5px */
        border-bottom: 2px solid var(--primary-color);
        position: relative;
        font-family: var(--font-family);
    }

    .shop-table thead th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
    }

    .shop-table tbody td {
        padding: 1.5rem 1.5rem;
        border-bottom: 1px solid #f1f3f4;
        vertical-align: middle;
        font-size: 0.875rem; /* Reducido de 0.95rem */
        transition: var(--transition);
        font-family: var(--font-family);
        font-weight: 400; /* Agregado para consistencia */
    }

    .shop-table tbody tr {
        transition: var(--transition);
        position: relative;
    }

    .shop-table tbody tr:hover {
        background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(18, 84, 161, 0.1);
    }

    .shop-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Estilos de celdas específicas */
    .order-id {
        font-weight: 600; /* Reducido de 700 */
        color: var(--primary-color);
        font-size: 1rem; /* Reducido de 1.1rem */
        position: relative;
        font-family: var(--font-family);
    }

    .order-id::before {
        content: '';
        position: absolute;
        left: -0.5rem;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 20px;
        background: var(--primary-color);
        border-radius: 2px;
    }

    .order-date {
        color: var(--text-muted);
        font-weight: 400; /* Reducido de 500 */
        font-family: var(--font-family);
    }

    /* Estados mejorados con iconos */
    .order-status {
        padding: 0.4rem 0.8rem; /* Reducido padding */
        border-radius: 20px; /* Más redondeado y consistente */
        font-size: 0.7rem; /* Reducido de 0.75rem */
        font-weight: 500; /* Reducido de 600 */
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem; /* Reducido gap */
        letter-spacing: 0.3px; /* Reducido letter-spacing */
        box-shadow: var(--shadow-sm);
        border: 1px solid transparent;
        transition: var(--transition);
        font-family: var(--font-family);
        min-width: 85px; /* Ancho mínimo para consistencia */
        justify-content: center; /* Centrar contenido */
    }

    .order-status:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }

    .order-status::before {
        content: '';
        width: 6px; /* Reducido de 8px */
        height: 6px; /* Reducido de 8px */
        border-radius: 50%;
        display: inline-block;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
        border-color: #fff3cd;
    }

    .status-pending::before {
        background: #856404;
    }

    .status-processing {
        background: #d1ecf1;
        color: #0c5460;
        border-color: #d1ecf1;
    }

    .status-processing::before {
        background: #0c5460;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
        border-color: #d4edda;
    }

    .status-completed::before {
        background: #155724;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
        border-color: #f8d7da;
    }

    .status-cancelled::before {
        background: #721c24;
    }

    /* Precio mejorado */
    .order-price {
        font-weight: 700;
        color: var(--success-color);
        font-size: 1.25rem;
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }

    .order-quantity {
        background: var(--light-bg);
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-left: 0.5rem;
    }

    /* Botón de acción mejorado */
    .btn-view {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 0.6rem 1.25rem; /* Reducido padding */
        border: none;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.8rem; /* Reducido de 0.875rem */
        font-weight: 500; /* Reducido de 600 */
        text-transform: uppercase;
        letter-spacing: 0.3px; /* Reducido de 0.5px */
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
        font-family: var(--font-family);
    }

    .btn-view::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .btn-view:hover::before {
        left: 100%;
    }

    .btn-view:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, #0a2d5c 100%);
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-view i {
        font-size: 0.875rem;
    }

    /* Estado vacío mejorado */
    .empty-orders {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(135deg, #f8f9fa 0%, white 100%);
        border-radius: var(--border-radius);
        border: 2px dashed var(--border-color);
        margin: 2rem 0;
        position: relative;
        overflow: hidden;
    }

    .empty-orders::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 10px,
            rgba(18, 84, 161, 0.03) 10px,
            rgba(18, 84, 161, 0.03) 20px
        );
        animation: float 20s linear infinite;
        pointer-events: none;
    }

    @keyframes float {
        0% { transform: translate(-50%, -50%) rotate(0deg); }
        100% { transform: translate(-50%, -50%) rotate(360deg); }
    }

    .empty-orders-content {
        position: relative;
        z-index: 1;
    }

    .empty-orders i {
        font-size: 4rem;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        opacity: 0.6;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .empty-orders h3 {
        color: #495057;
        margin-bottom: 0.75rem;
        font-size: 1.25rem; /* Reducido de 1.5rem */
        font-weight: 500; /* Reducido de 600 */
        font-family: var(--font-family);
    }

    .empty-orders p {
        color: var(--text-muted);
        margin-bottom: 2rem;
        font-size: 0.95rem; /* Reducido de 1.1rem */
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
        font-family: var(--font-family);
        font-weight: 400;
    }

    /* Botón de compra mejorado */
    .btn-shop {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        padding: 0.875rem 1.75rem; /* Reducido padding */
        border-radius: 50px;
        text-decoration: none;
        font-weight: 500; /* Reducido de 600 */
        font-size: 0.875rem; /* Reducido de 1rem */
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        transition: var(--transition);
        box-shadow: var(--shadow-md);
        text-transform: uppercase;
        letter-spacing: 0.3px; /* Reducido de 0.5px */
        position: relative;
        overflow: hidden;
        font-family: var(--font-family);
    }

    .btn-shop::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.6s;
    }

    .btn-shop:hover::before {
        left: 100%;
    }

    .btn-shop:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, #0a2d5c 100%);
        color: white;
        text-decoration: none;
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
    }

    .btn-shop i {
        transition: var(--transition);
    }

    .btn-shop:hover i {
        transform: translateX(4px);
    }

    /* Sección de acciones */
    .orders-actions {
        margin-top: 2rem;
        padding: 1.5rem;
        background: var(--light-bg);
        border-radius: var(--border-radius);
        border-top: 3px solid var(--primary-color);
        text-align: center;
    }

    /* Responsive mejorado */
    @media (max-width: 768px) {
        .orders-header {
            padding: 1.5rem;
            margin: -1.25rem -1.25rem 1.5rem -1.25rem;
        }

        .orders-header-content {
            flex-direction: column;
            gap: 1.5rem;
        }

        .orders-header-info h1 {
            font-size: 1.75rem;
        }

        .orders-stats {
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
        }

        .stat-item {
            padding: 0.75rem;
        }

        .shop-table thead {
            display: none;
        }
        
        .shop-table tbody tr {
            display: block;
            border: 1px solid var(--border-color);
            margin-bottom: 1rem;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            background: white;
        }
        
        .shop-table tbody td {
            display: block;
            padding: 0.75rem 0;
            border: none;
            text-align: left;
        }
        
        .shop-table tbody td:before {
            content: attr(data-label) ": ";
            font-weight: 600;
            color: var(--primary-color);
            display: inline-block;
            width: 100px;
            margin-bottom: 0.25rem;
        }

        .order-id::before {
            display: none;
        }

        .empty-orders {
            padding: 2rem 1rem;
        }

        .empty-orders i {
            font-size: 3rem;
        }

        .btn-shop {
            padding: 0.875rem 1.5rem;
            font-size: 0.875rem;
        }
    }

    /* Animaciones */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .shop-table tbody tr {
        animation: fadeIn 0.5s ease-out;
    }

    .shop-table tbody tr:nth-child(odd) {
        animation-delay: 0.1s;
    }

    .shop-table tbody tr:nth-child(even) {
        animation-delay: 0.2s;
    }
</style>

<div class="card-box mb-30">
    <!-- Header principal mejorado -->
    <div class="orders-header">
        <div class="orders-header-content">
            <div class="orders-header-info">
                <h1><i class="fa fa-shopping-bag"></i> Mis Pedidos</h1>
                <p>Gestiona y revisa todos tus pedidos realizados</p>
            </div>
            <div class="orders-stats">
                <div class="stat-item">
                    <span class="stat-number">{{ $compras ? $compras->count() : 0 }}</span>
                    <span class="stat-label">Total</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $compras ? $compras->where('status', 'completed')->count() : 0 }}</span>
                    <span class="stat-label">Completados</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $compras ? $compras->where('status', 'processing')->count() : 0 }}</span>
                    <span class="stat-label">En Proceso</span>
                </div>
            </div>
        </div>
    </div>

    @if($compras && $compras->count() > 0)
        <!-- Tabla de órdenes mejorada -->
        <div class="orders-table-container">
            <table class="shop-table account-orders-table">
                <thead>
                    <tr>
                        <th class="order-id"><i class="fa fa-hashtag"></i> Pedido</th>
                        <th class="order-date"><i class="fa fa-calendar"></i> Fecha</th>
                        <th class="order-status"><i class="fa fa-info-circle"></i> Estado</th>
                        <th class="order-total"><i class="fa fa-dollar-sign"></i> Total</th>
                        <th class="order-actions"><i class="fa fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($compras as $compra)
                    <tr>
                        <td class="order-id" data-label="Pedido">#{{ $compra->id }}</td>
                        <td class="order-date" data-label="Fecha">
                            {{ $compra->created_at->format('d/m/Y') }}
                            <small class="d-block text-muted">{{ $compra->created_at->format('H:i') }}</small>
                        </td>
                        <td class="order-status" data-label="Estado">
                            <span class="order-status 
                                @if($compra->status === 'pending') status-pending
                                @elseif($compra->status === 'processing') status-processing  
                                @elseif($compra->status === 'completed') status-completed
                                @else status-cancelled
                                @endif">
                                @if($compra->status === 'pending')
                                    Pendiente
                                @elseif($compra->status === 'processing')
                                    En Proceso
                                @elseif($compra->status === 'completed')
                                    Completado
                                @else
                                    Cancelado
                                @endif
                            </span>
                        </td>
                        <td class="order-total" data-label="Total">
                            <span class="order-price">${{ number_format($compra->total, 2) }}</span>
                            @if($compra->items_count)
                                <div class="mt-1">
                                    <span class="order-quantity">{{ $compra->items_count }} {{ $compra->items_count === 1 ? 'artículo' : 'artículos' }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="order-action" data-label="Acciones">
                            <a href="{{ route('cliente.pedido.detalle', $compra->id) }}" 
                               class="btn-view">
                                <i class="fa fa-eye"></i> Ver Detalles
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Sección de acciones -->
        <div class="orders-actions">
            <a href="{{ route('productos.index') }}" class="btn-shop">
                <i class="fa fa-shopping-cart"></i> Continuar Comprando
            </a>
        </div>
    @else
        <!-- Estado vacío mejorado -->
        <div class="empty-orders">
            <div class="empty-orders-content">
                <i class="fa fa-shopping-bag"></i>
                <h3>¡Aún no tienes pedidos!</h3>
                <p>Explora nuestro catálogo de productos y realiza tu primera compra. Te aseguramos una experiencia de compra excepcional.</p>
                <a href="{{ route('productos.index') }}" class="btn-shop">
                    <i class="fa fa-rocket"></i> Comenzar a Comprar
                </a>
            </div>
        </div>
    @endif
</div>

@endsection