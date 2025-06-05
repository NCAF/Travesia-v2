<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'destinasi_id',
        'jumlah_kursi',
        'harga_kursi',
        'status',
        'kode_destinasi',
        'order_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function destinasi(): BelongsTo
    {
        return $this->belongsTo(Destinasi::class);
    }

    /**
     * Relationship to Transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the latest transaction for this order
     */
    public function latestTransaction()
    {
        return $this->hasOne(Transaction::class)->latest();
    }

    /**
     * Get successful transactions only
     */
    public function successfulTransactions()
    {
        return $this->hasMany(Transaction::class)->successful();
    }
}
