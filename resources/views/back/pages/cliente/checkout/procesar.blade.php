@extends('back.layout.master')
@section('pageTitle', 'Finalizar Compra')
@section('content')
<div class="container mt-5">
    <h3 class="mb-4">Resumen de tu compra</h3>

    @if($cartItems->isEmpty())
        <div class="alert alert-info">Tu carrito está vacío.</div>
    @else
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach ($cartItems as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>${{ number_format($item->product->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                    </tr>
                    @php $total += $item->product->price * $item->quantity; @endphp
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="text-end mt-4">
        <h5>Total a pagar: <strong>${{ number_format($total, 2) }}</strong></h5>

        <form action="{{ route('cliente.checkout.procesar') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success btn-lg mt-2">Confirmar Pedido</button>
        </form>
    </div>
    @endif
</div>
@endsection
