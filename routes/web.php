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
    Route::get('/cliente/carrito', [CartController::class, 'index'])->name('cliente.carrito.index');
    // Disminuir cantidad
    Route::post('/carrito/disminuir/{id}', [CartController::class, 'disminuir'])->name('carrito.disminuir');
    Route::post('/carrito/eliminar/{id}', [CartController::class, 'eliminar'])->name('carrito.eliminar');
    Route::get('/categoria/{slug}', [FrontEndController::class, 'productosPorCategoria'])->name('categoria.productos');


    Route::prefix('cliente')->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('cliente.checkout');
        Route::post('/checkout/procesar', [CheckoutController::class, 'procesar'])->name('cliente.checkout.procesar');
        Route::get('/checkout/mercadopago/{payment_id}', [CheckoutController::class, 'mercadoPagoCallback'])->name('cliente.checkout.mercadopago');
        Route::get('/cliente/pedido/{id}', [CheckoutController::class, 'detalles'])->name('cliente.checkout.detalles');
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
        Route::get('/tienda/{id}', [FrontEndController::class, 'detalleTienda'])->name('tienda.detalle');
























