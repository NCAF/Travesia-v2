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
    protected $fillable = [
        'user_id',
        'kode_destinasi',
        'destinasi_awal',
        'destinasi_akhir',
        'jenis_kendaraan',
        'no_plat',
        'hari_berangkat',
        'jumlah_kursi',
        'jumlah_bagasi',
        'foto',
        'deskripsi',
        'harga_kursi',
        'harga_bagasi',
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
