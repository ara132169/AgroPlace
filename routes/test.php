<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Services\StripeService;

// Test route for Stripe connectivity
Route::get('/test-stripe', function () {
    try {
        $stripeService = new StripeService();
        
        // Test creating a simple payment intent
        $paymentIntent = $stripeService->createPaymentIntent([
            'amount' => 100, // $1.00 in cents
            'currency' => 'usd',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);
        
        return response()->json([
            'success' => true,
            'payment_intent_id' => $paymentIntent->id,
            'status' => $paymentIntent->status,
            'message' => 'Stripe connection successful!'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'message' => 'Stripe connection failed!'
        ], 500);
    }
});