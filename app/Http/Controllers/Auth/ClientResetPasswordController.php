<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class ClientResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.cliente.contrasena-reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker('clients')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($client, $password) {
                $client->password = bcrypt($password);
                $client->setRememberToken(Str::random(60));
                $client->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            Session::flash('success', 'Tu contraseña ha sido actualizada correctamente.');
            return redirect()->route('client.login'); // Asegúrate de tener esta ruta
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
