<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\VendorShop;
use Illuminate\Http\Request;

class TiendaController extends Controller
{
    public function index()
    {
        $tiendas = VendorShop::where('shop_status', 1)->get();
        return view('front.layout.pages.tiendas', compact('tiendas'));
    }
    
    public function detalle($id)
    {
        $tienda = VendorShop::findOrFail($id);
        $productos = $tienda->products()->where('status', 1)->paginate(12);
        
        return view('front.layout.pages.tienda-detalle', compact('tienda', 'productos'));
    }
}
