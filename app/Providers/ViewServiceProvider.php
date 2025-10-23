<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\CartComposer;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
        public function boot()
    {
        // Aplica a todas las vistas
        View::composer('*', CartComposer::class);

        View::composer('*', function ($view) {
            $cartItems = collect(session('cart'))->values();
            $view->with('cartItems', $cartItems);
        });
    }
}
