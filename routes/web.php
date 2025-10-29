<?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\FrontEndController;
    use App\Http\Controllers\FrontNosotrosController;
    use App\Http\Controllers\FrontContactoController;
    use App\Http\Controllers\Seller\SellerController;
    use App\Http\Controllers\Seller\ProductController;
    use App\Http\Controllers\Client\ClientController;
    use App\Http\Controllers\Client\CheckoutController;
    use App\Http\Livewire\Productos\TodosLosProductos;
    use App\Http\Controllers\Auth\SellerForgotPasswordController;
    use App\Http\Controllers\Auth\SellerResetPasswordController;
    use App\Http\Controllers\Auth\ClientForgotPasswordController;
    use App\Http\Controllers\Auth\ClientResetPasswordController;
    use App\Services\StripeService;

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider and all of them will
    | be assigned to the "web" middleware group. Make something great!
    |
    */

    Route::get('/', [FrontEndController::class, 'index'])->name('inicio');
    Route::view('/example-page','example-page');

    // Debug routes
    Route::get('/debug-session', function() {
        return response()->json([
            'cart' => session('cart', []),
            'auth_client' => auth('client')->check(),
            'auth_user' => auth('client')->user(),
        ]);
    });

    Route::get('/debug-add-cart', function() {
        $cart = [
            [
                'product_id' => 1,
                'name' => 'Semilla de Maíz A7573 Tratada',
                'price' => 85.00,
                'quantity' => 1,
            ]
        ];
        
        session(['cart' => $cart]);
        
        return response()->json([
            'message' => 'Cart added',
            'cart' => session('cart'),
        ]);
    });

    Route::get('/debug-login', function() {
        // Find or create a test client
        $client = \App\Models\Client::first();
        if (!$client) {
            $client = \App\Models\Client::create([
                'name' => 'Test Client',
                'username' => 'testclient',
                'email' => 'test@client.com',
                'password' => bcrypt('password'),
                'status' => 1,
            ]);
        }
        
        auth('client')->login($client);
        
        return response()->json([
            'message' => 'Client logged in',
            'client' => $client,
            'auth_check' => auth('client')->check(),
        ]);
    });

    // Rutas temporales de checkout sin autenticación para pruebas
    Route::get('/checkout-test', [\App\Http\Controllers\Client\CheckoutController::class, 'index'])->name('checkout.test');
    Route::post('/checkout-test/procesar', [\App\Http\Controllers\Client\CheckoutController::class, 'procesar'])->name('checkout.test.procesar');
    Route::post('/checkout-test/confirm-payment', [\App\Http\Controllers\Client\CheckoutController::class, 'confirmPayment'])->name('checkout.test.confirm-payment');
    Route::get('/order-test/{id}', [\App\Http\Controllers\Client\CheckoutController::class, 'detalles'])->name('order.test.details');
    Route::get('/order-test/{id}/pdf', [\App\Http\Controllers\Client\CheckoutController::class, 'downloadOrderPdf'])->name('order.test.pdf');
    Route::view('/example-auth','example-auth');
    Route::view('/nosotros','nosotros')->name('nosotros');
    Route::view('/contacto','contacto')->name('contacto');

    // Ruta para mostrar productos por categoría
    Route::get('/categoria/{slug}', [ProductController::class, 'productosPorCategoria'])->name('categoria.productos');
    // Ruta para mostrar productos por subcategoría dentro de una categoría
    Route::get('/categoria/{categorySlug}/subcategoria/{subcategorySlug}', [FrontEndController::class, 'getProductsBySubcategory'])->name('subcategoria');
    // Página de productos por subsubcategoría (si existe)
    Route::get('/categoria/{categorySlug}/subcategoria/{subcategorySlug}/subsubcategoria/{subsubcategorySlug}', [FrontEndController::class, 'getProductsBySubsubcategory'])->name('subsubcategoria');

    Route::get('/producto/{slug}', [FrontEndController::class, 'show'])->name('producto.index');
    Route::post('/carrito/agregar/{id}', [FrontEndController::class, 'agregarAlCarrito'])->name('carrito.agregar');


    Route::get('/vendedor/{username}', [FrontEndController::class, 'perfilVendedor'])->name('perfil.vendedor');

    Route::get('/carrito', [FrontEndController::class, 'verCarrito'])->name('front.layout.pages.cliente.carrito.index');
    // Route::get('/cliente/carrito', [CartController::class, 'index'])->name('cliente.carrito.index');
    // Disminuir cantidad
    // Route::post('/carrito/disminuir/{id}', [CartController::class, 'disminuir'])->name('carrito.disminuir');
    // Route::post('/carrito/eliminar/{id}', [CartController::class, 'eliminar'])->name('carrito.eliminar');
    Route::get('/categoria/{slug}', [FrontEndController::class, 'productosPorCategoria'])->name('categoria.productos');

    // Stripe webhook (outside of middleware group for proper handling)
    Route::post('/stripe/webhook', [CheckoutController::class, 'stripeWebhook'])->name('stripe.webhook');

// Test route for Stripe connectivity
Route::get('/test-stripe', function () {
    try {
        $stripeService = new StripeService();
        
        // Test creating a simple payment intent
        $paymentIntent = $stripeService->createPaymentIntent([
            'amount' => 100, // $1.00 in cents
            'currency' => 'usd',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);
        
        return response()->json([
            'success' => true,
            'payment_intent_id' => $paymentIntent->id,
            'status' => $paymentIntent->status,
            'stripe_key' => config('stripe.key'),
            'message' => 'Stripe connection successful!'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'stripe_key' => config('stripe.key'),
            'message' => 'Stripe connection failed!'
        ], 500);
    }
});

        
        // Mostrar formulario para solicitar enlace
        Route::get('tienda/contrasena-olvidada', [SellerForgotPasswordController::class, 'showLinkRequestForm'])
            ->name('tienda.password.request');

        // Procesar envío del email
        Route::post('tienda/contrasena-olvidada', [SellerForgotPasswordController::class, 'sendResetLinkEmail'])
            ->name('tienda.password.email');

        // Mostrar formulario para ingresar nueva contraseña
        Route::get('tienda/restablecer-contrasena/{token}', [SellerResetPasswordController::class, 'showResetForm'])
            ->name('tienda.password.reset');

        // Guardar nueva contraseña
        Route::post('tienda/restablecer-contrasena', [SellerResetPasswordController::class, 'reset'])
            ->name('tienda.password.update');

            // Olvidé mi contraseña
        Route::get('cliente/password/forgot', [ClientForgotPasswordController::class, 'showLinkRequestForm'])->name('client.password.request');
        Route::post('cliente/password/email', [ClientForgotPasswordController::class, 'sendResetLinkEmail'])->name('client.password.email');

        // Reset de contraseña
        Route::get('cliente/password/reset/{token}', [ClientResetPasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('cliente/password/reset', [ClientResetPasswordController::class, 'reset'])->name('client.password.update');

        Route::get('/subcategoria/{slug}', [ProductController::class, 'productosPorSubcategoria'])->name('subcategoria.productos');
        

        Route::get('/tiendas', [FrontEndController::class, 'mostrarTiendas'])->name('tiendas.index');

        // Ruta para mostrar detalle de tienda - usar controlador existente
        Route::get('/tienda/detalle/{id}', [FrontEndController::class, 'detalleTienda'])->name('tienda.detalle');
























