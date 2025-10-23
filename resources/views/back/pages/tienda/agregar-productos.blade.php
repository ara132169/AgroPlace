@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Agregar productos')
@section('content')

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Añadir producto</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('tienda.home') }}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Añadir producto
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="{{ route('tienda.product.productos') }}" class="btn btn-primary">Ver todos los productos</a>
        </div>
    </div>
</div>

<form action="{{ route('tienda.product.crear-producto') }}" method="POST" enctype="multipart/form-data" id="addProductForm">
    @csrf
    <div class="row pd-10">
        <div class="col-md-8 mb-20">
            <div class="card-box height-100-p pd-20" style="position: relative">
                <div class="form-group">
                    <label for=""><b>Nombre del producto:</b></label>
                    <input type="text" class="form-control" name="name" placeholder="Enter product name">
                    @error('name')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for=""><b>Descripción:</b></label>
                    <textarea  id="summary" name="summary" class="form-control summernote" cols="30" rows="10"></textarea>
                    @error('summary')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for=""><b>Imagen de producto:</b><small>Tamaño máximo (1080x1080)</small></label>
                    <input type="file" name="product_image" class="form-control">
                    @error('product_image')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="d-block mb-3" style="max-width: 250px;">
                  <img src="" class="img-thumbnail" id="image-preview" data-ijabo-default-img="">
                </div>
              
                
                <b>Nota</b>:<small class="text-danger">Podrás agregar más imágenes relacionadas con este producto cuando esté en la página de edición del producto.</small>
            </div>
        </div>
        <div class="col-md-4 mb-20">
            <div class="card-box min-height-200px pd-20 mb-20">
                <div class="form-group">
                    <label for=""><b>Categoría:</b></label>
                    <select name="category" id="category" class="form-control">
                        <option value="" selected>No seleccionada</option>
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}">{{ $item->category_name }}</option>
                        @endforeach
                    </select>
                    @error('category')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for=""><b>Sub Categoría:</b></label>
                    <select name="subcategory" id="subcategory" class="form-control">
                        <option value="" selected>No seleccionada</option>
                    </select>
                    @error('subcategory')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>
            <div class="card-box min-height-200px pd-20 mb-20">
                <div class="form-group">
                    <label for=""><b>Precio:</b><small>Moneda Mexicana ($)</small></label>
                    <input type="text" name="price" class="form-control" placeholder="P.ej: 23.99">
                    @error('price')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for=""><b>Comparar precio:</b><small>Opcional</small></label>
                    <input type="text" name="compare_price" class="form-control" placeholder="P.ej: 77.99">
                    @error('compare_price')
                      <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="card-box min-height-120px pd-20">
               <div class="form-group">
                 <label for=""><b>Visibilidad:</b></label>
                 <select name="visibility" id="" class="form-control">
                    <option value="1" selected>Publico</option>
                    <option value="0">Privado</option>
                 </select>
               </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Agregar  producto</button>
    </div>
</form>
@endsection

@push('scripts')
    <script>
       $(document).on('change', '#category', function(e) {
    e.preventDefault();
    var category_id = $(this).val();
    var url = "{{ route('tienda.product.get-product-category') }}";

    if (!category_id) {
        $('#subcategory').html('<option value="">No seleccionada</option>');
        return;
    }

    $.ajax({
        url: url,
        method: 'GET',
        data: { category_id: category_id },
        dataType: 'json',
        success: function(response) {
            if (response.status == 1) {
                $('#subcategory').html('<option value="">No seleccionada</option>' + response.data);
            } else {
                $('#subcategory').html('<option value="">No hay subcategorías disponibles</option>');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en AJAX:", error);
            $('#subcategory').html('<option value="">Error al cargar subcategorías</option>');
        }
    });
});
</script>
<script>
    $('#addProductForm').on('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: '{{ route("tienda.product.crear-producto") }}', // Asegúrate de poner el route correcto
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status === 1) {
                    toastr.success(response.msg);
                    $('#addProductForm')[0].reset(); // limpia el form
                } else {
                    toastr.error(response.msg);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    // errores de validación
                    let errors = xhr.responseJSON.errors;
                    for (let field in errors) {
                        toastr.error(errors[field][0]);
                    }
                } else {
                    toastr.error('Ocurrió un error inesperado.');
                }
            }
        });
    });
</script>
@endpush