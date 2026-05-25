<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

    protected $table = 'rute'; // Pastikan tanpa 's'

    protected $fillable = [
        'kota_asal',
        'kota_tujuan',
        'jarak_km',
        'estimasi_waktu',
        'harga_dasar',
        'status',
    ];
}