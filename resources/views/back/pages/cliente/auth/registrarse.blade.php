@extends('back.layout.auth-layout')
@section('PageTitle',isset($pageTitle)? $pageTitle : 'Agro MarketPlace')
@section('content')
<div class="login-box bg-white box-shadow border-radius-10">
  <div class="login-title">
    <h2 class="text-center text-primary">Registrate</h2>
  </div>
  <form action="{{route('cliente.register')}}" method="POST">
    @csrf
    <x-alert.form-alert/>

    <div class="form-group">
      <label for="">Nombre completo: </label>
      <input type="text" class="form-control" placeholder="Escribe tu nombre completo" name="name" value="{{ old('name') }}">
      @error('name')
        <span class="text-danger ml-2">{{ $message }}</span>
      @enderror
    </div>

    
    <div class="form-group">
      <label for="">Usuario: </label>
      <input type="text" class="form-control" placeholder="Escribe tu usuario" name="username" value="{{ old('username') }}">
      @error('username')
        <span class="text-danger ml-2">{{ $message }}</span>
      @enderror
    </div>


    <div class="form-group">
      <label for="">Correo electrónico: </label>
      <input type="text" class="form-control" placeholder="Escribe tu correo electrónico" name="email" value="{{ old('email') }}">
      @error('email')
        <span class="text-danger ml-2">{{ $message }}</span>
      @enderror
    </div>

    <div class="form-group">
      <label for="">Contraseña: </label>
      <input type="password" name="password" class="form-control" placeholder="Escribe tu contraseña">
      @error('password')
        <span class="text-danger">{{ $message }}</span>
      @enderror
    </div>

    <div class="form-group">
      <label for="">Confirma tu contraseña: </label>
      <input type="password" name="password_confirmation" class="form-control" placeholder="Vuelve a escribir tu contraseña">
      @error('password_confirmation')
        <span class="text-danger">{{ $message }}</span>
      @enderror
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="input-group mb-0">
          <button type="submit" class="btn btn-primary btn-lg btn-block">Crear cuenta</button>
        </div>
        <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373" style="color:rgb(112,115,115);">O</div>
        <div class="input-group mb-0">
          <a href="{{ route('cliente.ingresar') }}" class="btn btn-outline-primary btn-lg btn-block">Ingresa</a>
        </div>
      </div>
    </div>
  </form>
</div>

@endsection