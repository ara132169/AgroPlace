@extends('back.layout.pages-layout')
@section('PageTitle', 'Gestión de Depósitos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title">💰 Gestión de Depósitos Manuales</h4>
                        <p class="text-muted mb-0">Procesar depósitos pendientes a vendedores</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.cuentas-vendedores') }}" class="btn btn-outline-primary">
                            👥 Gestionar Cuentas
                        </a>
                        <button class="btn btn-info" onclick="showStats()">
                            📊 Estadísticas
                        </button>
                        <!-- BOTÓN PELIGROSO MOVIDO A ZONA SEPARADA -->
                        <div class="dropdown d-inline">
                            <button class="btn btn-warning dropdown-toggle" type="button" id="bulkActionsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                ⚠️ Acciones en Lote
                            </button>
                            <div class="dropdown-menu" aria-labelledby="bulkActionsDropdown">
                                <h6 class="dropdown-header text-danger">⚠️ USAR CON PRECAUCIÓN</h6>
                                <button class="dropdown-item text-warning" onclick="safeProcessAllPending()">
                                    ⚡ Procesar Todos los Pendientes
                                </button>
                                <div class="dropdown-divider"></div>
                                <small class="dropdown-item-text text-muted">
                                    Esta acción afecta múltiples depósitos
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    
                    <!-- Estadísticas rápidas -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3>${{ number_format($stats['pending_amount'], 2) }}</h3>
                                    <small>Pendiente ({{ $stats['pending_count'] }} solicitudes)</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $stats['processing_count'] }}</h3>
                                    <small>En Proceso</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3>${{ number_format($stats['completed_today'], 2) }}</h3>
                                    <small>Completado Hoy</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $stats['total_sellers'] }}</h3>
                                    <small>Vendedores con Cuentas</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-control" id="statusFilter" onchange="filterDeposits()">
                                <option value="">Todos los estados</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendientes</option>
                                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>En Proceso</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completados</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Fallidos</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="sellerFilter" 
                                   placeholder="Buscar por vendedor..." 
                                   value="{{ request('seller') }}">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-primary" onclick="filterDeposits()">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <button class="btn btn-outline-secondary" onclick="clearFilters()">
                                <i class="fas fa-times"></i> Limpiar
                            </button>
                            <button class="btn btn-outline-info" onclick="testConnection()">
                                <i class="fas fa-wifi"></i> Test
                            </button>
                            <button class="btn btn-outline-success" onclick="window.debugDepositSystem ? alert('✅ Sistema funcionando') : alert('❌ Sistema no cargado')">
                                <i class="fas fa-bug"></i> Debug
                            </button>
                            <button class="btn btn-outline-warning" onclick="showRecentLogs()">
                                <i class="fas fa-file-alt"></i> Ver Logs
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de depósitos -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Referencia</th>
                                    <th>Vendedor</th>
                                    <th>Cuenta Destino</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Solicitado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deposits as $deposit)
                                <tr id="deposit-{{ $deposit->id }}" class="deposit-row" data-status="{{ $deposit->status }}">
                                    <td>
                                        <strong>{{ $deposit->reference }}</strong>
                                        @if($deposit->order_id)
                                        <br><small class="text-muted">Orden #{{ $deposit->order_id }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $deposit->seller->name }}</strong>
                                            <br><small class="text-muted">{{ $deposit->seller->email }}</small>
                                            @php
                                                $totalAccounts = $deposit->seller->paymentAccounts->count();
                                                $verifiedAccounts = $deposit->seller->paymentAccounts->where('is_verified', true)->count();
                                            @endphp
                                            @if($totalAccounts > 1)
                                                <br><small class="badge badge-info">
                                                    👥 {{ $verifiedAccounts }}/{{ $totalAccounts }} cuentas verificadas
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            {!! $deposit->paymentAccount->display_info !!}
                                            <br><small class="text-muted">{{ $deposit->paymentAccount->account_holder_name }}</small>
                                            @if($totalAccounts > 1)
                                                <br><small class="text-primary">
                                                    🔄 Cuenta seleccionable al procesar
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ $deposit->formatted_amount }}</strong>
                                        @if($deposit->description)
                                        <br><small class="text-muted">{{ $deposit->description }}</small>
                                        @endif
                                    </td>
                                    <td>{!! $deposit->status_badge !!}</td>
                                    <td>
                                        <small>
                                            {{ $deposit->requested_at->format('d/m/Y H:i') }}
                                            <br>{{ $deposit->requested_at->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group-sm">
                                            @if($deposit->status === 'pending')
                                            <button class="btn btn-sm btn-warning" onclick="processDeposit({{ $deposit->id }})">
                                                🔄 Procesar
                                            </button>
                                            @endif
                                            
                                            @if($deposit->status === 'processing')
                                            <button class="btn btn-sm btn-success" onclick="completeDeposit({{ $deposit->id }})">
                                                ✅ Completar
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="failDeposit({{ $deposit->id }})">
                                                ❌ Fallar
                                            </button>
                                            @endif
                                            
                                            <button class="btn btn-sm btn-info" onclick="viewDetails({{ $deposit->id }})">
                                                👁️ Ver
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                        <br>No hay depósitos {{ request('status') ? 'con ese estado' : '' }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($deposits->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $deposits->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para procesar depósito -->
<div class="modal fade" id="processModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🔄 Procesar Depósito</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="processForm">
                    <div id="depositDetails" class="alert alert-light mb-3">
                        <!-- Información del depósito se cargará aquí -->
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Cuenta de Destino</label>
                        <select class="form-control" id="destinationAccount" name="destination_account_id" required>
                            <option value="">Seleccionar cuenta destino...</option>
                        </select>
                        <small class="text-muted">El depósito se realizará a la cuenta seleccionada</small>
                        
                        <!-- Información completa de la cuenta (solo visible cuando se selecciona) -->
                        <div id="accountFullDetails" class="alert alert-warning mt-2" style="display: none;">
                            <h6>🔐 Información Completa de la Cuenta:</h6>
                            <div id="fullAccountInfo">
                                <!-- Se cargará dinámicamente -->
                            </div>
                            <small class="text-danger">⚠️ Información sensible - Solo para uso administrativo</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Método de Depósito</label>
                        <select class="form-control" id="depositMethod" name="deposit_method" required>
                            <option value="">Seleccionar método</option>
                            <option value="stripe_transfer">🎯 Transferencia Stripe</option>
                            <option value="bank_transfer">🏦 Transferencia Bancaria</option>
                            <option value="manual">📝 Depósito Manual</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">ID de Transacción Externa</label>
                        <input type="text" class="form-control" id="transactionId" name="transaction_id" 
                               placeholder="ID de referencia del pago">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Notas del Administrador</label>
                        <textarea class="form-control" id="adminNotes" name="admin_notes" rows="3"
                                  placeholder="Notas internas sobre el procesamiento..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" onclick="submitProcess()">🔄 Marcar como Procesando</button>
                <button type="button" class="btn btn-success" onclick="submitComplete()">✅ Completar Directamente</button>
            </div>
        </div>
    </div>
<!-- Meta tag para CSRF token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Script externo para manejo de depósitos -->
<script src="{{ asset('js/admin-deposits.js') }}"></script>

<script>
// Configuración específica para esta vista
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎯 Vista de administración de depósitos cargada');
    console.log('🔗 Script externo cargado desde:', '{{ asset("js/admin-deposits.js") }}');
    
    // Esperar un momento para que el script externo se cargue completamente
    setTimeout(function() {
        // Verificar que las funciones están disponibles
        const functionsCheck = {
            processDeposit: typeof window.processDeposit,
            completeDeposit: typeof window.completeDeposit,
            completeDepositDirect: typeof window.completeDepositDirect,
            failDeposit: typeof window.failDeposit,
            testConnection: typeof window.testConnection,
            debugSystem: typeof window.debugDepositSystem
        };
        
        console.log('🔍 Verificación de funciones:', functionsCheck);
        
        // Mostrar alertas si hay problemas
        const missingFunctions = Object.entries(functionsCheck)
            .filter(([name, type]) => type !== 'function')
            .map(([name]) => name);
        
        if (missingFunctions.length > 0) {
            console.error('❌ Funciones no disponibles:', missingFunctions);
            console.error('🔍 Verificando window object para funciones...');
            console.log('Window functions:', Object.keys(window).filter(key => key.includes('deposit')));
        } else {
            console.log('✅ Todas las funciones están disponibles');
        }
        
        // Test adicional - verificar botones con onclick
        const buttonTests = document.querySelectorAll('button[onclick*="completeDeposit"]');
        console.log(`🔍 Botones con completeDeposit encontrados: ${buttonTests.length}`);
        
        if (buttonTests.length > 0) {
            console.log('📋 Botones:', Array.from(buttonTests).map(btn => ({
                text: btn.textContent.trim(),
                onclick: btn.getAttribute('onclick')
            })));
        }
        
    }, 500); // Esperar 500ms para que se cargue el script
});

// Función de emergencia si el script externo falla
window.emergencyCompleteDeposit = function(depositId) {
    console.log('🚨 Función de emergencia llamada para depósito:', depositId);
    
    if (confirm('¿Completar depósito ' + depositId + '?\n\nUsando función de emergencia.')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
        
        fetch(`/admin/depositos/${depositId}/actualizar-estado`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                status: 'completed',
                admin_notes: 'Completado usando función de emergencia'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ ' + data.message);
                location.reload();
            } else {
                alert('❌ Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('❌ Error:', error);
            alert('❌ Error de conexión: ' + error.message);
        });
    }
};
</script>
@endsection