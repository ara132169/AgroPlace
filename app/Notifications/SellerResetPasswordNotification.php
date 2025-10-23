<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SellerResetPasswordNotification extends Notification
{
    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Recupera tu contraseña')
            ->line('Haz clic en el siguiente botón para establecer una nueva contraseña:')
            ->action('Cambiar contraseña', url(route('tienda.password.reset', [
                'token' => $this->token,
                'email' => $this->email,
            ])))
            ->line('Si no solicitaste este correo, ignóralo.')
            ->line('Este enlace caducará en 60 minutos.')
            ->line('Si no solicitaste el restablecimiento de la contraseña, no es necesario realizar ninguna acción.')
            ->salutation('Saludos, Agromarket');
    }
}
