
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
        <!-- Start of Main -->
        <main class="main checkout">
            <!-- Start of Breadcrumb -->
            <nav class="breadcrumb-nav">
                <div class="container">
                    <ul class="breadcrumb shop-breadcrumb bb-no">
                        <li class="passed"><a href="cart.html">Tienda</a></li>
                        <li class="active"><a href="{{ route('cliente.cliente.checkout') }}">Finalizar pedido</a></li>
                        <li><a href="order.html">Orden completada</a></li>
                    </ul>
                </div>
            </nav>
            <!-- End of Breadcrumb -->


            <!-- Start of PageContent -->
            <div class="page-content">
               
                    <div class="container">
                        
                        <div class="coupon-toggle">
                            ¿Tienes un cupón? <a href="#"
                                class="show-coupon font-weight-bold text-uppercase text-dark">Ingresa el código</a>
                        </div>
                        <div class="coupon-content mb-4">
                            <p>Si tienes un cupón, aplicalo aqui abajo</p>
                            <div class="input-wrapper-inline">
                                <input type="text" name="coupon_code" class="form-control form-control-md mr-1 mb-2" placeholder="Coupon code" id="coupon_code">
                                <button type="submit" class="btn button btn-rounded btn-coupon mb-2" name="apply_coupon" value="Apply coupon">Apply Coupon</button>
                            </div>
                        </div>
                   


                        <form action="/cliente/checkout/procesar" method="POST" class="form checkout-form" >
                        @csrf
                            <div class="row mb-9">
                                <div class="col-lg-7 pr-lg-4 mb-4">
                                    <h3 class="title billing-title text-uppercase ls-10 pt-1 pb-3 mb-0">
                                        Detalles de facturación
                                    </h3>
                                    <div class="row gutter-sm">
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label>Nombre *</label>
                                                <input type="text" class="form-control form-control-md" name="shipping_name" required>

                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="form-group">
                                                <label>Apellidos *</label>
                                                <input type="text" class="form-control form-control-md" name="shipping_surname" required>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Empresa (opcional)</label>
                                        <input type="text" class="form-control form-control-md" name="shipping_company">

                                    </div>
                                    <div class="form-group">
                                        <label>País *</label>
                                        <div class="select-box">
                                        <select name="shipping_country" class="form-control form-control-md">

                                                <option value="default" selected="selected">México
                                                </option>
                                                <option value="uk">Estados Unidos  </option>
                                                <option value="us">Canadá</option>
                                                <option value="fr">Colombia</option>
                                                <option value="aus">Australia</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Dirección *</label>
                                    
                                            <input type="text" placeholder="Nombre de calle y número de casa (departamento,edificio, etc.)" 
                                            class="form-control form-control-md mb-2" name="shipping_address" required>

                                    </div>
                                    <div class="row gutter-sm">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Ciudad*</label>
                                                <input type="text" class="form-control form-control-md" name="shipping_city" required>

                                            </div>
                                            <div class="form-group">
                                                <label>Código Postal *</label>
                                                <input type="text" class="form-control form-control-md" name="shipping_cp" required>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                                <label>Estado *</label>
                                                <input type="text" class="form-control form-control-md" name="shipping_state" required>

                                            </div>
                                            <div class="form-group">
                                                <label>Teléfono *</label>
                                                <input type="text" class="form-control form-control-md" name="shipping_phone" required>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-7">
                                        <label>Correo electrónico *</label>
                                        <input type="email" class="form-control form-control-md" name="shipping_email" required>

                                    </div>

                                    <div class="form-group mt-3">
                                        <label for="order-notes">Añadir una nota (opcional)</label>
                                        <textarea class="form-control mb-0" id="order-notes" name="message" cols="30"
                                            rows="4"
                                            placeholder="Notes about your order, e.g special notes for delivery"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-5 mb-4 sticky-sidebar-wrapper">
                                    <div class="order-summary-wrapper sticky-sidebar">
                                        <h3 class="title text-uppercase ls-10">Resumen de tu pedido</h3>
                                        <div class="order-summary">
                                            <table class="order-table">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2">
                                                            <b>Producto(s)</b>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($cartItems as $item)
                                                    <tr class="bb-no">
                                                        <td class="product-name">{{ $item['name'] }} <i
                                                                class="fas fa-times"></i> <span
                                                                class="product-quantity">{{ $item['quantity'] }}</span></td>
                                                        <td class="product-total">${{ $item['price'] }}</td>
                                                    </tr>
                                                @endforeach
                                                    
                                                    <tr class="cart-subtotal bb-no">
                                                        <td>
                                                            <b>Subtotal</b>
                                                        </td>
                                                        <td>
                                                            <b>${{ $subtotal }}</b>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    
                                                    <tr class="order-total">
                                                        <th>
                                                            <b>Total</b>
                                                        </th>
                                                        <td>
                                                            <b>${{ $subtotal }}</b>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>

                                            <div class="payment-methods" id="payment_method">
                                                <h4 class="title font-weight-bold ls-25 pb-0 mb-1">Payment Methods</h4>
                                                <div class="accordion payment-accordion">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <a href="#cash-on-delivery" class="collapse">Direct Bank Transfor</a>
                                                        </div>
                                                        <div id="cash-on-delivery" class="card-body expanded">
                                                            <p class="mb-0">
                                                                Make your payment directly into our bank account. 
                                                                Please use your Order ID as the payment reference. 
                                                                Your order will not be shipped until the funds have cleared in our account.
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <a href="#payment" class="expand">Check Payments</a>
                                                        </div>
                                                        <div id="payment" class="card-body collapsed">
                                                            <p class="mb-0">
                                                                Please send a check to Store Name, Store Street, Store Town, Store State / County, Store Postcode.
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <a href="#delivery" class="expand">Cash on delivery</a>
                                                        </div>
                                                        <div id="delivery" class="card-body collapsed">
                                                            <p class="mb-0">
                                                                Pay with cash upon delivery.
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="card p-relative">
                                                        <div class="card-header">
                                                            <a href="#paypal" class="expand">Paypal</a>
                                                        </div>
                                                        <a href="https://www.paypal.com/us/webapps/mpp/paypal-popup" class="text-primary paypal-que" 
                                                            onclick="javascript:window.open('https://www.paypal.com/us/webapps/mpp/paypal-popup','WIPaypal',
                                                            'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=1060, height=700'); 
                                                            return false;">What is PayPal?
                                                        </a>
                                                        <div id="paypal" class="card-body collapsed">
                                                            <p class="mb-0">
                                                                Pay via PayPal, you can pay with your credit cart if you
                                                                don't have a PayPal account.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group place-order pt-6">
                                                <button type="submit" class="btn btn-dark btn-block btn-rounded">Realizar pedido</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
          
            </div>
            <!-- End of PageContent -->
        </main>
        <!-- End of Main -->

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
</body>

</html>