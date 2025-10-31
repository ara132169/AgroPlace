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
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function buyer()
    {
        if ($this->buyer_type === 'client') {
            return $this->client();
        } else {
            return $this->seller();
        }
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

}

