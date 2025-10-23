<?php

// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'client_id',
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
        'status'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

}

