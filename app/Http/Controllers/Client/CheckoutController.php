<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = session('cart', []);

        // Calcular el subtotal
        $subtotal = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('front.layout.pages.cliente.checkout', compact('cartItems', 'subtotal'));
    }

    /* public function procesar(Request $request)
    {
       
        DB::beginTransaction();

        try {
            $client = Auth::guard('client')->user();

            // Obtener productos del carrito del cliente
            $cartItems = Cart::where('client_id', $client->id)->get();

            if ($cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'Tu carrito está vacío.');
            }

            $total = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            // Crear orden
            $order = Order::create([
                'client_id'        => $client->id,
                'shipping_name'    => $request->shipping_name,
                'shipping_address' => $request->shipping_address,
                'shipping_company' => $request->shipping_company,
                'shipping_country' => $request->shipping_country,
                'shipping_city'    => $request->shipping_city,
                'shipping_state'   => $request->shipping_state,
                'shipping_cp'      => $request->shipping_cp,
                'shipping_phone'   => $request->shipping_phone,
                'shipping_email'   => $request->shipping_email,
                'total'            => $total,
                'status'           => 'pendiente',
            ]);

            // Guardar los items del pedido
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->product->price, // precio actual
                ]);
            }

            // Limpiar carrito
            Cart::where('client_id', $client->id)->delete();

            DB::commit();

            return redirect()->route('cliente.checkout.detalles', $order->id)
                ->with('success', 'Tu pedido ha sido procesado exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Hubo un error al procesar tu pedido. ' . $e->getMessage());
        }

        // Integración con Mercado Pago
        MercadoPago::setAccessToken(env('MERCADOPAGO_ACCESS_TOKEN'));

        $preference = new \MercadoPago\Preference();
        $items = [];

        foreach ($cartItems as $id => $item) {
            $items[] = new \MercadoPago\Item([
                'title' => $item['name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
            ]);
        }

        $preference->items = $items;
        $preference->back_urls = [
            "success" => route('cliente.checkout.mercadopago', ['payment_id' => 'success']),
            "failure" => route('cliente.checkout.mercadopago', ['payment_id' => 'failure']),
            "pending" => route('cliente.checkout.mercadopago', ['payment_id' => 'pending']),
        ];

        $preference->save();

        return redirect()->to($preference->init_point);
    } */

    public function procesar(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required',
            'surname' => 'required',
            'address' => 'required',
            'country' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
        ]);

        $cartItems = session('cart', []);

        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Tu carrito está vacío');
        }

        $order = Order::create([
            'client_id' => auth('cliente')->id(),
            'shipping_name' => $request->shipping_name . ' ' . $request->surname,
            'shipping_address' => $request->address,
            'shipping_company' => $request->company,
            'shipping_country' => $request->country,
            'shipping_city' => $request->city,
            'shipping_state' => $request->state,
            'shipping_cp' => $request->zip,
            'shipping_phone' => $request->phone,
            'shipping_email' => $request->email,
            'total' => collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']),
            'status' => 'pendiente',
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        session()->forget('cart');

        return redirect()->route('cliente.checkout.detalles', $order->id)->with('success', 'Pedido realizado correctamente.');
    }


    public function mercadoPagoCallback($payment_id)
    {
        // Validar el estado del pago
        $payment = \MercadoPago\Payment::find_by_id($payment_id);

        $order = Order::find($payment->order_id);
        if ($payment->status == 'approved') {
            $order->status = 'accepted';
        } else {
            $order->status = 'cancelled';
        }

        $order->save();

        return redirect()->route('cliente.dashboard')->with('success', 'Tu pedido ha sido procesado');
    }

    public function detalles($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

        return view('cliente.checkout.detalles', compact('order'));
    }

}
