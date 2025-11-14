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
use App\Services\StripeConnectService;
use App\Models\Seller;
use App\Models\Product;

class CheckoutController extends Controller
{
    protected $stripeService;
    protected $stripeConnectService;

    public function __construct(StripeService $stripeService, StripeConnectService $stripeConnectService)
    {
        $this->stripeService = $stripeService;
        $this->stripeConnectService = $stripeConnectService;
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

            // Determinar el client_id (siempre necesario para orders)
            $clientId = null;

            if (Auth::guard('client')->check()) {
                $clientId = Auth::guard('client')->id();
            } elseif (Auth::guard('seller')->check()) {
                // Si es un vendedor comprando, buscar client asociado por email
                $seller = Auth::guard('seller')->user();
                $client = \App\Models\Client::where('email', $seller->email)->first();
                
                if (!$client) {
                    throw new \Exception('Vendedor debe tener una cuenta de cliente asociada para realizar compras');
                }
                
                $clientId = $client->id;
            } else {
                throw new \Exception('Usuario no autenticado');
            }

            // Create the order first
            $order = Order::create([
                'client_id' => $clientId, // Solo usar client_id, no seller_id ni buyer_type
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

            // 🆕 OBTENER VENDEDORES DE LOS PRODUCTOS PARA SISTEMA DE COMISIONES
            $productIds = collect($cartItems)->pluck('product_id')->unique();
            $products = Product::with('seller')->whereIn('id', $productIds)->get();
            $sellers = $products->pluck('seller')->unique('id');

            Log::info('🛒 Análisis de vendedores en carrito', [
                'product_ids' => $productIds->toArray(),
                'sellers_count' => $sellers->count(),
                'sellers' => $sellers->pluck('id', 'name')->toArray()
            ]);

            // Verificar que todos los vendedores tengan Stripe Connect configurado
            $sellersWithoutStripe = $sellers->filter(function($seller) {
                return !$seller->isStripeAccountActive();
            });

            if ($sellersWithoutStripe->isNotEmpty()) {
                Log::warning('❌ Vendedores sin Stripe Connect activo', [
                    'sellers_without_stripe' => $sellersWithoutStripe->pluck('id', 'name')->toArray()
                ]);
                
                throw new \Exception('Algunos vendedores no tienen configurada su cuenta de pagos. No se puede procesar la compra.');
            }

            // Log debug information con sistema de comisiones
            Log::info('Creating payment intent with commission split:', [
                'amount' => $amountInCents,
                'payment_method_id' => $request->payment_method_id,
                'order_id' => $order->id,
                'sellers_count' => $sellers->count(),
                'commission_rate' => '15%'
            ]);

            // 💰 CREAR PAGO CON DIVISIÓN AUTOMÁTICA DE COMISIONES
            // Detectar automáticamente si Connect está disponible
            if ($request->is('checkout-test*') || !$this->isStripeConnectAvailable()) {
                Log::info('🧪 MODO FALLBACK: Pago directo con comisiones calculadas localmente');
                
                // Crear pago directo (sin Connect) y calcular comisiones localmente
                $paymentResult = $this->createDirectPaymentWithCommission(
                    $amountInCents,
                    $request->payment_method_id,
                    $sellers,
                    $order->id
                );
                
                Log::info('✅ Pago directo creado con comisiones calculadas localmente');
            } else {
                // Pago real con Stripe Connect (solo si está disponible)
                Log::info('🔄 MODO CONNECT: Usando Stripe Connect para división automática');
                $paymentResult = $this->stripeConnectService->createPaymentWithSplit(
                    $amountInCents,
                    $request->payment_method_id,
                    [
                        'order_id' => $order->id,
                        'client_email' => $request->email,
                    ],
                    $sellers
                );
            }

            $paymentIntent = $paymentResult['payment_intent'];

            Log::info('💰 Payment intent with commission created successfully:', [
                'payment_intent_id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
                'platform_fee' => $paymentResult['platform_fee'],
                'seller_amount' => $paymentResult['seller_amount'],
                'seller_id' => $paymentResult['seller_id']
            ]);

            // Update order with payment intent ID and commission info
            $order->update([
                'stripe_payment_intent_id' => $paymentIntent->id,
                'platform_fee' => $paymentResult['platform_fee'] / 100, // Convertir a pesos
                'seller_amount' => $paymentResult['seller_amount'] / 100, // Convertir a pesos
            ]);

            // Handle the payment result
            if ($paymentIntent->status === 'succeeded') {
                Log::info('✅ Pago exitoso con comisiones distribuidas', [
                    'order_id' => $order->id,
                    'total_paid' => $amountInCents / 100,
                    'platform_commission' => ($paymentResult['platform_fee'] / 100),
                    'seller_receives' => ($paymentResult['seller_amount'] / 100),
                    'commission_rate' => '15%'
                ]);

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
                Log::error('❌ Pago con comisiones falló', [
                    'payment_status' => $paymentIntent->status,
                    'order_id' => $order->id,
                    'attempted_amount' => $amountInCents / 100,
                    'platform_fee' => $paymentResult['platform_fee'] / 100,
                    'seller_amount' => $paymentResult['seller_amount'] / 100
                ]);
                
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
            
            Log::warning('💳 Stripe Card Exception durante pago con comisiones: ' . $e->getMessage(), [
                'error_code' => $error->code,
                'error_type' => $error->type,
                'decline_code' => $error->decline_code ?? null,
                'order_id' => $order->id ?? 'unknown',
                'attempted_amount' => isset($amountInCents) ? $amountInCents / 100 : 'unknown'
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

    /**
     * Verificar si Stripe Connect está disponible
     */
    private function isStripeConnectAvailable()
    {
        try {
            Log::info('🔍 Verificando Stripe Connect...');
            
            // Verificar si hay vendedores con cuentas Connect configuradas
            $sellersWithConnect = \App\Models\Seller::where('stripe_account_id', '!=', null)
                                                  ->where('stripe_account_status', 'active')
                                                  ->where('stripe_charges_enabled', true)
                                                  ->count();
            
            if ($sellersWithConnect > 0) {
                Log::info("✅ CONNECT DISPONIBLE: {$sellersWithConnect} vendedores configurados");
                Log::info('� Verificando compatibilidad geográfica...');
                
                // Por ahora, debido a restricciones MX->MX, mantenemos modo directo
                // Pero dejamos la base para cuando se configure con cuentas internacionales
                Log::info('⚠️ RESTRICCIÓN MÉXICO: Connect MX->MX no soportado por Stripe');
                Log::info('💡 Para activar: vendedores deben configurar cuentas internacionales (US/EU)');
                Log::info('📋 Usando sistema híbrido: comisiones manuales por ahora');
                
                return false; // ❌ FORZAMOS MODO DIRECTO para usar sistema de depósitos manuales
            } else {
                Log::info('❌ No hay vendedores con Stripe Connect configurado');
                Log::info('📋 Usando sistema de pago directo + comisiones manuales (15%)');
                return false;
            }
            
        } catch (\Exception $e) {
            Log::warning("Error verificando Connect: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear pago directo sin Connect, calculando comisiones localmente
     */
    private function createDirectPaymentWithCommission($amountInCents, $paymentMethodId, $sellers, $orderId)
    {
        try {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
            
            // Crear pago simple (como el sistema original)
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'mxn',
                'payment_method' => $paymentMethodId,
                'confirm' => true,
                'automatic_payment_methods' => [
                    'enabled' => true,
                    'allow_redirects' => 'never' // Evitar métodos que requieren redirect
                ],
                'metadata' => [
                    'order_id' => $orderId,
                    'commission_calculated_locally' => 'true'
                ]
            ]);
            
            // Calcular comisiones localmente (15%)
            $platformFee = $amountInCents * 0.15;
            $sellerAmount = $amountInCents - $platformFee;
            
            Log::info('💰 Pago directo exitoso con comisión calculada:', [
                'payment_intent_id' => $paymentIntent->id,
                'total_amount' => $amountInCents / 100,
                'platform_fee' => $platformFee / 100,
                'seller_amount' => $sellerAmount / 100
            ]);
            
            return [
                'payment_intent' => $paymentIntent,
                'platform_fee' => $platformFee,
                'seller_amount' => $sellerAmount,
                'seller_id' => $sellers->first()->id ?? 1
            ];
            
        } catch (\Exception $e) {
            Log::error("Error en pago directo: " . $e->getMessage());
            throw $e;
        }
    }

}
