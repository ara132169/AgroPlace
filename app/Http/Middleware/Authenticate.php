<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            if ($request->routeIs('admin.*')) {
                session()->flash('fail', 'Debes iniciar sesión primero');
                return route('admin.login');
            }
    
            if ($request->routeIs('seller.*')) {
                session()->flash('fail', 'Debes iniciar sesión primero');
                return route('tienda.ingresar');
            }
    
            // ⚠️ Este return cubre cualquier otra ruta protegida que no sea admin o seller
            return route('cliente.ingresar'); // O la ruta general de login
        }
    
        return null;
}
}