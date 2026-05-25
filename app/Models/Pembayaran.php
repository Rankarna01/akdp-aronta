<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'tiket_id',
        'metode_pembayaran',
        'jumlah_bayar',
        'bukti_transfer',
        'status',
        'tanggal_bayar',
    ];

    public function tiket()
    {
        return $this->belongsTo(Tiket::class, 'tiket_id');
    }
}