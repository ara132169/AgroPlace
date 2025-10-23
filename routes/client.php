<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\AuthClientController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;



Route::prefix('cliente')->name('cliente.')->group(function () {

    // Rutas públicas (login, registro)
    Route::middleware(['guest:client', 'PreventBackHistory'])->group(function () {
        Route::get('/ingresar', [AuthClientController::class, 'showLoginForm'])->name('ingresar');
        Route::post('/login-handler', [AuthClientController::class, 'loginHandler'])->name('login-handler');
        Route::get('/registrarse', [AuthClientController::class, 'showRegisterForm'])->name('register.form');
        Route::post('/registro', [AuthClientController::class, 'register'])->name('register');
    });
    

    // Rutas privadas (solo clientes autenticados)
    Route::middleware(['auth:client', 'PreventBackHistory'])->group(function () {
        Route::controller(ClientController::class)->group(function () {
            Route::get('/panel', 'dashboard')->name('panel');
            Route::post('/logout','logoutHandler')->name('logout');
            Route::get('/perfil', 'profile')->name('perfil');
            Route::post('/logout', [AuthClientController::class, 'logout'])->name('logout');
            // Dentro de Route::middleware(['auth:client', 'PreventBackHistory'])->group(...)
            Route::get('/compras', [ClientController::class, 'misCompras'])->name('compras');
            Route::get('/pedido', [ClientController::class, 'realizarPedido'])->name('pedido');
        });

        Route::controller(CheckoutController::class)->group(function () {
            
            Route::get('/checkout', 'index')->name('cliente.checkout');
            Route::post('/checkout/procesar', 'process')->name('cliente.checkout.procesar');
        });
    });

    Route::middleware(['auth:client', 'PreventBackHistory'])->group(function () {
        Route::controller(CartController::class)->group(function () {
            Route::get('/carrito', 'showCart')->name('carrito');
            Route::post('/carrito/agregar/{producto}', 'agregar')->name('carrito.agregar');
            Route::get('/cliente/carrito','ver')->name('cliente.carrito');
            Route::post('/carrito/eliminar', 'removeFromCart')->name('carrito.eliminar');
            Route::post('/carrito/actualizar', 'updateCart')->name('carrito.actualizar');
        });
    });
});
