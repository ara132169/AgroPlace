<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendedorAprobado extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    
     public function toMail($notifiable)
     {
         return (new MailMessage)
             ->subject('¡Tu cuenta de vendedor ha sido aprobada!')
             ->greeting('Hola ' . $notifiable->nombre . ',')
             ->line('Nos complace informarte que tu solicitud como vendedor en Agro MarketPlace ha sido aprobada.')
             ->line('Ahora puedes iniciar sesión y comenzar a publicar tus productos.')
             ->action('Ir al panel', url('/tienda/login'))
             ->line('¡Gracias por unirte a nuestra comunidad!');
     }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
