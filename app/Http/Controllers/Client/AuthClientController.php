<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\Client;
use App\Mail\ClientRegistrationConfirmation;

class AuthClientController extends Controller
{
    public function showLoginForm()
    {
        return view('back.pages.cliente.auth.ingresar');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('client')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->route('cliente.perfil');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->withInput();
    }

    public function showRegisterForm()
    {
        return view('back.pages.cliente.auth.registrarse');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:clients',
            'email' => 'required|email|unique:clients',
            'password' => 'required|confirmed|min:6',
        ]);

        $client = Client::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Enviar correo de confirmación de registro
        try {
            Mail::to($client->email)->send(new ClientRegistrationConfirmation($client));
        } catch (\Exception $e) {
            // Log el error pero no interrumpir el proceso de registro
            \Log::error('Error enviando correo de confirmación de registro: ' . $e->getMessage());
        }

        Auth::guard('client')->login($client);

        return redirect()->route('cliente.panel')->with('success', '¡Bienvenido! Tu cuenta ha sido creada exitosamente. Revisa tu correo electrónico para más información.');
    }

            public function logout()
        {
            Auth::guard('client')->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('cliente.ingresar')->with('success', 'Has cerrado sesión correctamente.');
        }

        public function loginHandler(Request $request)
        {
            $request->validate([
                'login_id' => 'required|string',
                'password' => 'required|string',
            ]);

            $login_type = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            if (Auth::guard('client')->attempt([$login_type => $request->login_id, 'password' => $request->password], $request->filled('remember'))) {
                return redirect()->route('cliente.panel');
            }

            return redirect()->back()->withErrors(['login_id' => 'Credenciales inválidas'])->withInput();
        }

}
