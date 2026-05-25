<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;

    protected $table = 'tiket';

    protected $fillable = [
        'kode_tiket',
        'jadwal_id',
        'penumpang_id',
        'kursi_id',
        'catatan_titik',
        'harga',
        'status_pembayaran',
        'status_tiket',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function penumpang()
    {
        return $this->belongsTo(Penumpang::class, 'penumpang_id');
    }

    public function kursi()
    {
        return $this->belongsTo(Kursi::class, 'kursi_id');
    }
}