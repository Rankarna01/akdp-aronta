<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MetodePembayaranMaster extends Model
{
    use HasFactory;

    protected $table = 'metode_pembayaran_masters';

    protected $fillable = [
        'nama_bank',
        'nomor_rekening',
        'atas_nama',
        'status',
    ];
}
