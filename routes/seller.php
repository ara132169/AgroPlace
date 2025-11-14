<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Seller\ProductController;
use App\Models\Product;


Route::prefix('tienda')->name('tienda.')->group(function(){

    Route::middleware(['guest:seller','PreventBackHistory'])->group(function(){
        Route::controller(SellerController::class)->group(function(){
           Route::get('/ingresar','ingresar')->name('ingresar');
           Route::post('/login-handler','loginHandler')->name('login-handler');
           Route::get('/registrarse','registrarse')->name('registrarse');
           Route::post('/registrar','registrartienda')->name('registrar');
           Route::get('/cuenta/verificar/{token}','verificarCuenta')->name('verificar');
           Route::get('/registro-realizado','registroRealizado')->name('registro-realizado');
        });
    });
 
    Route::middleware(['auth:seller','PreventBackHistory'])->group(function(){
 
        Route::controller(SellerController::class)->group(function(){
           Route::get('/','home')->name('home');
           Route::post('/logout','logoutHandler')->name('logout');
           Route::get('perfil','profileView')->name('perfil');
           Route::post('/cambiar-imagen-perfil','cambiarImagenPerfil')->name('cambiar-imagen-perfil');
           Route::get('/configuraciones-tienda','shopSettings')->name('configuraciones-tienda');
           Route::post('/configuracion-tienda','shopSetup')->name('configuracion-tienda');
           Route::post('/change-profile-picture', 'changeProfilePicture')->name('change-profile-picture');
           
           // Rutas para ventas
           Route::get('/ventas', 'misVentas')->name('ventas');
           Route::get('/venta/{orderId}/detalle', 'detalleVenta')->name('venta.detalle');

           // Rutas para compras del vendedor
           Route::get('/compras', 'misCompras')->name('compras');
           Route::get('/compra/{orderId}/detalle', 'detalleCompra')->name('compra.detalle');

        });

        Route::prefix('product')->name('product.')->group(function(){
            Route::controller(ProductController::class)->group(function(){
               Route::get('/productos','allProducts')->name('productos');
               Route::get('/agregar-productos','addProduct')->name('agregar-productos');
               Route::get('/mostrar-categoria-productos','getProductCategory')->name('mostrar-categoria-productos');
               Route::post('/crear','createProduct')->name('crear-producto');
               Route::get('/get-product-category','getProductCategory')->name('get-product-category');
               Route::get('/editar','editProduct')->name('editar-producto');
               Route::post('/actualizar-producto','updateProduct')->name('actualizar-producto');
               Route::post('/cargar-imagenes','uploadProductImages')->name('cargar-imagenes');
               Route::get('/obtener-imagen-productos','getProductImages')->name('obtener-imagen-productos');
               Route::post('/eliminar-imagen-producto','deleteProductImage')->name('eliminar-imagen-producto');
               Route::post('/eliminar-producto','deleteProduct')->name('eliminar-producto');
               Route::get('/get-category-subcategories','getSubcategories')->name('get-product-subcategory');
            });
        });

        // Rutas de Stripe Connect para pagos
        Route::controller(\App\Http\Controllers\Seller\StripeConnectController::class)->group(function(){
           Route::get('/stripe/config','showConfig')->name('stripe.config');
           Route::get('/stripe/dashboard','dashboard')->name('stripe.dashboard');
           Route::post('/stripe/connect','startOnboarding')->name('stripe.connect');
           Route::get('/stripe/success','onboardingSuccess')->name('stripe.success');
           Route::get('/stripe/refresh','refreshAccountStatus')->name('stripe.refresh');
           Route::post('/stripe/disconnect','disconnect')->name('stripe.disconnect');
        });

        // Rutas de cuentas de pago manuales
        Route::controller(\App\Http\Controllers\Seller\PaymentAccountController::class)->group(function(){
           Route::get('/payment-accounts','index')->name('payment.accounts');
           Route::post('/payment-account','store')->name('payment.account.store');
           Route::get('/payment-account/{id}/edit','edit')->name('payment.account.edit');
           Route::put('/payment-account/{id}','update')->name('payment.account.update');
           Route::delete('/payment-account/{id}','destroy')->name('payment.account.delete');
           Route::get('/deposit-history','deposits')->name('deposit.history');
           Route::post('/request-deposit','requestDeposit')->name('request.deposit');
        });
    });

    // Rutas del carrito y checkout para vendedores (sin autenticación requerida para carrito)
    Route::controller(\App\Http\Controllers\Client\CartController::class)->group(function () {
        Route::get('/carrito', 'showCart')->name('carrito');
        Route::post('/carrito/agregar/{producto}', 'agregar')->name('carrito.agregar');
        Route::post('/carrito/eliminar', 'removeFromCart')->name('carrito.eliminar');
        Route::post('/carrito/actualizar', 'updateCart')->name('carrito.actualizar');
    });

    // Rutas de checkout para vendedores autenticados
    Route::controller(\App\Http\Controllers\Client\CheckoutController::class)->group(function () {
        Route::get('/checkout', 'index')->name('checkout');
        Route::post('/checkout/procesar', 'procesar')->name('checkout.procesar');
        Route::post('/checkout/confirm-payment', 'confirmPayment')->name('checkout.confirm-payment');
        Route::get('/checkout/mercadopago/{payment_id}', 'mercadoPagoCallback')->name('checkout.mercadopago');
        Route::get('/pedido/{id}', 'detalles')->name('checkout.detalles');
        Route::get('/pedido/{id}/pdf', 'downloadOrderPdf')->name('checkout.pdf');
    });

 
});