<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function agregar($id)
    {
        $wishlist = session()->get('wishlist', []);
        if (!in_array($id, $wishlist)) {
            $wishlist[] = $id;
            session(['wishlist' => $wishlist]);
        }

        return back()->with('success', 'Producto añadido a la wishlist.');
    }

    public function ver()
    {
        $wishlist = session('wishlist', []);
        return view('front.pages.wishlist', compact('wishlist'));
    }
}

