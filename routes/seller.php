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
           Route::post('/change-profile-picture', [SellerController::class, 'changeProfilePicture'])->name('change-profile-picture');

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
    });

 
});