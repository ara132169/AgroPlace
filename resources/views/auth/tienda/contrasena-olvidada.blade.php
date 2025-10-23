@extends('back.layout.auth-layout')
@section('PageTitle',isset($pageTitle)? $pageTitle : 'Agro MarketPlace')
@section('content')

<div class="login-box bg-white box-shadow border-radius-10">

  <div class="login-title">
      <h2 class="text-center text-primary">Restablece tu contraseña </h2>
  </div>

  
    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            <ul class="list-disc ml-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
  <form action="{{ route('tienda.password.email') }}" method="POST">
     @csrf
    
      <div class="input-group custom">
          <input type="email" class="form-control form-control-lg" placeholder="Correo registrado" name="email">
          <div class="input-group-append custom">
              <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
          </div>
      </div>
   
 
      <div class="row">
          <div class="col-sm-12">
              <div class="input-group mb-0">
            
                  <input class="btn btn-primary btn-lg btn-block" type="submit" value="Enviar enlace ">
          
                  {{-- <a class="btn btn-primary btn-lg btn-block" href="index.html">Ingresar</a> --}}
              </div>
            
          </div>
      </div>
  </form>
</div>

@endsection

