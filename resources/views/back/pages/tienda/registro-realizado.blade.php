@extends('back.layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page title here')
@section('content')

<div class="login-box bg-white box-shadow border-radius-10">
  <div class="login-title">
      <h2 class="text-center text-primary">¡Bienvenido!</h2>
      <p class="text-center text-primary">Tu solicitud ha sido enviada al administrador, pronto tendrás noticias.</p>
  </div>
      <div class="row">
          <div class="col-sm-12">
              <div class="input-group mb-0">
            
                  
          
              <a href="{{ route('tienda.ingresar') }}" class="btn btn-outline-primary btn-lg btn-block">Ingresa</a>
              </div>
            
          </div>
      </div>

</div>

@endsection