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

        // Enviar correo de aprobación al vendedor
        try {
            \Mail::to($solicitud->email)->send(new \App\Mail\SellerAccountApproved($solicitud));
            
            // Log del envío exitoso
            \Log::info('Correo de aprobación enviado al vendedor', [
                'seller_id' => $solicitud->id,
                'seller_email' => $solicitud->email,
                'seller_name' => $solicitud->name,
                'approved_at' => now()
            ]);
            
            $message = 'Solicitud verificada con éxito y correo de bienvenida enviado.';
        } catch (\Exception $e) {
            // Log del error pero no fallar la aprobación
            \Log::error('Error enviando correo de aprobación al vendedor', [
                'seller_id' => $solicitud->id,
                'error' => $e->getMessage()
            ]);
            
            $message = 'Solicitud verificada con éxito. (Nota: hubo un problema enviando el correo de confirmación)';
        }

        return redirect()->route('admin.home')->with('success', $message);
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
