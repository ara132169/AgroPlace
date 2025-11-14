@extends('back.layout.pages-layout')
@section('PageTitle', 'Cuentas de Vendedores')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title">👥 Gestión de Cuentas de Vendedores</h4>
                        <p class="text-muted mb-0">Verificar y gestionar cuentas de pago de vendedores</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.depositos') }}" class="btn btn-outline-primary">
                            ← Volver a Depósitos
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    
                    <!-- Estadísticas rápidas -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $stats['total_accounts'] }}</h3>
                                    <small>Total Cuentas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $stats['verified_accounts'] }}</h3>
                                    <small>Verificadas</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $stats['pending_verification'] }}</h3>
                                    <small>Pendientes</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h3>{{ $stats['total_sellers'] }}</h3>
                                    <small>Vendedores únicos</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-control" id="statusFilter" onchange="filterAccounts()">
                                <option value="">Todos los estados</option>
                                <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verificadas</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendientes</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="typeFilter" onchange="filterAccounts()">
                                <option value="">Todos los tipos</option>
                                <option value="card" {{ request('type') === 'card' ? 'selected' : '' }}>Tarjetas</option>
                                <option value="bank" {{ request('type') === 'bank' ? 'selected' : '' }}>Bancos</option>
                                <option value="paypal" {{ request('type') === 'paypal' ? 'selected' : '' }}>PayPal</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="sellerFilter" 
                                   placeholder="Buscar por vendedor..." 
                                   value="{{ request('seller') }}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary" onclick="filterAccounts()">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                        </div>
                    </div>

                    <!-- Tabla de cuentas -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Vendedor</th>
                                    <th>Tipo de Cuenta</th>
                                    <th>Información de Cuenta</th>
                                    <th>Estado</th>
                                    <th>Creada</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($accounts as $account)
                                <tr id="account-{{ $account->id }}" data-status="{{ $account->is_verified ? 'verified' : 'pending' }}">
                                    <td>
                                        <div>
                                            <strong>{{ $account->seller->name }}</strong>
                                            <br><small class="text-muted">{{ $account->seller->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($account->account_type === 'card')
                                            <span class="badge badge-primary">💳 Tarjeta</span>
                                        @elseif($account->account_type === 'bank')
                                            <span class="badge badge-info">🏦 Banco</span>
                                        @else
                                            <span class="badge badge-warning">📧 PayPal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            {!! $account->display_info !!}
                                            <br><small class="text-muted">{{ $account->account_holder_name }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        @if($account->is_verified)
                                            <span class="badge badge-success">✅ Verificada</span>
                                            @if($account->verifiedBy)
                                                <br><small class="text-muted">Por: {{ $account->verifiedBy->name }}</small>
                                            @endif
                                        @else
                                            <span class="badge badge-warning">⏳ Pendiente</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>
                                            {{ $account->created_at->format('d/m/Y H:i') }}
                                            <br>{{ $account->created_at->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group-sm">
                                            <button class="btn btn-sm btn-info" onclick="viewAccount({{ $account->id }})">
                                                👁️ Ver
                                            </button>
                                            
                                            @if(!$account->is_verified)
                                            <button class="btn btn-sm btn-success" onclick="verifyAccount({{ $account->id }})">
                                                ✅ Verificar
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="rejectAccount({{ $account->id }})">
                                                ❌ Rechazar
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-credit-card fa-2x text-muted mb-2"></i>
                                        <br>No hay cuentas de vendedores {{ request('status') ? 'con ese estado' : '' }}
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($accounts->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $accounts->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para verificar cuenta -->
<div class="modal fade" id="verifyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">✅ Verificar Cuenta</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de verificar esta cuenta de pago?</p>
                <div class="alert alert-info">
                    <small><strong>Nota:</strong> Una vez verificada, el vendedor podrá solicitar depósitos.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="confirmVerify()">Verificar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para rechazar cuenta -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">❌ Rechazar Cuenta</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="rejectForm">
                    <div class="mb-3">
                        <label class="form-label">Motivo del rechazo:</label>
                        <textarea class="form-control" name="admin_notes" rows="3" 
                                  placeholder="Explica por qué se rechaza la cuenta..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="confirmReject()">Rechazar</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentAccountId = null;

function filterAccounts() {
    const status = document.getElementById('statusFilter').value;
    const type = document.getElementById('typeFilter').value;
    const seller = document.getElementById('sellerFilter').value;
    
    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (type) params.append('type', type);
    if (seller) params.append('seller', seller);
    
    window.location.href = '{{ route("admin.cuentas-vendedores") }}?' + params.toString();
}

function viewAccount(accountId) {
    window.open(`{{ route('admin.cuentas-vendedores.ver', ':id') }}`.replace(':id', accountId), '_blank');
}

function verifyAccount(accountId) {
    currentAccountId = accountId;
    $('#verifyModal').modal('show');
}

function rejectAccount(accountId) {
    currentAccountId = accountId;
    $('#rejectModal').modal('show');
}

function confirmVerify() {
    if (!currentAccountId) return;
    
    fetch(`{{ route('admin.cuentas-vendedores.verificar', ':id') }}`.replace(':id', currentAccountId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            action: 'verify'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#verifyModal').modal('hide');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al verificar cuenta');
    });
}

function confirmReject() {
    if (!currentAccountId) return;
    
    const form = document.getElementById('rejectForm');
    const formData = new FormData(form);
    
    fetch(`{{ route('admin.cuentas-vendedores.verificar', ':id') }}`.replace(':id', currentAccountId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            action: 'reject',
            admin_notes: formData.get('admin_notes')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#rejectModal').modal('hide');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al rechazar cuenta');
    });
}
</script>
@endsection