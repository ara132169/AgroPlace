<?php

// app/Http/Controllers/SolicitudVendedorController.php

namespace App\Http\Controllers;

use App\Models\Seller;
use Illuminate\Http\Request;

class SolicitudVendedorController extends Controller
{
    // Verificar (aprobar) solicitud
    public function aprobarVendedor($id)
    {
        // Buscar la solicitud de vendedor
        $solicitud = Seller::findOrFail($id);

        // Actualizar el estado de la solicitud a 'verified'
        $solicitud->status = 'Active';
        $solicitud->verified = 1;  // Cambiar el campo verified a 1 (verificado)
        $solicitud->save();

        return redirect()->route('admin.home')->with('success', 'Solicitud verificada con éxito');
    }

    // Eliminar (rechazar) solicitud
    public function rechazarVendedor($id)
    {
        // Buscar la solicitud de vendedor
        $solicitud = Seller::findOrFail($id);

        // Eliminar la solicitud
        $solicitud->delete();

        return redirect()->route('admin.home')->with('success', 'Solicitud eliminada');
    }
}
