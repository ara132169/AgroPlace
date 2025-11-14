@extends('back.layout.pages-layout')
@section('PageTitle', isset($pageTitle) ? $pageTitle : 'Agro MarketPlace - Cliente')

@section('content')
<div class="page-header">
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="title">
                <h4>Perfil del Cliente</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Perfil
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

{{-- Mensajes de sesión --}}
@if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>¡Éxito!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>¡Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@livewire('client.client-profile') {{-- Aquí iría un componente Livewire para el perfil del cliente --}}
@endsection

@push('scripts')
<script>
    // Manejo de cambio de imagen de perfil con Livewire
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('clientProfilePicture');
        const preview = document.getElementById('clientProfilePicturePreview');
        
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validar que sea una imagen
                    if (!file.type.startsWith('image/')) {
                        alert('Por favor selecciona un archivo de imagen válido.');
                        this.value = ''; // Limpiar el input
                        return;
                    }
                    
                    // Validar tamaño (max 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('El archivo es demasiado grande. El máximo permitido es 2MB.');
                        this.value = ''; // Limpiar el input
                        return;
                    }
                    
                    // Mostrar preview inmediato
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (preview) {
                            preview.src = e.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                    
                    // Livewire se encarga del resto automáticamente
                }
            });
        }
    });

    // Escuchar eventos de Livewire para mostrar mensajes
    document.addEventListener('livewire:load', function () {
        Livewire.on('imageUploaded', message => {
            toastr.success(message);
        });
        
        Livewire.on('imageError', message => {
            toastr.error(message);
        });
    });
</script>
@endpush
