<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    // Status constants for better code maintainability
    const STATUS_PENDING = 'pending';
    const STATUS_SETTLEMENT = 'settlement';
    const STATUS_CAPTURE = 'capture';
    const STATUS_DENY = 'deny';
    const STATUS_CANCEL = 'cancel';
    const STATUS_EXPIRE = 'expire';
    const STATUS_FAILURE = 'failure';

    // Valid status values
    public static $statusValues = [
        self::STATUS_PENDING,
        self::STATUS_SETTLEMENT,
        self::STATUS_CAPTURE,
        self::STATUS_DENY,
        self::STATUS_CANCEL,
        self::STATUS_EXPIRE,
        self::STATUS_FAILURE,
    ];

    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_method',
        'gross_amount',
        'status',
        'payment_code',
        'fraud_status',
        'transaction_time',
        'settlement_time',
        'midtrans_response',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
        'midtrans_response' => 'json',
    ];

    /**
     * Relationship to Order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Check if transaction is successful
     */
    public function isSuccessful(): bool
    {
        return in_array($this->status, [self::STATUS_SETTLEMENT, self::STATUS_CAPTURE]);
    }

    /**
     * Check if transaction is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if transaction failed
     */
    public function isFailed(): bool
    {
        return in_array($this->status, [self::STATUS_DENY, self::STATUS_CANCEL, self::STATUS_EXPIRE, self::STATUS_FAILURE]);
    }

    /**
     * Scope for successful transactions
     */
    public function scopeSuccessful($query)
    {
        return $query->whereIn('status', [self::STATUS_SETTLEMENT, self::STATUS_CAPTURE]);
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope for failed transactions
     */
    public function scopeFailed($query)
    {
        return $query->whereIn('status', [self::STATUS_DENY, self::STATUS_CANCEL, self::STATUS_EXPIRE, self::STATUS_FAILURE]);
    }
}
