<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringPerjalanan extends Model
{
    use HasFactory;

    protected $table = 'monitoring_perjalanan'; // Paksa nama tabel

    protected $fillable = [
        'jadwal_id',
        'lokasi_sekarang',
        'status',
        'keterangan',
    ];

    // Relasi ke Jadwal
    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }
}