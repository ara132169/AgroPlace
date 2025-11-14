@extends('back.layout.auth-layout')
@section('pageTitle', 'Registro Exitoso - AgroPlace')
@section('content')

<div class="login-box bg-white box-shadow border-radius-10">
    <div class="login-title text-center">
        <div style="font-size: 4rem; color: #28a745; margin-bottom: 20px;">
            ✅
        </div>
        <h2 class="text-success mb-3">¡Registro Exitoso!</h2>
        <p class="text-muted lead">
            Tu solicitud para registrarte como tienda en <strong>AgroPlace</strong> ha sido recibida exitosamente.
        </p>
    </div>

    <div class="card border-0 bg-light mb-4">
        <div class="card-body">
            <h5 class="card-title text-primary">
                <i class="fa fa-info-circle"></i> ¿Qué sigue ahora?
            </h5>
            <div class="timeline">
                <div class="timeline-item mb-3">
                    <div class="d-flex align-items-center">
                        <div class="timeline-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; margin-right: 15px;">
                            <i class="fa fa-check" style="font-size: 12px;"></i>
                        </div>
                        <div>
                            <strong>✅ Registro completado</strong>
                            <p class="mb-0 text-muted small">Tu información ha sido guardada correctamente</p>
                        </div>
                    </div>
                </div>
                
                <div class="timeline-item mb-3">
                    <div class="d-flex align-items-center">
                        <div class="timeline-icon bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; margin-right: 15px;">
                            <i class="fa fa-clock-o" style="font-size: 12px;"></i>
                        </div>
                        <div>
                            <strong>🔍 Revisión administrativa</strong>
                            <p class="mb-0 text-muted small">Nuestro equipo revisará tu solicitud (1-3 días hábiles)</p>
                        </div>
                    </div>
                </div>
                
                <div class="timeline-item mb-3">
                    <div class="d-flex align-items-center">
                        <div class="timeline-icon bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; margin-right: 15px;">
                            <i class="fa fa-envelope" style="font-size: 12px;"></i>
                        </div>
                        <div>
                            <strong>📧 Notificación por correo</strong>
                            <p class="mb-0 text-muted small">Te enviaremos un email cuando tu cuenta sea aprobada</p>
                        </div>
                    </div>
                </div>
                
                <div class="timeline-item">
                    <div class="d-flex align-items-center">
                        <div class="timeline-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; margin-right: 15px;">
                            <i class="fa fa-store" style="font-size: 12px;"></i>
                        </div>
                        <div>
                            <strong>🚀 ¡Comienza a vender!</strong>
                            <p class="mb-0 text-muted small">Configura tu tienda y publica tus productos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="fa fa-envelope"></i>
        <strong>¡Revisa tu correo!</strong><br>
        Hemos enviado un correo de confirmación con todos los detalles del proceso.
        <br><small class="text-muted">Si no lo encuentras, revisa tu carpeta de spam.</small>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <a href="{{ route('tienda.ingresar') }}" class="btn btn-primary btn-lg btn-block">
                <i class="fa fa-sign-in"></i> Iniciar Sesión
            </a>
        </div>
        <div class="col-sm-6">
            <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-lg btn-block">
                <i class="fa fa-home"></i> Ir al Inicio
            </a>
        </div>
    </div>

    <div class="text-center mt-4">
        <p class="text-muted small">
            ¿Tienes preguntas? <a href="mailto:soporte@agroplace.com">Contáctanos</a>
        </p>
    </div>
</div>

<style>
.timeline-icon {
    min-width: 30px;
    min-height: 30px;
}
.timeline-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 14px;
    top: 35px;
    height: 20px;
    width: 2px;
    background-color: #dee2e6;
    margin-left: 15px;
}
.timeline-item {
    position: relative;
}
</style>

@endsection