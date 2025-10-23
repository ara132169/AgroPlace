<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;


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
        ]);

        $status = Password::broker('sellers')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($seller, $password) {
                $seller->password = bcrypt($password);
                $seller->setRememberToken(Str::random(60));
                $seller->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            Session::flash('success', 'Tu contraseña ha sido actualizada correctamente.');
            return redirect()->route('tienda.ingresar'); // Asegúrate de que esta ruta exista
        }

        return back()->withErrors(['email' => [__($status)]]);
    }
}

