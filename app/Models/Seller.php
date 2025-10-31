<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use App\Notifications\SellerResetPasswordNotification;


class Seller extends Authenticatable implements CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable,CanResetPasswordTrait;


    protected $guard = "seller";
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
        'verified',
        'status',
        'payment_method',
        'payment_email'
        
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

    public function getPictureAttribute($value){
        if( $value ){
            return asset('/images/users/sellers/'.$value);
        }else{
            return asset('/images/users/default-avatar.png');
        }
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }

    /**
     * Relación para obtener las órdenes de productos vendidos por este vendedor
     */
    public function orderItems()
    {
        return $this->hasManyThrough(
            OrderItem::class,
            Product::class,
            'seller_id', // Foreign key en products table
            'product_id', // Foreign key en order_items table
            'id', // Local key en sellers table
            'id' // Local key en products table
        );
    }

    /**
     * Relación para obtener las órdenes que contienen productos de este vendedor
     */
    public function orders()
    {
        return $this->hasManyThrough(
            Order::class,
            OrderItem::class,
            'product_id', // Foreign key en order_items table
            'id', // Foreign key en orders table
            'id', // Local key en sellers table (a través de products)
            'order_id' // Local key en order_items table
        )->join('products', 'order_items.product_id', '=', 'products.id')
         ->where('products.seller_id', $this->id);
    }

    protected static function booted()
    {
        static::created(function ($seller) {
            // Verifica que no tenga ya un shop
            if (!$seller->shop) {
                Shop::create([
                    'seller_id' => $seller->id,
                    'shop_name' => $seller->name,
                    'shop_phone' => '000-000-0000', // valores por defecto
                    'shop_address' => 'Dirección no especificada',
                    'shop_description' => 'Descripción de tienda',
                    'shop_logo' => 'default-logo.png',
                ]);
            }
        });
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new SellerResetPasswordNotification($token, $this->email));
    }
    
}
