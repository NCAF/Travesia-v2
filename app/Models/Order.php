<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
