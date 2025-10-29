
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
                        <li class="active"><a href="{{ route('cliente.checkout') }}">Finalizar pedido</a></li>
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
                   


                        <form action="{{ route('checkout.test.procesar') }}" method="POST" class="form checkout-form" id="payment-form">
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
                                                <input type="text" class="form-control form-control-md" name="surname" required>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Empresa (opcional)</label>
                                        <input type="text" class="form-control form-control-md" name="company">

                                    </div>
                                    <div class="form-group">
                                        <label>País *</label>
                                        <div class="select-box">
                                        <select name="country" class="form-control form-control-md" required>
                                                <option value="MX" selected="selected">México</option>
                                                <option value="US">Estados Unidos</option>
                                                <option value="CA">Canadá</option>
                                                <option value="CO">Colombia</option>
                                                <option value="ES">España</option>
                                                <option value="AR">Argentina</option>
                                                <option value="CL">Chile</option>
                                                <option value="PE">Perú</option>
                                                <option value="VE">Venezuela</option>
                                                <option value="EC">Ecuador</option>
                                                <option value="BO">Bolivia</option>
                                                <option value="UY">Uruguay</option>
                                                <option value="PY">Paraguay</option>
                                                <option value="GT">Guatemala</option>
                                                <option value="CR">Costa Rica</option>
                                                <option value="PA">Panamá</option>
                                                <option value="NI">Nicaragua</option>
                                                <option value="HN">Honduras</option>
                                                <option value="SV">El Salvador</option>
                                                <option value="BR">Brasil</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Dirección *</label>
                                    
                                            <input type="text" placeholder="Nombre de calle y número de casa (departamento,edificio, etc.)" 
                                            class="form-control form-control-md mb-2" name="address" required>

                                    </div>
                                    <div class="row gutter-sm">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Ciudad*</label>
                                                <input type="text" class="form-control form-control-md" name="city" required>

                                            </div>
                                            <div class="form-group">
                                                <label>Código Postal *</label>
                                                <input type="text" class="form-control form-control-md" name="zip" required>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                        <div class="form-group">
                                                <label>Estado *</label>
                                                <input type="text" class="form-control form-control-md" name="state" required>

                                            </div>
                                            <div class="form-group">
                                                <label>Teléfono *</label>
                                                <input type="text" class="form-control form-control-md" name="phone" required>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mb-7">
                                        <label>Correo electrónico *</label>
                                        <input type="email" class="form-control form-control-md" name="email" required>

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
                                                <h4 class="title font-weight-bold ls-25 pb-0 mb-3">Método de Pago</h4>
                                                
                                                <!-- Stripe Card Element -->
                                                <div class="stripe-payment-section">
                                                    <div class="form-group">
                                                        <label for="card-element">
                                                            Tarjeta de Crédito o Débito
                                                        </label>
                                                        <div id="card-element" class="form-control" style="padding: 10px;">
                                                            <!-- Stripe Elements will create form elements here -->
                                                        </div>
                                                        <!-- Used to display Element errors -->
                                                        <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Hidden field for payment method ID -->
                                                <input type="hidden" id="payment-method-id" name="payment_method_id">
                                            </div>

                                            <div class="form-group place-order pt-6">
                                                <button type="submit" id="submit-payment" class="btn btn-dark btn-block btn-rounded">
                                                    <span id="button-text">Realizar pedido</span>
                                                    <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                </button>
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

    <!-- Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>
    
    <script>
        // Initialize Stripe
        const stripe = Stripe('{{ $stripePublishableKey }}');
        const elements = stripe.elements();
        
        // Create card element
        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#424770',
                    '::placeholder': {
                        color: '#aab7c4',
                    },
                },
                invalid: {
                    color: '#9e2146',
                },
            },
        });
        
        cardElement.mount('#card-element');
        
        // Handle card errors
        cardElement.on('change', ({error}) => {
            const displayError = document.getElementById('card-errors');
            if (error) {
                displayError.textContent = error.message;
            } else {
                displayError.textContent = '';
            }
        });
        
        // Handle form submission
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-payment');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');
        
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            
            // Disable submit button and show loading
            submitButton.disabled = true;
            buttonText.classList.add('d-none');
            spinner.classList.remove('d-none');
            
            try {
                // Create payment method
                const {paymentMethod, error} = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                    billing_details: {
                        name: document.querySelector('[name="shipping_name"]').value + ' ' + document.querySelector('[name="surname"]').value,
                        email: document.querySelector('[name="email"]').value,
                        address: {
                            line1: document.querySelector('[name="address"]').value,
                            city: document.querySelector('[name="city"]').value,
                            state: document.querySelector('[name="state"]').value,
                            postal_code: document.querySelector('[name="zip"]').value,
                            country: document.querySelector('[name="country"]').value,
                        },
                    },
                });
                
                if (error) {
                    // Show error and re-enable button
                    document.getElementById('card-errors').textContent = error.message;
                    resetButton();
                } else {
                    // Set payment method ID and submit form
                    document.getElementById('payment-method-id').value = paymentMethod.id;
                    
                    // Submit form with all data
                    const formData = new FormData(form);
                    
                    try {
                        console.log('Sending payment request to:', form.action);
                        console.log('Form data:', formData);
                        
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                                'Accept': 'application/json',
                            },
                        });
                        
                        console.log('Response status:', response.status);
                        console.log('Response headers:', response.headers);
                        console.log('Response URL:', response.url);
                        
                        // Get the response text first to see what we're getting
                        const responseText = await response.text();
                        console.log('Raw response:', responseText);
                        
                        if (!response.ok) {
                            console.error('HTTP error:', response.status, responseText);
                            throw new Error(`HTTP error! status: ${response.status} - ${responseText}`);
                        }
                        
                        // Try to parse as JSON
                        let result;
                        try {
                            result = JSON.parse(responseText);
                        } catch (parseError) {
                            console.error('JSON parse error:', parseError);
                            console.error('Response was:', responseText);
                            throw new Error('Server response is not valid JSON');
                        }
                        
                        console.log('Parsed result:', result);
                        
                        if (result.requires_action) {
                            // 3D Secure authentication required
                            const {error: confirmError} = await stripe.confirmCardPayment(
                                result.payment_intent.client_secret
                            );
                            
                            if (confirmError) {
                                document.getElementById('card-errors').textContent = confirmError.message;
                                resetButton();
                            } else {
                                // Payment succeeded after authentication
                                const confirmResponse = await fetch('{{ route("checkout.test.confirm-payment") }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                                    },
                                    body: JSON.stringify({
                                        payment_intent_id: result.payment_intent.id,
                                        order_id: result.order_id,
                                    }),
                                });
                                
                                const confirmResult = await confirmResponse.json();
                                
                                if (confirmResult.success) {
                                    window.location.href = confirmResult.redirect_url;
                                } else {
                                    document.getElementById('card-errors').textContent = confirmResult.message;
                                    resetButton();
                                }
                            }
                        } else if (response.ok) {
                            // Payment succeeded without additional authentication
                            if (result.redirect_url) {
                                window.location.href = result.redirect_url;
                            } else {
                                // Handle regular form submission redirect
                                window.location.reload();
                            }
                        } else {
                            // Handle validation errors or other errors
                            const errorData = await response.text();
                            console.error('Payment error:', errorData);
                            document.getElementById('card-errors').textContent = 'Error al procesar el pago. Inténtalo de nuevo.';
                            resetButton();
                        }
                    } catch (fetchError) {
                        console.error('Network error details:', fetchError);
                        console.error('Error message:', fetchError.message);
                        console.error('Error stack:', fetchError.stack);
                        document.getElementById('card-errors').textContent = `Error de red: ${fetchError.message}`;
                        resetButton();
                    }
                }
            } catch (stripeError) {
                console.error('Stripe error:', stripeError);
                document.getElementById('card-errors').textContent = 'Error al procesar la tarjeta. Inténtalo de nuevo.';
                resetButton();
            }
        });
        
        function resetButton() {
            submitButton.disabled = false;
            buttonText.classList.remove('d-none');
            spinner.classList.add('d-none');
        }
    </script>
</body>

</html>