<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ClientResetPasswordNotification;


class Client extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guard = "client";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'name',
        'username',
        'email',
        'password',
        'picture',
        'address',
        'phone',
        'email_verified_at',
        'status'
     
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ClientResetPasswordNotification($token));
    }

    /**
     * Relación con las órdenes del cliente
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relación con los elementos del carrito
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Relación con la lista de deseos (si existe)
     */
    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }
}
