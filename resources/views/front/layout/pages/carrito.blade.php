

@section('content')
    <div class="container">
        <h2>Mi Carrito</h2>

        @if(count($cart) > 0)
            <div class="row">
                @foreach($cart as $productoId => $quantity)
                    @php
                        $producto = App\Models\producto::find($productoId);
                    @endphp
                    @if($producto)
                        <div class="col-md-3">
                            <div class="card">
                                <img src="{{ asset('images/productos/' . $producto->image) }}" class="card-img-top" alt="{{ $producto->name }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $producto->name }}</h5>
                                    <p class="card-text">{{ $producto->description }}</p>
                                    <p class="card-text">Cantidad: {{ $quantity }}</p>
                                    <p class="card-text">Precio: ${{ $producto->price }}</p>
                                    <form action="" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <p>No tienes productos en tu carrito.</p>
        @endif

        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('tienda.checkout') }}" class="btn btn-success">Proceder al Pago</a>
            </div>
        </div>
    </div>
@endsection
