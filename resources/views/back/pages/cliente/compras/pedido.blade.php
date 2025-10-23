{{-- resources/views/back/pages/cliente/pedido.blade.php --}}
@extends('back.layout.pages-layout')
@section('PageTitle', 'Realizar Pedido')
@section('content')
<div class="card-box">
  <h4 class="h4 text-blue mb-20">Resumen del Pedido</h4>
  <form action="#" method="POST">
    @csrf
    <div class="form-group">
      <label for="direccion">Dirección de envío</label>
      <input type="text" name="direccion" id="direccion" class="form-control" placeholder="Ej: Calle Falsa 123">
    </div>
    <div class="form-group">
      <label for="metodo_pago">Método de pago</label>
      <select name="metodo_pago" class="form-control">
        <option value="efectivo">Efectivo</option>
        <option value="tarjeta">Tarjeta</option>
      </select>
    </div>
    <button class="btn btn-success">Confirmar Pedido</button>
  </form>
</div>
@endsection
