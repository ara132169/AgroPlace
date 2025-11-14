<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Client;
use App\Models\GeneralSetting;

class ClientRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $siteSettings;

    /**
     * Create a new message instance.
     */
    public function __construct($client)
    {
        $this->client = $client;
        $this->siteSettings = GeneralSetting::first() ?? (object) [
            'site_name' => config('app.name', 'AgroMarket'),
            'site_email' => config('mail.admin_email', 'soporte@agromarket.com'),
            'site_phone' => '+52 (XXX) XXX-XXXX',
            'site_logo' => null,
            'site_address' => 'México'
        ];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '¡Bienvenido a ' . $this->siteSettings->site_name . '! - Tu cuenta ha sido creada exitosamente',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.client.registration-confirmation',
            with: [
                'client' => $this->client,
                'siteSettings' => $this->siteSettings,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
