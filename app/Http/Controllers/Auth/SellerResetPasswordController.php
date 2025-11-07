<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use App\Models\Seller;

class SellerResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token)
    {
        return view('auth.tienda.restablecer-contrasena', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ], [
            'token.required' => 'Token de verificación requerido.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        try {
            $status = Password::broker('sellers')->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($seller, $password) {
                    $seller->password = Hash::make($password);
                    $seller->setRememberToken(Str::random(60));
                    $seller->save();
                    
                    \Log::info('Contraseña actualizada para vendedor: ' . $seller->email);
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return redirect()->route('tienda.ingresar')->with('success', 'Tu contraseña ha sido actualizada correctamente. Ya puedes iniciar sesión.');
            }

            return back()->withErrors(['email' => 'El enlace de recuperación ha expirado o es inválido. Solicita uno nuevo.']);
            
        } catch (\Exception $e) {
            \Log::error('Error al restablecer contraseña: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Error al actualizar la contraseña. Inténtalo nuevamente.']);
        }
    }
}

