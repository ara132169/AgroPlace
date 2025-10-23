@extends('back.layout.auth-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Ingresar - Cliente')
@section('content')

<div class="login-box bg-white box-shadow border-radius-10">
  <div class="login-title">
      <h2 class="text-center text-primary">Ingresar</h2>
  </div>
  @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
  <form action="{{ route('cliente.login-handler') }}" method="POST">
     @csrf
     <x-alert.form-alert/>
      <div class="input-group custom">
          <input type="text" class="form-control form-control-lg" placeholder="Correo / Nombre de usuario" name="login_id" value="{{ old('login_id') }}">
          <div class="input-group-append custom">
              <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
          </div>
      </div>
      @error('login_id')
          <div class="d-block text-danger" style="margin-top: -25px;margin-bottom:15px;">
              {{ $message }}
          </div>
      @enderror
      <div class="input-group custom">
          <input type="password" class="form-control form-control-lg" placeholder="**********" name="password">
          <div class="input-group-append custom">
              <span class="input-group-text"><i class="dw dw-padlock1"></i></span>
          </div>
      </div>
      @error('password')
      <div class="d-block text-danger" style="margin-top: -25px;margin-bottom:15px;">
          {{ $message }}
      </div>
      @enderror
      <div class="row pb-30">
          <div class="col-6">
              <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="customCheck1">
                  <label class="custom-control-label" for="customCheck1">Recordarme</label>
              </div>
          </div>
          <div class="col-6">
              <div class="forgot-password text-right">
                  <a href="{{ route('client.password.request') }}">¿Olvidaste tu contraseña?</a>
              </div>
          </div>
      </div>

      <div class="row">
          <div class="col-sm-12">
              <div class="input-group mb-0">
                  <input class="btn btn-primary btn-lg btn-block" type="submit" value="Ingresar">
              </div>
          </div>
      </div><br>
      
      <div class="row ">
          <div class="col-sm-12">
          <div class="forgot-password text-center">
                  <a href="{{ route('cliente.register.form') }}">¿Aún no te registras? Da click en el enlace</a>
              </div>
          </div>
      </div>
  </form>
</div>

@endsection
