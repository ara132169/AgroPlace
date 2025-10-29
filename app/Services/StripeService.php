<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret'));
    }

    /**
     * Crear un Payment Intent
     */
    public function createPaymentIntent($data)
    {
        try {
            Log::info('StripeService: createPaymentIntent called with data:', $data);
            
            // Si se pasa un array de datos, usarlo directamente
            if (is_array($data)) {
                Log::info('StripeService: Using array format');
                
                // El amount ya viene en centavos desde el controller, no convertir
                $paymentIntent = PaymentIntent::create($data);
            } else {
                Log::info('StripeService: Using legacy format');
                
                // Compatibilidad con el formato anterior (amount, currency, metadata)
                $amount = $data;
                $currency = func_get_arg(1) ?? 'usd';
                $metadata = func_get_arg(2) ?? [];
                
                $paymentIntent = PaymentIntent::create([
                    'amount' => $amount * 100, // Stripe usa centavos
                    'currency' => $currency,
                    'metadata' => $metadata,
                    'automatic_payment_methods' => [
                        'enabled' => true,
                    ],
                ]);
            }

            Log::info('StripeService: PaymentIntent created successfully:', [
                'id' => $paymentIntent->id,
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency,
                'status' => $paymentIntent->status,
            ]);

            return $paymentIntent;
        } catch (\Exception $e) {
            Log::error('Error creando Payment Intent: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Confirmar un Payment Intent
     */
    public function confirmPaymentIntent($paymentIntentId, $paymentMethodId)
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            $paymentIntent->confirm([
                'payment_method' => $paymentMethodId,
            ]);

            return $paymentIntent;
        } catch (\Exception $e) {
            Log::error('Error confirmando Payment Intent: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Obtener un Payment Intent
     */
    public function getPaymentIntent($paymentIntentId)
    {
        try {
            return PaymentIntent::retrieve($paymentIntentId);
        } catch (\Exception $e) {
            Log::error('Error obteniendo Payment Intent: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verificar webhook de Stripe
     */
    public function verifyWebhook($payload, $signature)
    {
        try {
            return Webhook::constructEvent(
                $payload,
                $signature,
                config('stripe.webhook_secret')
            );
        } catch (SignatureVerificationException $e) {
            Log::error('Error verificando webhook: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Crear un reembolso
     */
    public function createRefund($paymentIntentId, $amount = null)
    {
        try {
            $refundData = [
                'payment_intent' => $paymentIntentId,
            ];

            if ($amount) {
                $refundData['amount'] = $amount * 100; // Convertir a centavos
            }

            return \Stripe\Refund::create($refundData);
        } catch (\Exception $e) {
            Log::error('Error creando reembolso: ' . $e->getMessage());
            throw $e;
        }
    }
}