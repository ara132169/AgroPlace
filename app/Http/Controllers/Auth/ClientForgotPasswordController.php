<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;

class ClientForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.cliente.contrasena-olvidada');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('clients')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            Session::flash('success', 'Se ha enviado un enlace de recuperación a tu correo.');
            return back();
        }

        return back()->withErrors(['email' => __($status)]);
    }
}

