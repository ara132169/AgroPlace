@extends('back.layout.pages-layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">💳 Configuración de Pagos - Stripe Connect</h4>
                </div>
                <div class="card-body">
                    
                    @if($seller->stripe_account_id && $seller->stripe_account_status === 'active')
                        <!-- CUENTA YA CONECTADA -->
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle"></i> Cuenta Stripe Conectada</h5>
                            <p class="mb-2">Tu cuenta está configurada correctamente. Los pagos se dividirán automáticamente:</p>
                            <ul class="mb-0">
                                <li><strong>85%</strong> se deposita directamente en tu cuenta</li>
                                <li><strong>15%</strong> se retiene como comisión de la plataforma</li>
                            </ul>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6>ID de Cuenta</h6>
                                        <code>{{ $seller->stripe_account_id }}</code>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <h6>Estado</h6>
                                        <span class="badge badge-success">{{ ucfirst($seller->stripe_account_status) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="text-center">
                            <a href="{{ route('tienda.stripe.dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-external-link-alt"></i> Acceder a Dashboard Stripe
                            </a>
                            <button type="button" class="btn btn-outline-danger" onclick="desconectarCuenta()">
                                <i class="fas fa-unlink"></i> Desconectar Cuenta
                            </button>
                        </div>
                        
                    @else
                        <!-- CUENTA NO CONECTADA -->
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> Conecta tu Cuenta Stripe</h5>
                            <p class="mb-0">Para recibir pagos automáticamente y evitar demoras, conecta tu cuenta de Stripe.</p>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <i class="fas fa-clock text-warning fa-2x mb-2"></i>
                                    <h6>Sin Stripe Connect</h6>
                                    <small class="text-muted">Pagos procesados manualmente<br>Demora: 3-5 días</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3">
                                    <i class="fas fa-arrow-right fa-2x text-primary"></i>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded bg-success text-white">
                                    <i class="fas fa-bolt fa-2x mb-2"></i>
                                    <h6>Con Stripe Connect</h6>
                                    <small>Pagos automáticos<br>Inmediato: 85% directo</small>
                                </div>
                            </div>
                        </div>
                        
                        @if($connectAvailable)
                            <div class="text-center">
                                <form action="{{ route('tienda.stripe.connect') }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fab fa-stripe"></i> Conectar con Stripe
                                    </button>
                                </form>
                                <br><small class="text-muted mt-2">Proceso seguro y certificado por Stripe</small>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-exclamation-triangle"></i> Stripe Connect no disponible</h6>
                                <p class="mb-2">Actualmente Stripe Connect tiene restricciones en México. Opciones:</p>
                                <ul class="mb-0">
                                    <li>Continuar con pagos manuales</li>
                                    <li>Esperar expansión de Stripe Connect en MX</li>
                                    <li>Configurar cuenta internacional (US/EU)</li>
                                </ul>
                            </div>
                        @endif
                        
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function desconectarCuenta() {
    if(confirm('¿Estás seguro de desconectar tu cuenta Stripe? Los futuros pagos se procesarán manualmente.')) {
        fetch('{{ route("tienda.stripe.disconnect") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if(response.ok) {
                location.reload();
            } else {
                alert('Error al desconectar cuenta');
            }
        });
    }
}
</script>
@endsection