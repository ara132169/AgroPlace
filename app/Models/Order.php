<?php

// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'client_id',
        'seller_id',
        'buyer_type',
        'shipping_name',
        'shipping_address',
        'shipping_company',
        'shipping_country',
        'shipping_city',
        'shipping_state',
        'shipping_cp',
        'shipping_phone',
        'shipping_email',
        'total',
        'status',
        // Campos de Stripe
        'payment_method',
        'stripe_payment_intent_id',
        'stripe_payment_status',
        'payment_currency',
        // Campos de comisiones
        'platform_fee',
        'seller_amount',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    // Comprador: puede ser cliente o vendedor
    public function buyerClient()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function buyerSeller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function buyer()
    {
        if ($this->buyer_type === 'client') {
            return $this->buyerClient();
        } else {
            return $this->buyerSeller();
        }
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

}

