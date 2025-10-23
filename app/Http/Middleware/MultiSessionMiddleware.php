<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class MultiSessionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Detecta el guard en base al nombre de la ruta
        $guard = null;

        if ($request->is('admin/*')) {
            $guard = 'admin';
        } elseif ($request->is('tienda/*')) {
            $guard = 'seller';
        } elseif ($request->is('cliente/*')) {
            $guard = 'client';
        } else {
            $guard = 'web';
        }

        // Cambia el nombre de la cookie de sesión dinámicamente
        config(['session.cookie' => $guard . '_session']);

        return $next($request);
    }
}
