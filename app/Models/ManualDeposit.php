<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'order_id',
        'payment_account_id',
        'processed_by',
        'amount',
        'currency',
        'reference',
        'description',
        'status',
        'requested_at',
        'processed_at',
        'completed_at',
        'deposit_method',
        'external_transaction_id',
        'admin_notes',
        'failure_reason'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Generar referencia única
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($deposit) {
            if (!$deposit->reference) {
                $deposit->reference = 'DEP-' . strtoupper(uniqid()) . '-' . $deposit->seller_id;
            }
            if (!$deposit->requested_at) {
                $deposit->requested_at = now();
            }
        });
    }

    /**
     * Relación con vendedor
     */
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    /**
     * Relación con orden (opcional)
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Cuenta de pago destino
     */
    public function paymentAccount()
    {
        return $this->belongsTo(SellerPaymentAccount::class, 'payment_account_id');
    }

    /**
     * Admin que procesó
     */
    public function processedBy()
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }

    /**
     * Marcar como en proceso
     */
    public function markAsProcessing($adminId)
    {
        $this->update([
            'status' => 'processing',
            'processed_at' => now(),
            'processed_by' => $adminId
        ]);
    }

    /**
     * Marcar como completado
     */
    public function markAsCompleted($transactionId = null, $method = null, $notes = null)
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'external_transaction_id' => $transactionId,
            'deposit_method' => $method ?? $this->deposit_method,
            'admin_notes' => $notes
        ]);
    }

    /**
     * Marcar como fallido
     */
    public function markAsFailed($reason, $notes = null)
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
            'admin_notes' => $notes
        ]);
    }

    /**
     * Scope para depósitos pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para depósitos del vendedor
     */
    public function scopeForSeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    /**
     * Status badge para mostrar
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge badge-warning">⏳ Pendiente</span>',
            'processing' => '<span class="badge badge-info">🔄 Procesando</span>',
            'completed' => '<span class="badge badge-success">✅ Completado</span>',
            'failed' => '<span class="badge badge-danger">❌ Fallido</span>',
            'cancelled' => '<span class="badge badge-secondary">🚫 Cancelado</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge badge-light">❓ Desconocido</span>';
    }

    /**
     * Formatear monto
     */
    public function getFormattedAmountAttribute()
    {
        return '$' . number_format($this->amount, 2) . ' ' . $this->currency;
    }
}