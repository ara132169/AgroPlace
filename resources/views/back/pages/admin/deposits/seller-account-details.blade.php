@extends('back.layout.pages-layout')
@section('PageTitle', 'Detalles de Cuenta')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title">💳 Detalles de Cuenta de Pago</h4>
                        <p class="text-muted mb-0">{{ $account->seller->name }} ({{ $account->seller->email }})</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.cuentas-vendedores') }}" class="btn btn-outline-primary">
                            ← Volver a Cuentas
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    
                    <div class="row">
                        <!-- Información de la cuenta -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5>📄 Información de la Cuenta</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Tipo de Cuenta:</strong></p>
                                            @if($account->account_type === 'card')
                                                <span class="badge badge-primary badge-lg">💳 Tarjeta de Débito/Crédito</span>
                                            @elseif($account->account_type === 'bank')
                                                <span class="badge badge-info badge-lg">🏦 Cuenta Bancaria</span>
                                            @else
                                                <span class="badge badge-warning badge-lg">📧 PayPal</span>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Estado:</strong></p>
                                            @if($account->is_verified)
                                                <span class="badge badge-success badge-lg">✅ Verificada</span>
                                                @if($account->verified_at)
                                                    <br><small class="text-muted">Verificada: {{ $account->verified_at->format('d/m/Y H:i') }}</small>
                                                @endif
                                                @if($account->verifiedBy)
                                                    <br><small class="text-muted">Por: {{ $account->verifiedBy->name }}</small>
                                                @endif
                                            @else
                                                <span class="badge badge-warning badge-lg">⏳ Pendiente de verificación</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><strong>Titular de la Cuenta:</strong></p>
                                            <p class="lead">{{ $account->account_holder_name }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><strong>Información de la Cuenta:</strong></p>
                                            <div class="alert alert-light">
                                                {!! $account->display_info !!}
                                            </div>
                                            
                                            <!-- Botón para mostrar información completa -->
                                            <button class="btn btn-warning btn-sm" onclick="toggleFullAccountInfo()" id="toggleBtn">
                                                🔐 Ver Información Completa (Solo Admin)
                                            </button>
                                            
                                            <!-- Información completa (oculta por defecto) -->
                                            <div id="fullAccountInfo" class="alert alert-danger mt-3" style="display: none;">
                                                <h6>⚠️ INFORMACIÓN COMPLETA - SOLO ADMINISTRATIVO</h6>
                                                @php $fullInfo = $account->admin_full_info; @endphp
                                                
                                                @if($fullInfo['type'] === '💳 Tarjeta')
                                                    <p><strong>Tipo:</strong> {{ $fullInfo['type'] }}</p>
                                                    <p><strong>Marca:</strong> {{ $fullInfo['brand'] }}</p>
                                                    <p><strong>Número completo:</strong> <code>{{ $fullInfo['number'] }}</code></p>
                                                    <p><strong>Titular:</strong> {{ $fullInfo['holder'] }}</p>
                                                @elseif($fullInfo['type'] === '🏦 Cuenta Bancaria')
                                                    <p><strong>Tipo:</strong> {{ $fullInfo['type'] }}</p>
                                                    <p><strong>Banco:</strong> {{ $fullInfo['bank'] }}</p>
                                                    <p><strong>Número de cuenta:</strong> <code>{{ $fullInfo['account_number'] }}</code></p>
                                                    <p><strong>CLABE:</strong> <code>{{ $fullInfo['clabe'] }}</code></p>
                                                    <p><strong>Titular:</strong> {{ $fullInfo['holder'] }}</p>
                                                @elseif($fullInfo['type'] === '📧 PayPal')
                                                    <p><strong>Tipo:</strong> {{ $fullInfo['type'] }}</p>
                                                    <p><strong>Email:</strong> <code>{{ $fullInfo['email'] }}</code></p>
                                                    <p><strong>Titular:</strong> {{ $fullInfo['holder'] }}</p>
                                                @endif
                                                
                                                <small class="text-white">
                                                    <strong>⚠️ Usar solo para transferencias manuales. No compartir.</strong>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Creada:</strong></p>
                                            <p>{{ $account->created_at->format('d/m/Y H:i') }}</p>
                                            <small class="text-muted">{{ $account->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Última actualización:</strong></p>
                                            <p>{{ $account->updated_at->format('d/m/Y H:i') }}</p>
                                            <small class="text-muted">{{ $account->updated_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Estadísticas y acciones -->
                        <div class="col-md-4">
                            <!-- Estadísticas -->
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h6>📊 Estadísticas de la Cuenta</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <h4 class="text-success">${{ number_format($accountStats['total_amount'], 2) }}</h4>
                                        <small class="text-muted">Total depositado</small>
                                    </div>
                                    
                                    <div class="row text-center">
                                        <div class="col-6">
                                            <h5>{{ $accountStats['total_deposits'] }}</h5>
                                            <small class="text-muted">Depósitos totales</small>
                                        </div>
                                        <div class="col-6">
                                            <h5 class="text-warning">{{ $accountStats['pending_deposits'] }}</h5>
                                            <small class="text-muted">Pendientes</small>
                                        </div>
                                    </div>
                                    
                                    @if($accountStats['last_deposit'])
                                    <hr>
                                    <p><small><strong>Último depósito:</strong></small></p>
                                    <p><small>{{ $accountStats['last_deposit']->requested_at->format('d/m/Y') }}</small></p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Acciones -->
                            <div class="card">
                                <div class="card-header">
                                    <h6>⚙️ Acciones</h6>
                                </div>
                                <div class="card-body">
                                    @if(!$account->is_verified)
                                        <button class="btn btn-success btn-block mb-2" onclick="verifyAccount()">
                                            ✅ Verificar Cuenta
                                        </button>
                                        <button class="btn btn-danger btn-block mb-2" onclick="rejectAccount()">
                                            ❌ Rechazar Cuenta
                                        </button>
                                    @else
                                        <div class="alert alert-success">
                                            <small>✅ Esta cuenta ya está verificada y puede recibir depósitos.</small>
                                        </div>
                                    @endif
                                    
                                    <button class="btn btn-info btn-block" onclick="viewDeposits()">
                                        💰 Ver Depósitos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Historial de depósitos recientes -->
                    @if($account->deposits->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>💸 Depósitos Recientes</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Referencia</th>
                                            <th>Monto</th>
                                            <th>Estado</th>
                                            <th>Solicitado</th>
                                            <th>Procesado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($account->deposits as $deposit)
                                        <tr>
                                            <td>{{ $deposit->reference }}</td>
                                            <td class="text-success">${{ number_format($deposit->amount, 2) }}</td>
                                            <td>{!! $deposit->status_badge !!}</td>
                                            <td>{{ $deposit->requested_at->format('d/m/Y') }}</td>
                                            <td>
                                                @if($deposit->processed_at)
                                                    {{ $deposit->processed_at->format('d/m/Y') }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            @if($accountStats['total_deposits'] > 10)
                            <div class="text-center">
                                <button class="btn btn-outline-primary" onclick="viewAllDeposits()">
                                    Ver todos los depósitos ({{ $accountStats['total_deposits'] }})
                                </button>
                            </div>
                            @endif
                        </div>
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
                    <small><strong>Vendedor:</strong> {{ $account->seller->name }}</small><br>
                    <small><strong>Cuenta:</strong> {!! $account->display_info !!}</small><br>
                    <small><strong>Titular:</strong> {{ $account->account_holder_name }}</small>
                </div>
                <div class="alert alert-warning">
                    <small><strong>Nota:</strong> Una vez verificada, el vendedor podrá solicitar depósitos a esta cuenta.</small>
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
                    <div class="alert alert-warning">
                        <small><strong>Cuenta:</strong> {!! $account->display_info !!}</small><br>
                        <small><strong>Titular:</strong> {{ $account->account_holder_name }}</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motivo del rechazo:</label>
                        <textarea class="form-control" name="admin_notes" rows="3" required
                                  placeholder="Explica claramente por qué se rechaza la cuenta..."></textarea>
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
function verifyAccount() {
    $('#verifyModal').modal('show');
}

function rejectAccount() {
    $('#rejectModal').modal('show');
}

function confirmVerify() {
    fetch(`{{ route('admin.cuentas-vendedores.verificar', $account->id) }}`, {
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
    const form = document.getElementById('rejectForm');
    const formData = new FormData(form);
    const notes = formData.get('admin_notes');
    
    if (!notes || notes.trim() === '') {
        alert('Por favor ingresa el motivo del rechazo');
        return;
    }
    
    fetch(`{{ route('admin.cuentas-vendedores.verificar', $account->id) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            action: 'reject',
            admin_notes: notes
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

function viewDeposits() {
    window.location.href = `{{ route('admin.depositos') }}?seller={{ urlencode($account->seller->name) }}`;
}

function viewAllDeposits() {
    viewDeposits();
}

function toggleFullAccountInfo() {
    const showFullInfoBtn = document.getElementById('showFullInfo');
    const hideFullInfoBtn = document.getElementById('hideFullInfo');
    const maskedInfo = document.querySelector('.masked-info');
    const fullInfo = document.querySelector('.full-info');
    const accountId = {{ $account->id }};

    if (!fullInfo.innerHTML.trim()) {
        // Load full account information
        fetch(`{{ route('admin.depositos.detallesCuenta') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                account_id: accountId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.admin_full_info) {
                let fullInfoHtml = '<div class="alert alert-warning mb-3">';
                fullInfoHtml += '<i class="fas fa-exclamation-triangle"></i> ';
                fullInfoHtml += '<strong>Información Sensible:</strong> Esta información es confidencial y solo debe usarse para transferencias manuales.';
                fullInfoHtml += '</div>';
                
                if (data.admin_full_info.full_card_number) {
                    fullInfoHtml += `<div class="mb-3">
                        <strong>Número de Tarjeta Completo:</strong> 
                        <code class="bg-dark text-light p-1 rounded">${data.admin_full_info.full_card_number}</code>
                    </div>`;
                }
                
                if (data.admin_full_info.full_account_number) {
                    fullInfoHtml += `<div class="mb-3">
                        <strong>Número de Cuenta Completo:</strong> 
                        <code class="bg-dark text-light p-1 rounded">${data.admin_full_info.full_account_number}</code>
                    </div>`;
                }
                
                if (data.admin_full_info.full_clabe) {
                    fullInfoHtml += `<div class="mb-3">
                        <strong>CLABE Completa:</strong> 
                        <code class="bg-dark text-light p-1 rounded">${data.admin_full_info.full_clabe}</code>
                    </div>`;
                }
                
                fullInfo.innerHTML = fullInfoHtml;
            } else {
                fullInfo.innerHTML = '<div class="alert alert-danger">Error al cargar información completa</div>';
            }
            
            // Toggle visibility
            maskedInfo.style.display = 'none';
            fullInfo.style.display = 'block';
            showFullInfoBtn.style.display = 'none';
            hideFullInfoBtn.style.display = 'inline-block';
        })
        .catch(error => {
            console.error('Error:', error);
            fullInfo.innerHTML = '<div class="alert alert-danger">Error al cargar información completa</div>';
            maskedInfo.style.display = 'none';
            fullInfo.style.display = 'block';
            showFullInfoBtn.style.display = 'none';
            hideFullInfoBtn.style.display = 'inline-block';
        });
    } else {
        // Just toggle visibility
        maskedInfo.style.display = 'none';
        fullInfo.style.display = 'block';
        showFullInfoBtn.style.display = 'none';
        hideFullInfoBtn.style.display = 'inline-block';
    }
}

function hideFullAccountInfo() {
    const showFullInfoBtn = document.getElementById('showFullInfo');
    const hideFullInfoBtn = document.getElementById('hideFullInfo');
    const maskedInfo = document.querySelector('.masked-info');
    const fullInfo = document.querySelector('.full-info');

    maskedInfo.style.display = 'block';
    fullInfo.style.display = 'none';
    showFullInfoBtn.style.display = 'inline-block';
    hideFullInfoBtn.style.display = 'none';
}
</script>
@endsection