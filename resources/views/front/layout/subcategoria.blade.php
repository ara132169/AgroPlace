@extends('front.layout.pages-layout')

@section('content')
    <h1>Productos en la subcategoría: {{ $subcategory->subcategory_name }} de {{ $category->category_name }}</h1>

    <div class="productos">
        @foreach($productos as $producto)
            <div class="producto">
                <img src="{{ asset('images/products/' . $producto->product_image) }}" alt="{{ $producto->name }}">
                <h2>{{ $producto->name }}</h2>
                <p>{{ $producto->summary }}</p>
                <p>Precio: ${{ $producto->price }}</p>
                <a href="{{ route('product.show', $producto->slug) }}">Ver más</a>
            </div>
        @endforeach
    </div>
@endsection
