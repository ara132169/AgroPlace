

@section('content')
    <div class="container">
        <h2>Mi Wishlist</h2>

        @if(count($wishlist) > 0)
            <div class="row">
                @foreach($wishlist as $productId)
                    @php
                        $product = App\Models\Product::find($productId);
                    @endphp
                    @if($product)
                        <div class="col-md-3">
                            <div class="card">
                                <img src="{{ asset('images/products/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">{{ $product->description }}</p>
                                    <a href="{{ route('tienda.producto.detalle', $product->slug) }}" class="btn btn-primary">Ver Producto</a>
                                    <form action="{{ route('cart.agregar', $product->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success">Añadir al Carrito</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <p>No tienes productos en tu wishlist.</p>
        @endif
    </div>
@endsection
