{{-- resources/views/back/pages/cliente/compras.blade.php --}}
@extends('back.layout.pages-layout')
@section('PageTitle', 'Mis Compras')
@section('content')
<div class="card-box mb-30">
  <h4 class="h4 text-blue mb-20">Mis Compras</h4>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Producto</th>
          <th>Precio</th>
          <th>Fecha</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        @forelse($compras as $compra)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $compra->product_name }}</td>
          <td>${{ $compra->price }}</td>
          <td>{{ $compra->created_at->format('d/m/Y') }}</td>
          <td>{{ ucfirst($compra->status) }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center">Aún no has realizado ninguna compra.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection