<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destinasi extends Model
{
    use HasFactory;

    protected $table = 'destinasi';
    
    // Enum values for status
    const STATUS_ORDERABLE = 'orderable';
    const STATUS_TRAVELING = 'traveling';
    const STATUS_ARRIVED = 'arrived';
    
    // Valid status values
    public static $statusValues = [
        self::STATUS_ORDERABLE,
        self::STATUS_TRAVELING,
        self::STATUS_ARRIVED
    ];
    
    protected $fillable = [
        'user_id',
        'kode_destinasi',
        'travel_name',
        'start_date',
        'check_point',
        'end_point',
        'vehicle_type',
        'plate_number',
        'number_of_seats',
        'price',
        'foto',
        'deskripsi',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }
}
