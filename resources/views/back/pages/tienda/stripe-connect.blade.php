@extends('back.layout.pages-layout')
@section('pageTitle', 'Configuración de Pagos - Stripe Connect')

@section('content')

<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>💳 Configuración de Pagos - Stripe Connect</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('tienda.home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Configuración de Pagos</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card-box mb-30">
            <div class="card-header">
                <h5 class="h5">Estado de tu Cuenta de Pagos</h5>
            </div>
            <div class="card-body">
                @if(auth('seller')->user()->hasStripeAccount())
                    <!-- Cuenta existente -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <h6><i class="fa fa-info-circle"></i> Estado de tu Cuenta Stripe</h6>
                                <ul class="mb-0">
                                    <li><strong>ID de Cuenta:</strong> {{ auth('seller')->user()->stripe_account_id }}</li>
                                    <li><strong>Estado:</strong> 
                                        @switch(auth('seller')->user()->stripe_account_status)
                                            @case('active')
                                                <span class="badge bg-success">✅ Activa</span>
                                                @break
                                            @case('restricted')
                                                <span class="badge bg-warning">⚠️ Restringida</span>
                                                @break
                                            @default
                                                <span class="badge bg-secondary">⏳ Pendiente</span>
                                        @endswitch
                                    </li>
                                    <li><strong>Cobros habilitados:</strong> 
                                        @if(auth('seller')->user()->stripe_charges_enabled)
                                            <span class="text-success">✅ Sí</span>
                                        @else
                                            <span class="text-danger">❌ No</span>
                                        @endif
                                    </li>
                                    <li><strong>Retiros habilitados:</strong> 
                                        @if(auth('seller')->user()->stripe_payouts_enabled)
                                            <span class="text-success">✅ Sí</span>
                                        @else
                                            <span class="text-danger">❌ No</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="alert alert-success">
                                <h6><i class="fa fa-dollar-sign"></i> Información de Comisiones</h6>
                                <ul class="mb-0">
                                    <li><strong>Comisión de plataforma:</strong> {{ auth('seller')->user()->commission_rate }}%</li>
                                    <li><strong>Tu porcentaje:</strong> {{ 100 - auth('seller')->user()->commission_rate }}%</li>
                                    <li class="mt-2"><small class="text-muted">Por cada venta, recibes el {{ 100 - auth('seller')->user()->commission_rate }}% del monto total. La plataforma retiene el {{ auth('seller')->user()->commission_rate }}% como comisión.</small></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            @if(!auth('seller')->user()->isStripeAccountActive())
                                <div class="alert alert-warning">
                                    <h6><i class="fa fa-exclamation-triangle"></i> Acción Requerida</h6>
                                    <p>Tu cuenta de Stripe Connect necesita información adicional para procesar pagos completamente.</p>
                                    <form action="{{ route('tienda.stripe.connect') }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fa fa-edit"></i> Completar Configuración
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="alert alert-success">
                                    <h6><i class="fa fa-check-circle"></i> ¡Cuenta Completamente Configurada!</h6>
                                    <p>Tu cuenta está lista para recibir pagos. Los clientes pueden realizar compras y recibirás el dinero automáticamente.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6>Acciones Disponibles:</h6>
                            <div class="btn-group" role="group">
                                <form action="{{ route('tienda.stripe.refresh') }}" method="GET" style="display: inline;">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fa fa-refresh"></i> Actualizar Estado
                                    </button>
                                </form>
                                
                                @if(!auth('seller')->user()->isStripeAccountActive())
                                    <form action="{{ route('tienda.stripe.connect') }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-cog"></i> Configurar Cuenta
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                @else
                    <!-- Sin cuenta -->
                    <div class="text-center">
                        <div class="alert alert-info">
                            <h5><i class="fa fa-info-circle"></i> Configuración de Pagos Requerida</h5>
                            <p class="lead">Para recibir pagos de tus ventas, necesitas configurar una cuenta de Stripe Connect.</p>
                            <p>Esto te permitirá:</p>
                            <ul class="text-left d-inline-block">
                                <li>✅ Recibir pagos automáticamente</li>
                                <li>✅ Gestionar tus transferencias</li>
                                <li>✅ Ver el historial de transacciones</li>
                                <li>✅ Configurar tu información bancaria</li>
                            </ul>
                        </div>

                        <div class="mt-4">
                            <form action="{{ route('tienda.stripe.connect') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa fa-credit-card"></i> Configurar Cuenta de Pagos
                                </button>
                            </form>
                            <p class="text-muted mt-2">
                                <small>Serás redirigido a Stripe para completar la configuración de forma segura.</small>
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(auth('seller')->user()->hasStripeAccount())
<div class="row">
    <div class="col-md-12">
        <div class="card-box mb-30">
            <div class="card-header">
                <h5 class="h5">Información Importante</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fa fa-shield-alt text-success"></i> Seguridad</h6>
                        <ul>
                            <li>Todos los pagos se procesan de forma segura a través de Stripe</li>
                            <li>Tu información bancaria está protegida con cifrado de nivel bancario</li>
                            <li>Los pagos se transfieren automáticamente a tu cuenta</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fa fa-calendar text-info"></i> Tiempos de Transferencia</h6>
                        <ul>
                            <li>Las transferencias se realizan automáticamente</li>
                            <li>Los fondos llegan a tu cuenta en 1-3 días hábiles</li>
                            <li>Puedes ver el estado de cada transferencia en Stripe</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Mostrar mensajes de éxito/error
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif
    
    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif
    
    @if(session('warning'))
        toastr.warning('{{ session('warning') }}');
    @endif
});
</script>
@endpush