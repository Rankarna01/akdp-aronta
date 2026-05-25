<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Armada extends Model
{
    use HasFactory;

    // Paksa Laravel menggunakan nama tabel ini (tanpa s)
    protected $table = 'armada';

    protected $fillable = [
        'nama_bus',
        'plat_nomor',
        'nomor_pintu',
        'tipe_bus',
        'total_kursi',
        'status',
        'gambar',
    ];
}