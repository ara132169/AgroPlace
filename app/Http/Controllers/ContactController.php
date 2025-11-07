<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function enviarMensaje(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email_1' => 'required|email|max:255',
            'message' => 'required|string|max:2000',
        ], [
            'username.required' => 'El nombre completo es obligatorio',
            'email_1.required' => 'El correo electrónico es obligatorio',
            'email_1.email' => 'Por favor ingresa un correo electrónico válido',
            'message.required' => 'El mensaje es obligatorio',
            'message.max' => 'El mensaje no puede tener más de 2000 caracteres',
        ]);

        // Obtener el email del administrador desde la configuración del sitio
        $adminEmail = get_settings()->site_email ?? config('mail.from.address');
        
        if (empty($adminEmail)) {
            return redirect()->back()->with('error', 'Error de configuración: No se ha configurado un email de contacto.');
        }

        // Preparar datos del email
        $emailData = [
            'nombre_remitente' => $request->username,
            'email_remitente' => $request->email_1,
            'mensaje' => $request->message,
            'fecha' => now()->format('d/m/Y H:i'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        try {
            // Renderizar el template de email
            $emailBody = view('email-templates.contacto-general', $emailData)->render();
            
            // Configurar el email
            $mailConfig = [
                'mail_from_email' => $request->email_1, // Email del remitente como FROM para poder responder
                'mail_from_name' => $request->username,
                'mail_recipient_email' => $adminEmail,
                'mail_recipient_name' => 'Administrador AgroPlace',
                'mail_subject' => 'Nuevo mensaje de contacto desde el sitio web',
                'mail_body' => $emailBody
            ];
            
            $emailSent = sendEmail($mailConfig);
            
            if ($emailSent) {
                return redirect()->back()->with('success', '¡Gracias por contactarnos! Tu mensaje ha sido enviado exitosamente. Te responderemos pronto.');
            } else {
                return redirect()->back()->with('error', 'Hubo un problema al enviar tu mensaje. Por favor intenta nuevamente o contáctanos directamente.');
            }
        } catch (\Exception $e) {
            Log::error('Error al enviar email de contacto general: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un problema al enviar tu mensaje. Por favor intenta nuevamente.')
                ->withInput();
        }
    }
}