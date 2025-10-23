<!-- resources/views/front/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row mb-3">
            <div class="col-md-3">
                <select wire:model="categoria" class="form-control">
                    <option value="">-- Categoría --</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select wire:model="orden" class="form-control">
                    <option value="recientes">Más recientes</option>
                    <option value="precio_asc">Precio: menor a mayor</option>
                    <option value="precio_desc">Precio: mayor a menor</option>
                </select>
            </div>

            <div class="col-md-3">
                <input wire:model="precioMin" type="number" placeholder="Precio mínimo" class="form-control">
            </div>

            <div class="col-md-3">
                <input wire:model="precioMax" type="number" placeholder="Precio máximo" class="form-control">
            </div>
        </div>

        <div class="row">
            @forelse($productos as $producto)
                <div class="col-md-3 mb-4">
                    <div class="card h-100">
                        <img src="{{ asset('images/products/' . $producto->product_image) }}" class="card-img-top" alt="{{ $producto->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $producto->name }}</h5>
                            <p class="card-text">${{ number_format($producto->price, 2) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-12">No hay productos disponibles.</p>
            @endforelse
        </div>

        <div class="d-flex justify-content-center">
            {{ $productos->links() }}
        </div>
    </div>
@endsection
