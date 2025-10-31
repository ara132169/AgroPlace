<?php

namespace App\Http\Controllers\Client;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    // Mostrar carrito usando sesiones (accesible para todos)
    public function showCart()
    {
        $cartItems = session('cart', []);
        return view('back.pages.cliente.compras.carrito', compact('cartItems'));
    }

    public function agregar(Request $request, $productoId)
    {
        $producto = Product::findOrFail($productoId);
    
        $cart = session()->get('cart', []);
        
        // Obtén la cantidad del formulario, mínimo 1
        $quantity = max((int) $request->input('quantity', 1), 1);
    
        // Si el producto ya está en el carrito, incrementar cantidad
        if (isset($cart[$productoId])) {
            $cart[$productoId]['quantity'] += $quantity;
        } else {
            $cart[$productoId] = [
                'id' => $producto->id, 
                'name' => $producto->name,
                'price' => $producto->price,
                'image' => $producto->product_image,
                'quantity' => $quantity,
            ];
        }
    
        session()->put('cart', $cart);
    
        $cartCount = collect($cart)->sum('quantity');
    
        // Si es una petición AJAX, devolver JSON
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'cartCount' => $cartCount,
                'message' => 'Producto agregado al carrito'
            ]);
        }
        
        // Si es un formulario normal, redirigir de vuelta con mensaje
        return redirect()->back()->with('success', 'Producto agregado al carrito exitosamente');
    }
    

    public function disminuir(Request $request, $productoId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productoId])) {
            $cart[$productoId]['quantity']--;

            if ($cart[$productoId]['quantity'] <= 0) {
                unset($cart[$productoId]); // Si llega a 0, lo quitamos del carrito
            }

            session()->put('cart', $cart);
        }

        $cartCount = collect($cart)->sum('quantity');

        return response()->json([
            'success' => true,
            'cartCount' => $cartCount,
            'message' => 'Cantidad actualizada en el carrito'
        ]);
    }


    public function eliminar(Request $request, $productoId)
    {
        $cart = session()->get('cart', []);
    
        if (isset($cart[$productoId])) {
            unset($cart[$productoId]); // Lo eliminamos completamente
            session()->put('cart', $cart);
        }
    
        $cartCount = collect($cart)->sum('quantity');
    
        return response()->json([
            'success' => true,
            'cartCount' => $cartCount,
            'message' => 'Producto eliminado del carrito'
        ]);
    }

    public function ver()
    {
        // Usar el mismo sistema de session que funciona en el header
        $cartItems = session('cart', []);
        
        // Calcular el subtotal
        $subtotal = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('back.pages.cliente.compras.carrito', compact('cartItems', 'subtotal'));
    }

    public function index()
    {
        $cliente = auth('client')->user();

        if (!$cliente) {
            return redirect()->route('cliente.ingresar')->with('fail', 'Debes iniciar sesión para ver tu carrito.');
        }

        $cartItems = Cart::with('product')
            ->where('client_id', $cliente->id)
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('front.layout.pages.cliente.carrito.index', compact('cartItems', 'total'));
    }
}
