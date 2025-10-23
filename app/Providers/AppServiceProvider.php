<?php

namespace App\Providers;
use App\Models\Cart;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            if (Auth::guard('client')->check()) {
                $cartItems = Cart::with('product')->where('client_id', Auth::guard('client')->id())->get();
    
                // Limpieza de ítems sin producto
                foreach ($cartItems as $item) {
                    if (!$item->product) {
                        $item->delete();
                    }
                }
    
                // Refrescamos el carrito limpio
                $cartItems = Cart::with('product')->where('client_id', Auth::guard('client')->id())->get();
    
                $view->with('cartItems', $cartItems);
            } else {
                $view->with('cartItems', collect());
            }
        });
    }
}
