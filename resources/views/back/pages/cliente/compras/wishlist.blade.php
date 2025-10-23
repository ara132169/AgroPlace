{{-- resources/views/back/pages/cliente/wishlist.blade.php --}}
@extends('back.layout.pages-layout')
@section('PageTitle', 'Wishlist')
@section('content')
<div class="row">
  @forelse($productos as $producto)
  <div class="col-md-4">
    <div class="card-box">
      <h5 class="h5">{{ $producto->name }}</h5>
      <p>Precio: ${{ $producto->price }}</p>
      <a href="#" class="btn btn-sm btn-primary">Ver producto</a>
    </div>
  </div>
  @empty
  <div class="col-md-12 text-center">
    <p>No tienes productos en tu lista de deseos.</p>
  </div>
  @endforelse
</div>
@endsection