@extends('back.layout.pages-layout')
@section('PageTitle', 'Historial de Depósitos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Estadísticas del vendedor -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3>${{ number_format($stats['pending_amount'], 2) }}</h3>
                            <small>💰 Pendiente de Depósito</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3>${{ number_format($stats['completed_amount'], 2) }}</h3>
                            <small>✅ Total Depositado</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3>{{ $stats['total_deposits'] }}</h3>
                            <small>📊 Total Solicitudes</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title">📊 Historial de Depósitos</h4>
                        <p class="text-muted mb-0">Seguimiento de tus solicitudes de pago</p>
                    </div>
                    <div>
                        @if(auth('seller')->user()->hasPaymentAccount())
                        <button class="btn btn-success" onclick="requestNewDeposit()">
                            💰 Solicitar Depósito
                        </button>
                        @else
                        <a href="{{ route('tienda.payment.accounts') }}" class="btn btn-warning">
                            ⚠️ Configurar Cuenta Primero
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    
                    @if($deposits->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Referencia</th>
                                    <th>Monto</th>
                                    <th>Cuenta Destino</th>
                                    <th>Estado</th>
                                    <th>Solicitado</th>
                                    <th>Completado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deposits as $deposit)
                                <tr class="deposit-row">
                                    <td>
                                        <div>
                                            <strong>{{ $deposit->reference }}</strong>
                                            @if($deposit->order_id)
                                            <br><small class="text-muted">Orden #{{ $deposit->order_id }}</small>
                                            @endif
                                        </div>
                                        @if($deposit->description)
                                        <small class="text-muted d-block">{{ $deposit->description }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ $deposit->formatted_amount }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            {!! $deposit->paymentAccount->display_info !!}
                                            <br><small class="text-muted">{{ $deposit->paymentAccount->account_holder_name }}</small>
                                        </div>
                                    </td>
                                    <td>{!! $deposit->status_badge !!}</td>
                                    <td>
                                        <small>
                                            {{ $deposit->requested_at->format('d/m/Y H:i') }}
                                            <br>{{ $deposit->requested_at->diffForHumans() }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($deposit->completed_at)
                                        <small class="text-success">
                                            {{ $deposit->completed_at->format('d/m/Y H:i') }}
                                            <br>{{ $deposit->completed_at->diffForHumans() }}
                                        </small>
                                        @else
                                        <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    @if($deposits->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $deposits->links() }}
                    </div>
                    @endif

                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No tienes solicitudes de depósito</h5>
                        <p class="text-muted">Cuando tengas ventas, podrás solicitar que te depositemos tus ganancias.</p>
                        @if(auth('seller')->user()->hasPaymentAccount())
                        <button class="btn btn-success mt-2" onclick="requestNewDeposit()">
                            💰 Solicitar Primer Depósito
                        </button>
                        @else
                        <a href="{{ route('tienda.payment.accounts') }}" class="btn btn-primary mt-2">
                            💳 Configurar Cuenta de Pago
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para solicitar nuevo depósito -->
<div class="modal fade" id="newDepositModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">💰 Solicitar Depósito</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="newDepositForm">
                    @csrf
                    
                    @if(auth('seller')->user()->paymentAccounts()->verified()->count() > 1)
                    <div class="mb-3">
                        <label class="form-label">Cuenta de Destino</label>
                        <select class="form-control" name="payment_account_id" required>
                            @foreach(auth('seller')->user()->paymentAccounts()->verified()->get() as $account)
                            <option value="{{ $account->id }}">
                                {!! $account->display_info !!} - {{ $account->account_holder_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    @php
                        $activeAccount = auth('seller')->user()->activePaymentAccount()->first();
                    @endphp
                    <input type="hidden" name="payment_account_id" value="{{ $activeAccount?->id }}">
                    <div class="alert alert-info">
                        <strong>Cuenta de destino:</strong><br>
                        {!! $activeAccount?->display_info !!}<br>
                        {{ $activeAccount?->account_holder_name }}
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Monto a Solicitar</label>
                        <input type="number" class="form-control" name="amount" 
                               min="1" max="999999" step="0.01" required
                               placeholder="0.00">
                        <small class="text-muted">Balance disponible: ${{ number_format($stats['pending_amount'], 2) }}</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descripción (Opcional)</label>
                        <textarea class="form-control" name="description" rows="3"
                                  placeholder="Descripción de la solicitud..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="submitNewDeposit()">
                    💰 Solicitar Depósito
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function requestNewDeposit() {
    $('#newDepositModal').modal('show');
}

function submitNewDeposit() {
    const form = document.getElementById('newDepositForm');
    const formData = new FormData(form);

    fetch('{{ route("tienda.request.deposit") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Solicitud creada: ' + data.deposit.reference);
            $('#newDepositModal').modal('hide');
            location.reload();
        } else {
            alert('❌ Error: ' + (data.message || 'Ocurrió un error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ Error de conexión');
    });
}
</script>
@endsection