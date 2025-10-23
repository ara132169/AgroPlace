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

@livewire('client.client-profile') {{-- Aquí iría un componente Livewire para el perfil del cliente --}}
@endsection

@push('scripts')
<script>
    $('input[type="file"][name="clientProfilePicture"][id="clientProfilePicture"]').ijaboCropTool({
        preview : '#clientProfilePicture',
        setRatio: 1,
        allowedExtensions: ['jpg', 'jpeg', 'png'],
        buttonsText: ['CROP','QUIT'],
        buttonsColor: ['#30bf7d','#ee5155'],
        processUrl: '', // Puedes agregar URL para procesamiento
        withCSRF: ['_token','{{ csrf_token() }}'],
        onSuccess: function(message, element, status){
            toastr.success(message);
        },
        onError: function(message, element, status){
            toastr.error(message);
        }
    });
</script>
@endpush
