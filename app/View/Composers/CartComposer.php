<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartComposer
{
    public function compose(View $view)
    {
        $cartItems = collect();

        if (Auth::guard('client')->check()) {
            $cartItems = Cart::with('product')
                ->where('client_id', Auth::guard('client')->id())
                ->get();
        }

        $view->with('cartItems', $cartItems);
    }
}
