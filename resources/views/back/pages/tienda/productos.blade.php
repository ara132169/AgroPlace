@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Productos agregados')
@section('content')

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Mis productos</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('tienda.home') }}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Mis productos
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="{{ route('tienda.product.agregar-productos') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i> Agregar nuevo producto</a>
        </div>
    </div>
</div>
@livewire('seller.products')
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@push('scripts')
    <script>
        $(document).on('click','a#deleteProductBtn', function(e){
             e.preventDefault();
             var url = "{{ route('tienda.product.eliminar-producto') }}";
             var token = "{{ csrf_token() }}";
             var product_id = $(this).data('id');
             Swal.fire({
                title:'¿Estás seguro?',
                html:'Eliminarás este producto',
                showCloseButton:true,
                showCancelButton:true,
                cancelButtonText:'Cancelar',
                confirmButtonText:'Si, Eliminar',
                cancelButtonColor:'#d33',
                confirmButtonColor:'#1254a1',
                width:300,
                allowOutsideClick:false
             }).then(function(result){
                if( result.value ){
                    $.post(url,{ _token:token, product_id:product_id }, function(response){
                         toastr.remove();
                         if( response.status == 1 ){
                            Livewire.dispatch('refreshProductsList');
                            toastr.success(response.msg);
                         }else{
                            toastr.error(response.msg);
                         }
                    },'json');
                }
             });
        });
    </script>
@endpush
