<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\StripeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Stripe\Exception\CardException;
use Stripe\Exception\RateLimitException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\ApiErrorException;

class CheckoutController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function index()
    {
        // Verificar si está autenticado como cliente o vendedor
        if (!Auth::guard('client')->check() && !Auth::guard('seller')->check()) {
            // Si no está autenticado, redirigir al login con mensaje
            session()->put('checkout_redirect', true);
            return redirect()->route('cliente.ingresar')->with('info', 'Por favor inicia sesión para finalizar tu compra.');
        }

        $cartItems = session('cart', []);

        // Verificar que el carrito no esté vacío
        if (empty($cartItems)) {
            return redirect()->route('cliente.carrito')->with('error', 'Tu carrito está vacío.');
        }

        // Calcular el subtotal
        $subtotal = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        // Get Stripe publishable key for frontend
        $stripePublishableKey = config('stripe.key');

        // Si es la ruta de prueba, usar vista de prueba
        $viewName = request()->is('checkout-test') ? 'checkout-test' : 'front.layout.pages.cliente.checkout';

        return view($viewName, compact('cartItems', 'subtotal', 'stripePublishableKey'));
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
        // Log inicial para debug
        Log::info('Checkout procesar called', [
            'is_ajax' => $request->ajax(),
            'accept_header' => $request->header('Accept'),
            'content_type' => $request->header('Content-Type'),
            'all_input' => $request->all(),
        ]);

        $validator = \Validator::make($request->all(), [
            'shipping_name' => 'required',
            'surname' => 'required',
            'address' => 'required',
            'country' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'payment_method_id' => 'required|string', // Stripe payment method ID
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed', $validator->errors()->toArray());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Log::info('Validation passed, proceeding with checkout');

        $cartItems = session('cart', []);
        
        Log::info('🛒 CARRITO EN CHECKOUT (ANTES DE PROCESAR PAGO)', [
            'cart_items' => $cartItems,
            'cart_count' => count($cartItems),
            'session_id' => session()->getId()
        ]);
        
        if (empty($cartItems) && request()->is('checkout-test*')) {
            // Obtener el primer producto disponible de la base de datos
            $firstProduct = \App\Models\Product::first();
            
            if ($firstProduct) {
                $cartItems = [
                    [
                        'product_id' => $firstProduct->id,
                        'name' => $firstProduct->name,
                        'price' => floatval($firstProduct->price),
                        'quantity' => 1,
                    ]
                ];
                session(['cart' => $cartItems]);
                Log::info('Added test cart items for testing', ['product_id' => $firstProduct->id]);
            } else {
                Log::error('No products found in database for testing');
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay productos disponibles para pruebas'
                    ], 400);
                }
                
                return redirect()->back()->with('error', 'No hay productos disponibles');
            }
        }
        
        Log::info('Cart items retrieved', [
            'cart_items' => $cartItems,
            'cart_count' => count($cartItems),
        ]);

        if (empty($cartItems)) {
            Log::error('Cart is empty');
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tu carrito está vacío'
                ], 400);
            }
            
            return redirect()->back()->with('error', 'Tu carrito está vacío');
        }

        DB::beginTransaction();
        
        try {
            // Calculate total amount in cents (Stripe requires amount in smallest currency unit)
            $totalAmount = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);
            $amountInCents = (int) ($totalAmount * 100);

            // Determinar el tipo de usuario y obtener el ID
            $clientId = null;
            $sellerId = null;
            $buyerType = 'client';

            if (Auth::guard('client')->check()) {
                $clientId = Auth::guard('client')->id();
                $buyerType = 'client';
            } elseif (Auth::guard('seller')->check()) {
                $sellerId = Auth::guard('seller')->id();
                $buyerType = 'seller';
            } else {
                throw new \Exception('Usuario no autenticado');
            }

            // Create the order first
            $order = Order::create([
                'client_id' => $clientId,
                'seller_id' => $sellerId,
                'buyer_type' => $buyerType,
                'shipping_name' => $request->shipping_name . ' ' . $request->surname,
                'shipping_address' => $request->address,
                'shipping_company' => $request->company ?? '', // Usar string vacío si no se proporciona
                'shipping_country' => $request->country,
                'shipping_city' => $request->city,
                'shipping_state' => $request->state,
                'shipping_cp' => $request->zip,
                'shipping_phone' => $request->phone,
                'shipping_email' => $request->email,
                'total' => $totalAmount,
                'status' => 'pendiente',
                'payment_method' => 'stripe',
                'stripe_payment_status' => 'pending',
                'payment_currency' => 'MXN',
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            // Log debug information
            Log::info('Creating payment intent with data:', [
                'amount' => $amountInCents,
                'payment_method_id' => $request->payment_method_id,
                'order_id' => $order->id,
            ]);

            // Create payment intent with Stripe
            $paymentIntent = $this->stripeService->createPaymentIntent([
                'amount' => $amountInCents, // Ya está en centavos
                'currency' => 'mxn',
                'payment_method' => $request->payment_method_id,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => url('/checkout-test'), // URL de retorno requerida
                'metadata' => [
                    'order_id' => $order->id,
                    'client_email' => $request->email,
                ],
            ]);

            Log::info('Payment intent created successfully:', [
                'payment_intent_id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
            ]);

            // Update order with payment intent ID
            $order->update([
                'stripe_payment_intent_id' => $paymentIntent->id,
            ]);

            // Handle the payment result
            if ($paymentIntent->status === 'succeeded') {
                $order->update([
                    'stripe_payment_status' => 'succeeded',
                    'status' => 'confirmed',
                ]);
                
                DB::commit();
                session()->forget('cart');
                
                if ($request->ajax() || $request->wantsJson()) {
                    // Usar ruta de prueba si es una petición de checkout-test
                    $redirectRoute = $request->is('checkout-test*') 
                        ? route('order.test.details', $order->id)
                        : route('cliente.checkout.detalles', $order->id);
                        
                    return response()->json([
                        'success' => true,
                        'redirect_url' => $redirectRoute,
                        'message' => 'Pago procesado exitosamente. Tu pedido ha sido confirmado.'
                    ]);
                }
                
                $redirectRoute = $request->is('checkout-test*')
                    ? route('order.test.details', $order->id) 
                    : route('cliente.checkout.detalles', $order->id);
                    
                return redirect($redirectRoute)
                    ->with('success', 'Pago procesado exitosamente. Tu pedido ha sido confirmado.');
                    
            } elseif ($paymentIntent->status === 'requires_action') {
                DB::commit();
                
                return response()->json([
                    'requires_action' => true,
                    'payment_intent' => [
                        'id' => $paymentIntent->id,
                        'client_secret' => $paymentIntent->client_secret,
                    ],
                    'order_id' => $order->id,
                ]);
            } else {
                // Payment failed
                $order->update([
                    'stripe_payment_status' => 'failed',
                    'status' => 'cancelled',
                ]);
                
                DB::rollback();
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El pago no pudo ser procesado. Inténtalo de nuevo.'
                    ], 400);
                }
                
                return redirect()->back()->with('error', 'El pago no pudo ser procesado. Inténtalo de nuevo.');
            }

        } catch (\Stripe\Exception\CardException $e) {
            // Error específico de la tarjeta (fondos insuficientes, tarjeta declinada, etc.)
            DB::rollback();
            $error = $e->getError();
            $errorMessage = $this->getStripeErrorMessage($error->code, $error->message);
            
            Log::warning('Stripe Card Exception: ' . $e->getMessage(), [
                'error_code' => $error->code,
                'error_type' => $error->type,
                'decline_code' => $error->decline_code ?? null,
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 400);
            }
            
            return redirect()->back()->with('error', $errorMessage);
            
        } catch (\Stripe\Exception\RateLimitException $e) {
            // Demasiadas peticiones a la API
            DB::rollback();
            Log::error('Stripe Rate Limit Exception: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Demasiadas peticiones. Por favor, inténtalo en unos momentos.'
                ], 429);
            }
            
            return redirect()->back()->with('error', 'Demasiadas peticiones. Por favor, inténtalo en unos momentos.');
            
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            // Parámetros inválidos
            DB::rollback();
            Log::error('Stripe Invalid Request Exception: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error en la configuración del pago. Contacta al soporte.'
                ], 400);
            }
            
            return redirect()->back()->with('error', 'Error en la configuración del pago. Contacta al soporte.');
            
        } catch (\Stripe\Exception\AuthenticationException $e) {
            // Error de autenticación con Stripe
            DB::rollback();
            Log::error('Stripe Authentication Exception: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de autenticación con el procesador de pagos.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error de autenticación con el procesador de pagos.');
            
        } catch (\Stripe\Exception\ApiConnectionException $e) {
            // Error de conexión con Stripe
            DB::rollback();
            Log::error('Stripe API Connection Exception: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de conexión con el procesador de pagos. Inténtalo más tarde.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error de conexión con el procesador de pagos. Inténtalo más tarde.');
            
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Error general de la API de Stripe
            DB::rollback();
            Log::error('Stripe API Error Exception: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error del procesador de pagos. Inténtalo más tarde.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Error del procesador de pagos. Inténtalo más tarde.');
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('General payment error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hubo un error al procesar el pago: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Hubo un error al procesar el pago: ' . $e->getMessage());
        }
    }

    // New method to handle payment confirmation for cases requiring additional authentication
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'order_id' => 'required|integer',
        ]);

        try {
            $order = Order::findOrFail($request->order_id);
            
            // Confirm the payment intent
            $paymentIntent = $this->stripeService->confirmPaymentIntent($request->payment_intent_id);

            if ($paymentIntent->status === 'succeeded') {
                $order->update([
                    'stripe_payment_status' => 'succeeded',
                    'status' => 'confirmed',
                ]);
                
                session()->forget('cart');
                
                // Usar ruta de prueba si es necesario
                $redirectRoute = $request->header('Referer') && str_contains($request->header('Referer'), 'checkout-test')
                    ? route('order.test.details', $order->id)
                    : route('cliente.checkout.detalles', $order->id);
                
                return response()->json([
                    'success' => true,
                    'redirect_url' => $redirectRoute,
                ]);
            } else {
                $order->update([
                    'stripe_payment_status' => 'failed',
                    'status' => 'cancelled',
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'El pago no pudo ser completado.',
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Payment confirmation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al confirmar el pago: ' . $e->getMessage(),
            ]);
        }
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
        // Cargar la orden con sus items y los productos relacionados, incluyendo el vendedor
        $order = Order::with(['items.product.seller'])->findOrFail($id);
        
        // Verificar que el usuario autenticado pueda ver esta orden (opcional)
        // if (auth('client')->check() && $order->client_id !== auth('client')->id()) {
        //     abort(403, 'No tienes permisos para ver esta orden');
        // }

        return view('front.layout.pages.cliente.order-details', compact('order'));
    }

    /**
     * Mapear códigos de error de Stripe a mensajes en español amigables
     */
    private function getStripeErrorMessage($errorCode, $originalMessage)
    {
        $errorMessages = [
            // Errores de tarjeta
            'card_declined' => 'Tu tarjeta fue rechazada. Por favor, intenta con otra tarjeta o contacta tu banco.',
            'insufficient_funds' => 'Fondos insuficientes en tu tarjeta. Por favor, verifica tu saldo o usa otra tarjeta.',
            'incorrect_cvc' => 'El código CVV/CVC es incorrecto. Por favor, verifica e intenta nuevamente.',
            'expired_card' => 'Tu tarjeta ha expirado. Por favor, usa una tarjeta válida.',
            'incorrect_number' => 'El número de tarjeta es incorrecto. Por favor, verifica e intenta nuevamente.',
            'incorrect_zip' => 'El código postal no coincide con tu tarjeta.',
            'processing_error' => 'Error al procesar tu tarjeta. Por favor, intenta nuevamente.',
            'generic_decline' => 'Tu tarjeta fue rechazada. Contacta tu banco para más información.',
            
            // Errores de límites
            'amount_too_large' => 'El monto es demasiado alto para ser procesado.',
            'amount_too_small' => 'El monto es demasiado pequeño para ser procesado.',
            
            // Errores de autenticación
            'authentication_required' => 'Tu banco requiere autenticación adicional. Por favor, intenta nuevamente.',
            
            // Errores de rate limit
            'rate_limit' => 'Demasiadas peticiones. Por favor, espera un momento e intenta nuevamente.',
        ];

        return $errorMessages[$errorCode] ?? $originalMessage ?? 'Ha ocurrido un error al procesar tu pago. Por favor, intenta nuevamente.';
    }

    // Stripe webhook handler
    public function stripeWebhook(Request $request)
    {
        try {
            $event = $this->stripeService->verifyWebhook(
                $request->getContent(),
                $request->header('Stripe-Signature')
            );

            // Handle the event
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    
                    if (isset($paymentIntent->metadata->order_id)) {
                        $order = Order::find($paymentIntent->metadata->order_id);
                        if ($order) {
                            $order->update([
                                'stripe_payment_status' => 'succeeded',
                                'status' => 'confirmed',
                            ]);
                        }
                    }
                    break;

                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    
                    if (isset($paymentIntent->metadata->order_id)) {
                        $order = Order::find($paymentIntent->metadata->order_id);
                        if ($order) {
                            $order->update([
                                'stripe_payment_status' => 'failed',
                                'status' => 'cancelled',
                            ]);
                        }
                    }
                    break;

                default:
                    Log::info('Unhandled Stripe webhook event: ' . $event->type);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Stripe webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook error'], 400);
        }
    }

    /**
     * Generar y descargar PDF de la orden
     */
    public function downloadOrderPdf($orderId)
    {
        try {
            // Buscar la orden con sus relaciones
            $order = Order::with(['items.product.seller'])->findOrFail($orderId);
            
            // Verificar que el usuario tenga acceso a esta orden
            if (Auth::guard('client')->check()) {
                if ($order->client_id !== Auth::guard('client')->id()) {
                    abort(403, 'No tienes permiso para acceder a esta orden.');
                }
            }
            
            // Generar el PDF
            $pdf = Pdf::loadView('front.layout.pages.cliente.order-pdf', compact('order'));
            
            // Configurar el PDF
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'defaultFont' => 'Arial',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);
            
            // Retornar el PDF para descarga
            return $pdf->download('orden-' . $order->id . '.pdf');
            
        } catch (\Exception $e) {
            Log::error('Error generando PDF de orden: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al generar el PDF de la orden.');
        }
    }

}
