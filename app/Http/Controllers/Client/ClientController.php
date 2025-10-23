<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function showRegisterForm()
    {
        // Aquí puedes retornar la vista del formulario de registro
        return view('cliente.registrarse');
    }

    public function dashboard()
    {
        $client = Auth::guard('client')->user();
        return view('back.pages.cliente.perfil', compact('client'));
    }

        public function profile()
    {
        $pageTitle = 'Perfil del Cliente';
        $client = auth()->guard('client')->user();

        return view('back.pages.cliente.perfil', compact('pageTitle', 'client'));
    }

    public function misCompras()
    {
        $client = auth()->guard('client')->user();
        $compras = $client->orders()->latest()->get(); // Asumiendo relación definida
        return view('back.pages.cliente.compras.compras', compact('compras'));
    }

    public function wishlist()
    {
        $client = auth()->guard('client')->user();
        $productos = $client->wishlist()->get(); // Asumiendo relación definida
        return view('back.pages.cliente.compras.wishlist', compact('productos'));
    }

    public function carrito()
    {
        $client = auth()->guard('client')->user();
        $carrito = $client->cartItems()->with('product')->get(); // Relación definida
        return view('back.pages.cliente.compras.carrito', compact('carrito'));
    }

    public function realizarPedido()
    {
        $client = auth()->guard('client')->user();
        // Obtén lo que se necesita para realizar el pedido
        return view('back.pages.cliente.compras.pedido');
    }

    public function logoutHandler(Request $request){
        Auth::guard('client')->logout();
        return redirect()->route('cliente.ingresar')->with('fail','Se ha cerrado tu sesión.');
    } //End M

    


    // Puedes agregar otros métodos como perfil, logout, etc.
}

