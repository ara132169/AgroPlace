<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\Transfer;
use Stripe\PaymentIntent;
use App\Models\Seller;
use Illuminate\Support\Facades\Log;

class StripeConnectService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Crear cuenta de Stripe Connect para vendedor
     */
    public function createConnectedAccount(Seller $seller)
    {
        try {
            // Verificar si Connect está disponible
            if (!$this->isConnectAvailable()) {
                // Simular cuenta Connect para desarrollo
                Log::info("🧪 Connect no disponible, simulando cuenta para desarrollo");
                
                $fakeAccountId = 'acct_dev_' . $seller->id . '_' . time();
                
                $seller->update([
                    'stripe_account_id' => $fakeAccountId,
                    'stripe_account_status' => 'active',
                    'stripe_charges_enabled' => true,
                    'stripe_payouts_enabled' => true
                ]);
                
                Log::info("✅ Cuenta simulada creada: {$fakeAccountId}");
                
                return (object)[
                    'id' => $fakeAccountId,
                    'charges_enabled' => true,
                    'payouts_enabled' => true
                ];
            }
            $account = Account::create([
                'type' => 'express', // Más fácil para vendedores
                'country' => 'MX', // México
                'email' => $seller->email,
                'business_type' => 'individual',
                'individual' => [
                    'email' => $seller->email,
                    'first_name' => explode(' ', $seller->name)[0] ?? $seller->name,
                    'last_name' => explode(' ', $seller->name)[1] ?? '',
                ],
                'business_profile' => [
                    'name' => $seller->name,
                    'product_description' => 'Productos agrícolas y alimentarios',
                    'support_email' => $seller->email,
                ],
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
                'tos_acceptance' => [
                    'service_agreement' => 'recipient',
                ],
            ]);

            // Actualizar vendedor con ID de cuenta
            $seller->update([
                'stripe_account_id' => $account->id,
                'stripe_account_status' => 'pending'
            ]);

            Log::info("Cuenta Stripe Connect creada para vendedor: {$seller->id}", [
                'seller_id' => $seller->id,
                'stripe_account_id' => $account->id
            ]);

            return $account;

        } catch (\Exception $e) {
            Log::error("Error creando cuenta Stripe Connect: " . $e->getMessage(), [
                'seller_id' => $seller->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Generar link de onboarding para completar configuración
     */
    public function createAccountLink(Seller $seller, $returnUrl, $refreshUrl)
    {
        try {
            if (!$seller->stripe_account_id) {
                throw new \Exception('El vendedor no tiene cuenta de Stripe Connect');
            }

            // Si es una cuenta simulada (para desarrollo sin Connect)
            if (str_starts_with($seller->stripe_account_id, 'acct_dev_') || str_starts_with($seller->stripe_account_id, 'acct_demo_')) {
                Log::info("🧪 Simulando onboarding para cuenta de desarrollo");
                
                // Simular proceso completado inmediatamente
                $seller->update([
                    'stripe_account_status' => 'active',
                    'stripe_charges_enabled' => true,
                    'stripe_payouts_enabled' => true
                ]);
                
                // Retornar URL de éxito directamente
                return $returnUrl . '?simulated=true';
            }

            $accountLink = AccountLink::create([
                'account' => $seller->stripe_account_id,
                'refresh_url' => $refreshUrl,
                'return_url' => $returnUrl,
                'type' => 'account_onboarding',
            ]);

            return $accountLink->url;

        } catch (\Exception $e) {
            Log::error("Error creando link de onboarding: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verificar estado de la cuenta Connect
     */
    public function checkAccountStatus(Seller $seller)
    {
        try {
            if (!$seller->stripe_account_id) {
                return false;
            }

            $account = Account::retrieve($seller->stripe_account_id);

            $seller->update([
                'stripe_account_status' => $account->details_submitted ? 'active' : 'pending',
                'stripe_charges_enabled' => $account->charges_enabled,
                'stripe_payouts_enabled' => $account->payouts_enabled
            ]);

            return [
                'charges_enabled' => $account->charges_enabled,
                'payouts_enabled' => $account->payouts_enabled,
                'details_submitted' => $account->details_submitted,
            ];

        } catch (\Exception $e) {
            Log::error("Error verificando cuenta Stripe: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Procesar pago con división automática
     */
    public function createPaymentWithSplit($amount, $paymentMethodId, $orderData, $sellers)
    {
        try {
            $platformCommission = 15; // 15% para la plataforma
            $totalAmount = $amount; // Monto en centavos (ya convertido)

            // Calcular comisión de la plataforma
            $platformFee = intval($totalAmount * ($platformCommission / 100));
            
            // El monto que va al vendedor es el total menos la comisión
            $sellerAmount = $totalAmount - $platformFee;

            Log::info("💰 Procesando pago dividido", [
                'total_amount' => $totalAmount,
                'platform_fee' => $platformFee,
                'seller_amount' => $sellerAmount,
                'commission_rate' => $platformCommission
            ]);

            // Si hay múltiples vendedores, dividir entre ellos
            if (count($sellers) > 1) {
                // Lógica para múltiples vendedores (para futuro)
                // Por ahora manejamos un vendedor principal
                $mainSeller = $sellers->first();
            } else {
                $mainSeller = $sellers->first();
            }

            // Verificar que el vendedor tenga cuenta activa
            if (!$mainSeller->stripe_charges_enabled) {
                throw new \Exception("El vendedor no tiene configurada su cuenta de pagos");
            }

            // Crear Payment Intent con transferencia automática
            $paymentIntent = PaymentIntent::create([
                'amount' => $totalAmount,
                'currency' => 'mxn',
                'payment_method' => $paymentMethodId,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => route('cliente.checkout'),
                'application_fee_amount' => $platformFee, // Comisión para la plataforma
                'transfer_data' => [
                    'destination' => $mainSeller->stripe_account_id, // Cuenta del vendedor
                ],
                'metadata' => [
                    'order_id' => $orderData['order_id'] ?? 'N/A',
                    'client_email' => $orderData['client_email'] ?? 'N/A',
                    'seller_id' => $mainSeller->id,
                    'platform_fee' => $platformFee,
                    'seller_amount' => $sellerAmount,
                ]
            ]);

            Log::info("✅ Payment Intent creado con división exitosa", [
                'payment_intent_id' => $paymentIntent->id,
                'seller_id' => $mainSeller->id,
                'platform_fee' => $platformFee,
                'seller_receives' => $sellerAmount
            ]);

            return [
                'success' => true,
                'payment_intent' => $paymentIntent,
                'platform_fee' => $platformFee,
                'seller_amount' => $sellerAmount,
                'seller_id' => $mainSeller->id
            ];

        } catch (\Exception $e) {
            Log::error("❌ Error en pago dividido: " . $e->getMessage(), [
                'amount' => $amount,
                'sellers_count' => count($sellers),
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Crear transferencia manual (si se necesita)
     */
    public function createTransfer($amount, $destinationAccount, $orderData = [])
    {
        try {
            $transfer = Transfer::create([
                'amount' => $amount,
                'currency' => 'mxn',
                'destination' => $destinationAccount,
                'metadata' => $orderData
            ]);

            return $transfer;

        } catch (\Exception $e) {
            Log::error("Error creando transferencia: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Verificar si Stripe Connect está disponible en la cuenta
     */
    public function isConnectAvailable()
    {
        try {
            // Intentar crear una cuenta de prueba para verificar Connect
            Account::create([
                'type' => 'express',
                'country' => 'MX',
                'email' => 'test@connect.check',
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
            ]);
            
            return true;
            
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            if (str_contains($e->getMessage(), 'signed up for Connect')) {
                Log::warning("⚠️ Stripe Connect no está habilitado en esta cuenta");
                return false;
            }
            
            // Otros errores podrían indicar que Connect sí está disponible
            return true;
            
        } catch (\Exception $e) {
            Log::error("Error verificando Connect: " . $e->getMessage());
            return false;
        }
    }
}