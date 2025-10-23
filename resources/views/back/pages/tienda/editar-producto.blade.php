@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page title here')
@section('content')

<div class="page-header">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="title">
                <h4>Editar producto</h4>
            </div>
            <nav aria-label="breadcrumb" role="navigation">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('tienda.home') }}">Inicio</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Editar producto
                    </li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
            <a href="{{ route('tienda.product.productos') }}" class="btn btn-primary">Ver todos los productos</a>
        </div>
    </div>
</div>

<form action="{{ route('tienda.product.actualizar-producto') }}" method="POST" enctype="multipart/form-data" id="updateProductForm">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">

    <div class="row pd-10">
        <div class="col-md-8 mb-20">
            <div class="card-box height-100-p pd-20" style="position: relative">
                <div class="form-group">
                    <label><b>Nombre del producto:</b></label>
                    <input type="text" class="form-control" name="name" placeholder="Ingresa el nombre del producto" value="{{ $product->name }}">
                    <span class="text-danger error-text name_error"></span>
                </div>

                <div class="form-group">
                    <label><b>Descripción del producto:</b></label>
                    <textarea name="summary" id="summary" class="form-control summernote" cols="30" rows="10">{!! $product->summary !!}</textarea>
                    <span class="text-danger error-text summary_error"></span>
                </div>

                <div class="form-group">
                    <label><b>Imagen del producto:</b><small> Tamaño máximo (1080x1080)</small></label>
                    <input type="file" name="product_image" class="form-control">
                    <span class="text-danger error-text product_image_error"></span>
                </div>

                <div class="d-block mb-3" style="max-width: 250px;">
                    <img src="{{ asset('images/products/' . $product->product_image) }}" class="img-thumbnail" id="image-preview">
                </div>

                <div class="form-group">
                    <label><b>Imágenes adicionales:</b><small> Tamaño máximo (1080x1080)</small></label>
                    <input type="file" name="additional_images[]" multiple accept="image/*">
                    <span class="text-danger error-text additional_images_error"></span>
                </div>

               

                <b>Nota:</b>
                <small class="text-danger">Podrás agregar más imágenes relacionadas con este producto cuando esté en la página de edición del producto.</small>
            </div>
        </div>

        <div class="col-md-4 mb-20">
            <div class="card-box min-height-200px pd-20 mb-20">
                <div class="form-group">
                    <label><b>Categoría:</b></label>
                    <select name="category" id="category" class="form-control">
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}" {{ $product->category == $item->id ? 'selected' : '' }}>{{ $item->category_name }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger error-text category_error"></span>
                </div>

                <div class="form-group">
                    <label><b>Sub categoría:</b></label>
                    <select name="subcategory" id="subcategory" class="form-control">
                        <option value="" selected>No seleccionada</option>
                        @foreach ($subcategories as $item)
                            <option value="{{ $item->id }}" {{ $item->id == $product->subcategory ? 'selected' : '' }}>{{ $item->subcategory_name }}</option>
                            @if (count($item->children) > 0)
                                @foreach ($item->children as $child)
                                    <option value="{{ $child->id }}" {{ $child->id == $product->subcategory ? 'selected' : '' }}>-- {{ $child->subcategory_name }}</option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                    <span class="text-danger error-text subcategory_error"></span>
                </div>
            </div>

            <div class="card-box min-height-200px pd-20 mb-20">
                <div class="form-group">
                    <label><b>Precio:</b><small> Moneda Mexicana ($)</small></label>
                    <input type="text" name="price" class="form-control" placeholder="Ej: 23.99" value="{{ $product->price }}">
                    <span class="text-danger error-text price_error"></span>
                </div>

                <div class="form-group">
                    <label><b>Comparar Precio:</b><small> Opcional</small></label>
                    <input type="text" name="compare_price" class="form-control" placeholder="Ej: 77.99" value="{{ $product->compare_price }}">
                    <span class="text-danger error-text compare_price_error"></span>
                </div>
            </div>

            <div class="card-box min-height-120px pd-20">
                <div class="form-group">
                    <label><b>Visibilidad:</b></label>
                    <select name="visibility" class="form-control">
                        <option value="1" {{ $product->visibility == 1 ? 'selected' : '' }}>Público</option>
                        <option value="0" {{ $product->visibility == 0 ? 'selected' : '' }}>Privado</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group text-center">
        <button type="submit" class="btn btn-primary">Actualizar producto</button>
    </div>
</form>

<hr>



@endsection
@push('stylesheets')
    <link rel="stylesheet" href="/extra-assets/dropzonejs/min/dropzone.min.css">
    <style>
        .box-container{
            width: 100%;
            display: flex;
            flex-direction: row;
            gap: 1rem;
            justify-content: flex-start;
            flex-wrap: wrap;
        }

        .box-container .box{
            background: #423838;
            display: block;
            width: 110px;
            height: 110px;
            position: relative;
            overflow: hidden;
        }
        .box-container .box img{
            width: 100%;
            height: auto;
        }

        .box-container .box a{
            position: absolute;
            right: 7px;
            bottom: 5px;
        }

        .swal2-popup{
            font-size: .87em;
        }
    </style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
    Dropzone.autoDiscover = false;

    // PRODUCTO
    $('#updateProductForm').on('submit', function(e) {
        e.preventDefault();
        let form = this;
        let formdata = new FormData(form);
        formdata.append('summary', $('textarea.summernote').summernote('code'));

        $.ajax({
            url: $(form).attr('action'),
            method: 'POST',
            data: formdata,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: () => {
                toastr.remove();
                $(form).find('span.error-text').text('');
            },
            success: (res) => {
                if (res.status === 1) {
                    toastr.success(res.msg);
                    $('#image-preview').attr('src', '/images/products/' + res.product.product_image);
                } else {
                    toastr.error(res.msg);
                }
            },
            error: (res) => {
                $.each(res.responseJSON.errors, function(key, val) {
                    $('span.' + key + '_error').text(val[0]);
                });
            }
        });
    });

    // DROPZONE
    let myDropzone = new Dropzone('#additionalImageDropzone', {
        autoProcessQueue: false,
        paramName: "file",
        maxFilesize: 5,
        acceptedFiles: 'image/*',
        addRemoveLinks: true,
        init: function () {
            let dz = this;
            $('#uploadAdditionalImagesBtn').on('click', function () {
                dz.processQueue();
            });
            dz.on('queuecomplete', function () {
                dz.removeAllFiles();
                getProductImages();
            });
        }
    });

    function getProductImages() {
        $.get("{{ route('tienda.product.obtener-imagen-productos', ['product_id' => $product->id]) }}", function(res) {
            $('#product_images').html(res.data);
        });
    }

    getProductImages();
</script>

   
@endpush