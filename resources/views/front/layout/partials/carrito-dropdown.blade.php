@php
    $total = 0;
@endphp
@foreach (session('carrito', []) as $item)
    @php $total += $item['precio'] * $item['cantidad']; @endphp
    <div class="product product-cart">
        <div class="product-detail">
            <a href="#" class="product-name">{{ $item['nombre'] }}</a>
            <div class="price-box">
                <span class="product-quantity">{{ $item['cantidad'] }}</span>
                <span class="product-price">${{ $item['precio'] }}</span>
            </div>
        </div>
        <figure class="product-media">
            <img src="{{ asset('images/products/' . $item['imagen']) }}" width="84" height="94">
        </figure>
        <button class="btn btn-link btn-close" aria-label="button">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endforeach
<div class="cart-total">
    <label>Subtotal:</label>
    <span class="price">${{ number_format($total, 2) }}</span>
</div>
@scripts
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-cart-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.querySelector('.cart-count').textContent = data.carrito_count;
                    document.querySelector('.products').innerHTML = data.carrito_html;
                });
            });
        });
    });
</script>
@endscripts