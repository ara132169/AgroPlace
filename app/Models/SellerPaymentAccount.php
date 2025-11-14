<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SellerPaymentAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'account_type',
        'account_holder_name',
        'card_last_four',
        'card_brand',
        'card_token',
        'encrypted_card_number',
        'bank_name',
        'account_number_last_four', 
        'clabe',
        'encrypted_account_number',
        'encrypted_clabe',
        'paypal_email',
        'other_account_info',
        'is_active',
        'is_verified',
        'verified_at',
        'verified_by',
        'admin_notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_verified' => 'boolean', 
        'verified_at' => 'datetime',
    ];

    /**
     * Relación con vendedor
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Admin que verificó la cuenta
     */
    public function verifiedBy()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }

    /**
     * Depósitos asociados
     */
    public function deposits()
    {
        return $this->hasMany(ManualDeposit::class, 'payment_account_id');
    }

    /**
     * Encriptar información sensible
     */
    public function setCardTokenAttribute($value)
    {
        if ($value) {
            $this->attributes['card_token'] = Crypt::encryptString($value);
        }
    }

    public function setEncryptedCardNumberAttribute($value)
    {
        if ($value) {
            $this->attributes['encrypted_card_number'] = Crypt::encryptString($value);
        }
    }

    public function setEncryptedAccountNumberAttribute($value)
    {
        if ($value) {
            $this->attributes['encrypted_account_number'] = Crypt::encryptString($value);
        }
    }

    public function setEncryptedClabeAttribute($value)
    {
        if ($value) {
            $this->attributes['encrypted_clabe'] = Crypt::encryptString($value);
        }
    }

    /**
     * Desencriptar información
     */
    public function getCardTokenAttribute($value)
    {
        if ($value) {
            return Crypt::decryptString($value);
        }
        return null;
    }

    public function getEncryptedCardNumberAttribute($value)
    {
        if ($value) {
            return Crypt::decryptString($value);
        }
        return null;
    }

    public function getEncryptedAccountNumberAttribute($value)
    {
        if ($value) {
            return Crypt::decryptString($value);
        }
        return null;
    }

    public function getEncryptedClabeAttribute($value)
    {
        if ($value) {
            return Crypt::decryptString($value);
        }
        return null;
    }

    /**
     * Información resumida para mostrar
     */
    public function getDisplayInfoAttribute()
    {
        switch ($this->account_type) {
            case 'debit_card':
            case 'card':
                return "💳 {$this->card_brand} ****{$this->card_last_four}";
            case 'bank_account':
            case 'bank':
                return "🏦 {$this->bank_name} ****{$this->account_number_last_four}";
            case 'paypal':
                return "📧 PayPal: {$this->paypal_email}";
            default:
                return "📄 {$this->account_type}";
        }
    }

    /**
     * Información COMPLETA para administradores (con números reales)
     * ⚠️ SOLO para uso administrativo - contiene datos sensibles
     */
    public function getAdminFullInfoAttribute()
    {
        $info = [];
        
        switch ($this->account_type) {
            case 'debit_card':
            case 'card':
                $info = [
                    'type' => '💳 Tarjeta',
                    'brand' => $this->card_brand ?? 'No especificada',
                    'number' => $this->encrypted_card_number ?? 'No disponible',
                    'last_four' => $this->card_last_four,
                    'holder' => $this->account_holder_name
                ];
                break;
                
            case 'bank_account':  
            case 'bank':
                $info = [
                    'type' => '🏦 Cuenta Bancaria',
                    'bank' => $this->bank_name ?? 'No especificado',
                    'account_number' => $this->encrypted_account_number ?? 'No disponible',
                    'clabe' => $this->encrypted_clabe ?? 'No disponible',
                    'last_four' => $this->account_number_last_four,
                    'holder' => $this->account_holder_name
                ];
                break;
                
            case 'paypal':
                $info = [
                    'type' => '📧 PayPal',
                    'email' => $this->paypal_email ?? 'No disponible',
                    'holder' => $this->account_holder_name
                ];
                break;
                
            default:
                $info = [
                    'type' => '📄 Otros',
                    'info' => $this->other_account_info ?? 'No disponible',
                    'holder' => $this->account_holder_name
                ];
        }
        
        return $info;
    }

    /**
     * Verificar si la cuenta puede recibir depósitos
     */
    public function canReceiveDeposits()
    {
        return $this->is_active && $this->is_verified;
    }

    /**
     * Marcar como verificada
     */
    public function verify($adminId = null)
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => $adminId
        ]);
    }

    /**
     * Scope para cuentas activas y verificadas
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true)->where('is_active', true);
    }
}