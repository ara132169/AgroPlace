<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\StripeConnectService;

class StripeConnectController extends Controller
{
    protected $stripeConnectService;

    public function __construct(StripeConnectService $stripeConnectService)
    {
        $this->stripeConnectService = $stripeConnectService;
        $this->middleware('auth:seller');
    }

    /**
     * Mostrar página de configuración de Stripe Connect
     */
    public function showConfig()
    {
        $seller = Auth::guard('seller')->user();
        
        // Verificar si Stripe Connect está disponible
        $connectAvailable = $this->stripeConnectService->isConnectAvailable();
        
        // Verificar estado actual de la cuenta
        $accountStatus = false;
        if ($seller->hasStripeAccount()) {
            $accountStatus = $this->stripeConnectService->checkAccountStatus($seller);
        }

        return view('seller.stripe-connect', compact('seller', 'connectAvailable', 'accountStatus'));
    }

    /**
     * Mostrar dashboard de Stripe Connect
     */
    public function dashboard()
    {
        $seller = Auth::guard('seller')->user();
        
        // Verificar estado actual de la cuenta
        if ($seller->hasStripeAccount()) {
            $accountStatus = $this->stripeConnectService->checkAccountStatus($seller);
        } else {
            $accountStatus = false;
        }

        return view('back.pages.tienda.stripe-connect', compact('seller', 'accountStatus'));
    }

    /**
     * Iniciar proceso de onboarding
     */
    public function startOnboarding()
    {
        try {
            $seller = Auth::guard('seller')->user();

            // Crear cuenta si no existe
            if (!$seller->hasStripeAccount()) {
                $this->stripeConnectService->createConnectedAccount($seller);
                $seller->refresh(); // Recargar datos
            }

            // Generar link de onboarding
            $returnUrl = route('tienda.stripe.success');
            $refreshUrl = route('tienda.stripe.refresh');
            
            $onboardingUrl = $this->stripeConnectService->createAccountLink(
                $seller, 
                $returnUrl, 
                $refreshUrl
            );

            Log::info("🔗 Onboarding iniciado para vendedor: {$seller->id}", [
                'seller_id' => $seller->id,
                'onboarding_url' => $onboardingUrl
            ]);

            return redirect($onboardingUrl);

        } catch (\Exception $e) {
            Log::error("❌ Error en onboarding: " . $e->getMessage());
            
            // Mostrar error específico para debugging
            $errorMessage = 'Error al configurar tu cuenta de pagos: ' . $e->getMessage();
            
            return redirect()->route('tienda.stripe.dashboard')
                ->with('error', $errorMessage);
        }
    }

    /**
     * Callback de éxito del onboarding
     */
    public function onboardingSuccess()
    {
        $seller = Auth::guard('seller')->user();
        
        // Verificar estado actualizado
        $this->stripeConnectService->checkAccountStatus($seller);
        
        return redirect()->route('tienda.stripe.dashboard')
            ->with('success', '¡Excelente! Tu cuenta de pagos ha sido configurada correctamente.');
    }

    /**
     * Callback para refrescar el onboarding
     */
    public function onboardingRefresh()
    {
        return redirect()->route('tienda.stripe.connect');
    }

    /**
     * Verificar estado de la cuenta
     */
    public function checkStatus()
    {
        try {
            $seller = Auth::guard('seller')->user();
            
            if (!$seller->hasStripeAccount()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes cuenta de Stripe configurada'
                ]);
            }

            $status = $this->stripeConnectService->checkAccountStatus($seller);
            
            return response()->json([
                'success' => true,
                'status' => $status,
                'can_receive_payments' => $seller->isStripeAccountActive()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error verificando estado: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Refrescar y actualizar estado de la cuenta Stripe
     */
    public function refreshAccountStatus()
    {
        try {
            $seller = Auth::guard('seller')->user();
            
            if (!$seller->hasStripeAccount()) {
                return redirect()->route('tienda.stripe.config')
                    ->with('error', 'No tienes una cuenta de Stripe configurada.');
            }

            // Verificar estado actualizado desde Stripe
            $accountStatus = $this->stripeConnectService->checkAccountStatus($seller);
            
            Log::info("🔄 Estado de cuenta actualizado para vendedor: {$seller->id}", [
                'seller_id' => $seller->id,
                'account_status' => $accountStatus
            ]);

            // Mensaje basado en el estado
            $message = 'Estado de cuenta actualizado correctamente.';
            if ($seller->isStripeAccountActive()) {
                $message = '✅ Tu cuenta está activa y lista para recibir pagos.';
            } elseif ($seller->stripe_account_status === 'pending') {
                $message = '⏳ Tu cuenta está en proceso de verificación.';
            } else {
                $message = '⚠️ Tu cuenta requiere información adicional.';
            }

            return redirect()->route('tienda.stripe.config')
                ->with('success', $message);

        } catch (\Exception $e) {
            Log::error("❌ Error actualizando estado de cuenta: " . $e->getMessage());
            
            return redirect()->route('tienda.stripe.config')
                ->with('error', 'Error al actualizar el estado de la cuenta: ' . $e->getMessage());
        }
    }

    /**
     * Desconectar cuenta Stripe
     */
    public function disconnect()
    {
        try {
            $seller = Auth::guard('seller')->user();
            
            if ($seller->hasStripeAccount()) {
                // Limpiar datos de Stripe del vendedor
                $seller->update([
                    'stripe_account_id' => null,
                    'stripe_account_status' => null,
                    'stripe_charges_enabled' => false,
                    'stripe_payouts_enabled' => false,
                ]);
                
                Log::info("Vendedor {$seller->id} desconectó su cuenta Stripe");
                
                return response()->json([
                    'success' => true,
                    'message' => 'Cuenta Stripe desconectada correctamente'
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No hay cuenta conectada'
            ]);
            
        } catch (\Exception $e) {
            Log::error("Error desconectando cuenta Stripe: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error desconectando cuenta'
            ]);
        }
    }
}