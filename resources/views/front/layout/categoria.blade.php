
@section('content')
    <div class="container">
        <!-- Mostrar el título de la categoría -->
        <h1>Productos en la categoría: {{ $category->category_name }}</h1>

        <!-- Si no hay productos, mostrar un mensaje -->
        @if(!empty($mensaje))
            <div class="text-dark">No existen productos</div>
        @else
            <!-- Mostrar los productos de la categoría -->
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
        @endif
    </div>
@endsection
