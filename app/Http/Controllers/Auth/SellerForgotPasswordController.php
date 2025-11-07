<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use App\Models\Seller;

class SellerForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.tienda.contrasena-olvidada');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:sellers,email'
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.exists' => 'No encontramos una cuenta con este correo electrónico.'
        ]);

        // Verificar que el vendedor existe y está activo
        $seller = Seller::where('email', $request->email)->first();
        
        if (!$seller) {
            return back()->withErrors(['email' => 'No encontramos una cuenta con este correo electrónico.']);
        }

        if ($seller->status != 1) {
            return back()->withErrors(['email' => 'Tu cuenta no está activa. Contacta al administrador.']);
        }

        try {
            $status = Password::broker('sellers')->sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return back()->with('success', 'Se ha enviado un enlace de recuperación a tu correo electrónico. Revisa tu bandeja de entrada y spam.');
            }

            return back()->withErrors(['email' => 'Hubo un problema al enviar el correo. Inténtalo nuevamente.']);
            
        } catch (\Exception $e) {
            \Log::error('Error enviando email de recuperación: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Error al enviar el correo. Inténtalo más tarde.']);
        }
    }
}
