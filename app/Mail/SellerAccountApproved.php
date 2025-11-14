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

class SellerAccountApproved extends Mailable
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
            subject: '🎉 ¡Tu cuenta ha sido aprobada! - Bienvenido a AgroPlace',
            from: new Address(
                config('mail.from.address', 'noreply@agroplace.com'),
                config('mail.from.name', 'AgroPlace')
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.seller-account-approved',
            with: [
                'sellerName' => $this->seller->name,
                'sellerEmail' => $this->seller->email,
                'loginUrl' => route('tienda.ingresar'),
                'dashboardUrl' => route('tienda.home'),
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
