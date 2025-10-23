

@extends('back.layout.auth-layout')
@section('PageTitle',isset($pageTitle)? $pageTitle : 'Agro MarketPlace')
@section('content')


<div class="login-box bg-white box-shadow border-radius-10">


  <div class="login-title">
    <h2 class="text-center text-primary">Actualiza tu contraseña</h2>
  </div>
  
  @if ($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            <ul class="list-disc ml-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
  <form action="{{route('client.password.update')}}" method="POST">
    @csrf
    <div class="form-group">
      <label for="">Correo electrónico: </label>
      <input id="email" type="email" class="form-control" placeholder="Escribe tu correo electrónico" name="email" value="{{ old('email', $email) }}">
    </div>

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group">
      <label for="">Ingresa tu nueva contraseña: </label>
      <input id="password" type="password" name="password" class="form-control" placeholder="">

    </div>

    <div class="form-group">
      <label for="">Confirma tu nueva contraseña: </label>
      <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="">

    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="input-group mb-0">
          <button type="submit" class="btn btn-primary btn-lg btn-block">Actualizar contraseña</button>
        </div>
      </div>
    </div>


  </form>
</div>

@endsection
