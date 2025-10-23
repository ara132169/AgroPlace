@extends('front.layout.pages-layout')


@section('content')
    <div class="container">
        <h1>Productos de la subsubcategoría: {{ $subsubcategory->subcategory_name }}</h1>

        <div class="row">
            @foreach($productos as $producto)
                <div class="col-md-4">
                    <div class="product-card">
                        <img src="{{ asset('images/products/' . $producto->product_image) }}" alt="{{ $producto->name }}">
                        <h4>{{ $producto->name }}</h4>
                        <p>{{ $producto->summary }}</p>
                        <p><strong>${{ number_format($producto->price, 2) }}</strong></p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
