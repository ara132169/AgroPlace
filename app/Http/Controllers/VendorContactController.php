<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Seller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class VendorContactController extends Controller
{
    public function enviarMensaje(Request $request, $vendorSlug)
    {
        // Validar los datos del formulario
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'message.required' => 'El mensaje es obligatorio.',
            'message.max' => 'El mensaje no puede exceder 1000 caracteres.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Buscar el vendedor
        $vendedor = Seller::where('username', $vendorSlug)->first();
        
        if (!$vendedor) {
            return redirect()->back()->with('error', 'Vendedor no encontrado.');
        }

        // Datos del mensaje
        $nombre = $request->input('name');
        $email = $request->input('email');
        $mensaje = $request->input('message');
        $emailVendedor = $vendedor->email;
        $nombreTienda = $vendedor->shop->shop_name ?? 'Tu tienda';

        try {
            // Enviar email al vendedor
            Mail::send('emails.contacto-vendedor', [
                'nombre' => $nombre,
                'email' => $email,
                'mensaje' => $mensaje,
                'nombreTienda' => $nombreTienda,
                'vendedor' => $vendedor
            ], function ($mail) use ($emailVendedor, $email, $nombre, $nombreTienda) {
                $mail->to($emailVendedor)
                     ->subject("Nuevo mensaje de contacto para {$nombreTienda}")
                     ->replyTo($email, $nombre);
            });

            return redirect()->back()->with('success', '¡Mensaje enviado exitosamente! El vendedor se pondrá en contacto contigo pronto.');
            
        } catch (\Exception $e) {
            // Log del error para debugging
            \Log::error('Error al enviar mensaje de contacto: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Hubo un problema al enviar el mensaje. Por favor, inténtalo más tarde.');
        }
    }
}