@extends('back.layout.pages-layout')
@section('PageTitle',isset($pageTitle)? $pageTitle : 'Agro MarketPlace')
@section('content')
        <div class="row">
            <div class="col-md-12">
                <div class="pd-20 card-box mb-30">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4 class="text-dark">Añadir categoría</h4>
                        </div>
                        <div class="pull-right">
                            <a href="{{ route('admin.manage-categories.cats-subcats-list') }}" class="btn btn-primary btn-sm">
                            <i class="ion-arrow-left-a"></i> Regresar a la lista de categorías
                            </a>
                        </div>
                    </div>
                    <hr>
                    <form action="{{ route('admin.manage-categories.store-category') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                        @csrf
                        @if (Session::get('success'))
                            <div class="alert alert-success">
                                <strong><i class="dw dw-checked"></i></strong>
                                {!! Session::get('success') !!}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if (Session::get('fail'))
                        <div class="alert alert-danger">
                            <strong><i class="dw dw-checked"></i></strong>
                            {!! Session::get('fail') !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="">Nombre de categoría</label>
                                <input type="text" class="form-control" name="category_name" placeholder="Ingresa el nombre de la categoría" value="{{ old('category_name') }}">
                                @error('category_name')
                                    <span class="text-danger ml-2">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="">Icono de categoría</label>
                                <input type="file" name="category_image" id="" class="form-control">
                                @error('category_image')
                                    <span class="text-danger ml-2">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                            <div class="avatar mb-3">
                                <img src="" alt="" data-ijabo-default-img="" width="50" height="50" id="category_image_preview">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">AGREGAR</button>
                    </form>
                </div>
            </div>
        </div>
@endsection
@push('scripts')
    <script>
        $('input[type="file"][name="category_image"]').ijaboViewer({
            preview:'#category_image_preview',
            imageShape:'square',
            allowedExtensions:['png','jpg','jpeg','svg'],
            onErrorShape:function(message,element){
                alert(message);
            },
            onInvalidType:function(message,element){
                alert(message);
            },
            onSuccess:function(message,element){}
        });
    </script>
@endpush
