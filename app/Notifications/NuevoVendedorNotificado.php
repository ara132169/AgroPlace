<?php

// app/Notifications/NuevoVendedorNotificado.php

namespace App\Notifications;

use App\Models\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NuevoVendedorNotificado extends Notification
{
    use Queueable;

    protected $vendedor;

    // Constructor para pasar el vendedor
    public function __construct(Seller $vendedor)
    {
        $this->vendedor = $vendedor;
    }

    // Definir la forma en que se envía la notificación (por ejemplo, por correo)
    public function via($notifiable)
    {
        return ['mail'];  // Solo usaremos correo para esta notificación
    }

    // Contenido del correo
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Nuevo Vendedor Registrado')
                    ->line('Se ha registrado un nuevo vendedor en la plataforma.')
                    ->line('Correo del Vendedor: ' . $this->vendedor->email)
                    ->action('Ver Vendedor', url('/admin/home'))
                    ->line('Gracias por utilizar nuestra plataforma.');
    }

    // Otros métodos si deseas enviar la notificación por otro medio (ej. base de datos)
    public function toArray($notifiable)
    {
        return [
            'vendedor' => $this->vendedor->email,
        ];
    }
}

