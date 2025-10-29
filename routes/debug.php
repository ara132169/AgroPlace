<?php

use Illuminate\Support\Facades\Route;

Route::get('/debug-session', function() {
    return response()->json([
        'cart' => session('cart', []),
        'auth_client' => auth('cliente')->check(),
        'auth_user' => auth('cliente')->user(),
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