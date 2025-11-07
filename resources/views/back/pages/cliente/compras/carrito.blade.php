@php
    use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
@include('front.layout.inc.head')
</head>


<body class="home">
    <!-- start of .page-wrapper -->
    <div class="page-wrapper">
        <h1 class="d-none">Agro - MarketPlace</h1>
        <!-- Start of Header -->
		@include('front.layout.inc.headerdos')
        <!-- End of Header -->
<br>
<main class="main cart">
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb shop-breadcrumb bb-no">
                        <li class="active"><a href="cart.html">Carrito de compra</a></li>
                        <li><a href="checkout.html">Finalizar compra</a></li>
                        <li><a href="order.html">Completar orden</a></li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->

            <!-- Start of PageContent -->
            <div class="page-content">
                <div class="container">
                    <!-- Calcular subtotal si no existe -->
                    @php
                        if (!isset($subtotal)) {
                            $subtotal = collect($cartItems ?? [])->sum(function ($item) {
                                return $item['price'] * $item['quantity'];
                            });
                        }
                    @endphp
                
                 
                    <!-- Fin debug -->
                    <div class="row gutter-lg mb-10">
                        <div class="col-lg-8 pr-lg-4 mb-6">
                            <table class="shop-table cart-table">
                                <thead>
                                    <tr>
                                        <th class="product-name"><span>Producto</span></th>
                                        <th></th>
                                        <th class="product-price"><span>Precio</span></th>
                                        <th class="product-quantity"><span>Cantidad</span></th>
                                        <th class="product-subtotal"><span>Subtotal</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse ($cartItems as $id => $item)
                                    <tr>
                                        <td class="product-thumbnail">
                                            <div class="p-relative">
                                                <a href="#">
                                                    <figure>
                                                        <img src="{{ asset('images/products/' . ($item['image'] ?? 'default.jpg')) }}" alt="{{ $item['name'] }}" width="84" height="94" />
                                                    </figure>
                                                </a>
                                                <button type="submit" class="btn btn-close"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </td>
                                        <td class="product-name">
                                            <a href="#">
                                              {{ $item['name'] }}
                                            </a>
                                        </td>
                                        <td class="product-price"><span class="amount">${{ number_format($item['price'], 2) }}</span></td>
                                        <td class="product-quantity">
                                            <div class="input-group">
                                                <input class="quantity form-control" type="number" min="1" max="100000" value="{{ $item['quantity'] }}" data-product-id="{{ $id }}">
                                                <button class="quantity-plus w-icon-plus"></button>
                                                <button class="quantity-minus w-icon-minus"></button>
                                            </div>
                                        </td>
                                        <td class="product-subtotal">
                                            <span class="amount">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tu carrito está vacío.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                            <div class="cart-action mb-6">
                                <a href="{{ url('/') }}" class="btn btn-dark btn-rounded btn-icon-left btn-shopping mr-auto"><i class="w-icon-long-arrow-left"></i>Continuar comprando</a>
                                <button type="submit" class="btn btn-rounded btn-default btn-clear" name="clear_cart" value="Clear Cart">Limpiar carrito</button> 
                                <button type="submit" class="btn btn-rounded btn-update disabled" name="update_cart" value="Update Cart">Actualizar carrito</button>
                            </div>

                           
                        </div>
                        <div class="col-lg-4 sticky-sidebar-wrapper">
                            <div class="sticky-sidebar">
                                <div class="cart-summary mb-4">
                                    <h3 class="cart-title text-uppercase">Total del carrito</h3>
                                    <div class="cart-subtotal d-flex align-items-center justify-content-between">
                                        <label class="ls-25">Subtotal</label>
                                        <span>${{ number_format($subtotal ?? 0, 2) }}</span>
                                    </div>

                                    <hr class="divider">

                                

                                    <hr class="divider mb-6">
                                    <div class="order-total d-flex justify-content-between align-items-center">
                                        <label>Total</label>
                                        <span class="ls-50">${{ number_format($subtotal ?? 0, 2) }}</span>
                                    </div>
                                    <a href="{{ route('cliente.checkout') }}"
                                        class="btn btn-block btn-dark btn-icon-right btn-rounded  btn-checkout">
                                        Finalizar compra<i class="w-icon-long-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of PageContent -->
        </main>

        @include('front.layout.inc.footer')
        <!-- End of Footer -->
    </div>
    <!-- end of .page-wrapper -->

    <!-- Start of Sticky Footer -->
    @include('front.layout.inc.footermovil')
    <!-- End of Sticky Footer -->

    <!-- Start of Scroll Top -->
    <a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> <i class="w-icon-angle-up"></i> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"> <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle> </svg> </a>
    <!-- End of Scroll Top -->

    <!-- Start of Mobile Menu -->
        @include('front.layout.inc.mobile-menu')
    <!-- End of Mobile Menu -->

    <!-- Start of Newsletter popup -->
    <div class="newsletter-popup mfp-hide">
        <div class="newsletter-content">
            <h4 class="text-uppercase font-weight-normal ls-25 text-white">Obtén grandes <span class="text-primary">descuentos</span></h4>
            <h2 class="ls-25 text-white">Suscríbete a nuestro Newsletter</h2>
            <p class="ls-10 text-white">Obtendrás grandes promociones y noticias recientes.</p>
            <form action="#" method="get" class="input-wrapper input-wrapper-inline input-wrapper-round">
                <input type="email" class="form-control email font-size-md text-white" name="email" id="email2"
                    placeholder="Ingresa tu correo" required="">
                <button class="btn btn-dark" type="submit">ENVIAR</button>
            </form>
            <div class="form-checkbox d-flex align-items-center">
                <input type="checkbox" class="custom-checkbox" id="hide-newsletter-popup" name="hide-newsletter-popup"
                    required="">
                <label for="hide-newsletter-popup" class="font-size-sm text-white">No mostrar otra vez.</label>
            </div>
        </div>
    </div>
    <!-- End of Newsletter popup -->


    <script src="/front/assets/vendor/jquery/jquery.min.js"></script>
    <script src="/front/assets/vendor/swiper/swiper-bundle.min.js"></script>
    <script src="/front/assets/vendor/parallax/parallax.min.js"></script>
    <script src="/front/assets/vendor/jquery.plugin/jquery.plugin.min.js"></script>
    <script src="/front/assets/vendor/jquery.countdown/jquery.countdown.min.js"></script>
    <script src="/front/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
    <script src="/front/assets/vendor/isotope/isotope.pkgd.min.js"></script>
    <script src="/front/assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="/front/assets/vendor/zoom/jquery.zoom.js"></script>

    <!-- Main JS -->
    <script src="/front/assets/js/main.min.js"></script>

    <script>
    $(document).ready(function() {
        let actualizacionTimeout;
        
        // Función para actualizar los totales
        function actualizarTotales() {
            let subtotalGeneral = 0;
            
            // Recorrer cada fila del carrito
            $('table.cart-table tbody tr').each(function() {
                let $row = $(this);
                let $input = $row.find('input.quantity');
                
                if ($input.length > 0) {
                    let cantidad = parseInt($input.val()) || 0;
                    let precioTexto = $row.find('.product-price .amount').text().replace('$', '').replace(',', '');
                    let precio = parseFloat(precioTexto) || 0;
                    let subtotal = precio * cantidad;
                    
                    // Actualizar subtotal de la fila
                    $row.find('.product-subtotal .amount').text('$' + subtotal.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
                    
                    subtotalGeneral += subtotal;
                }
            });
            
            // Actualizar subtotal general y total
            $('.cart-subtotal span').text('$' + subtotalGeneral.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
            $('.order-total span').text('$' + subtotalGeneral.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ','));
        }
        
        // Función para actualizar cantidad en el servidor
        function actualizarCantidadEnServidor($input) {
            let productId = $input.data('product-id');
            let cantidad = parseInt($input.val()) || 1;
            
            console.log('Actualizando producto:', productId, 'cantidad:', cantidad);
            
            $.ajax({
                url: '{{ route("carrito.actualizar") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: productId,
                    quantity: cantidad
                },
                success: function(response) {
                    console.log('✓ Cantidad actualizada en el servidor:', response);
                },
                error: function(xhr, status, error) {
                    console.error('✗ Error al actualizar cantidad:', error);
                    console.error('Respuesta del servidor:', xhr.responseText);
                }
            });
        }
        
        // Función con debounce para evitar múltiples llamadas
        function actualizarConDebounce($input) {
            clearTimeout(actualizacionTimeout);
            actualizacionTimeout = setTimeout(function() {
                actualizarTotales();
                actualizarCantidadEnServidor($input);
            }, 300); // Esperar 300ms después del último cambio
        }
        
        // Observar cambios en los inputs de cantidad
        $(document).on('input change', 'input.quantity', function() {
            actualizarConDebounce($(this));
        });
        
        // También observar clics en los botones
        $(document).on('click', '.quantity-plus, .quantity-minus', function() {
            let $input = $(this).siblings('input.quantity');
            setTimeout(function() {
                actualizarConDebounce($input);
            }, 100);
        });
    });
    </script>
</body>

</html>




