<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use App\Models\Seller;

class NewSellerRegistrationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $seller;

    /**
     * Create a new message instance.
     */
    public function __construct(Seller $seller)
    {
        $this->seller = $seller;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[AgroPlace Admin] Nueva Solicitud de Registro de Tienda',
            from: new Address(
                config('mail.from.address', 'noreply@agroplace.com'),
                config('mail.from.name', 'AgroPlace System')
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-new-seller-notification',
            with: [
                'sellerName' => $this->seller->name,
                'sellerEmail' => $this->seller->email,
                'registrationDate' => $this->seller->created_at ? $this->seller->created_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i'),
                'sellerId' => $this->seller->id,
                'adminPanelUrl' => route('admin.home'),
                'siteName' => config('app.name', 'AgroPlace')
            ]
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
