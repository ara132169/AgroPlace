@extends('back.layout.pages-layout')
@section('PageTitle',isset($pageTitle)? $pageTitle : 'Agro MarketPlace')
@section('content')
<div class="page-header">
						<div class="row">
							<div class="col-md-12 col-sm-12">
								<div class="title">
									<h4>Configuración de tienda</h4>
								</div>
								<nav aria-label="breadcrumb" role="navigation">
									<ol class="breadcrumb">
										<li class="breadcrumb-item">
											<a href="{{route('tienda.home')}}">Inicio</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">
											Configuración de tienda
										</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>

                    <div class="pd-20 bg-white border-radius-4 box-shadow mb-30">
                    <x-alert.form-alert/>
                    <form action="{{ route('tienda.configuracion-tienda') }}" method="post" enctype="multipart/form-data">
                    @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for=""><b>Nombre de tu tienda:</b></label>
                                    <input type="text" class="form-control" name="shop_name" placeholder="Ingresa el nombre de tu tienda..." value="{{ old('shop_name') ? old('shop_name') : $shopInfo->shop_name }}">
                                    @error('shop_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for=""><b>Teléfono de contacto de tu tienda:</b></label>
                                    <input type="text" class="form-control" name="shop_phone" placeholder="p.e: +52 2223323212" value="{{ old('shop_phone') ? old('shop_phone') : $shopInfo->shop_phone }}">
                                    @error('shop_phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for=""><b>Ubicación de tu tienda:</b></label>
                                    <input type="text" class="form-control" name="shop_address" placeholder="p.e: Av. México col. Zapata" value="{{ old('shop_address') ? old('shop_address') : $shopInfo->shop_address }}">
                                    @error('shop_address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-md-6">
                                <div class="form-group">
                                    <label for=""><b>Logo de tu tienda: (tamaño máximo 200px)</b></label>
                                    <input type="file" name="shop_logo" class="form-control">
                                    <div class="mb-2 mt-1" style="max-width: 200px">
                                        <img src="{{ $shopInfo->shop_logo != null ? '/images/shop/'.$shopInfo->shop_logo : '' }}" alt="" class="img-thumbnail" id="shop_logo_preview">
                                    </div>
                                    @error('shop_logo')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                
                        </div>
                        <div class="col-md-6">
                               

                                <div class="form-group">
                                    <label for=""><b>Banner de tu tienda: (tamaño sugerido 625 ancho x  300 alto)</b></label>
                                    <input type="file" name="shop_banner" class="form-control">
                                    <div class="mb-2 mt-1" style="max-width: 400px">
                                        <img src="{{ $shopInfo->shop_banner != null ? '/images/shop/'.$shopInfo->shop_banner : '' }}" alt="" class="img-thumbnail" id="shop_banner_preview">
                                    </div>
                                    @error('shop_banner')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                        </div>

                    </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for=""><b>Descripción de  tu tienda:</b></label>
                                    <textarea class="form-control" name="shop_description" cols="30" rows="10" placeholder="Cuentanos más sobre tu negocio...">{{ old('shop_description') ? old('shop_description') : $shopInfo->shop_description }}</textarea>
                                    @error('shop_description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                    </div>
    @endsection
    @push('scripts')
    <script>
        $('input[type="file"][name="shop_logo"]').ijaboViewer({
            preview:'img#shop_logo_preview',
            imageShape:'square',
            allowedExtensions:['png','jpg','svg'],
            onErrorShape:function(message,element){
                alert(message);
            },
            onInvalidType:function(message, element){
                alert(message);
            },
            onSuccess:function(message, element){}
        });
    </script>
@endpush