<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';

    protected $fillable = [
        'rute_id',
        'armada_id',
        'supir_id',
        'tanggal',
        'waktu_berangkat',
        'waktu_tiba',
        'harga_tiket',
        'status',
    ];

    public function rute()
    {
        return $this->belongsTo(Rute::class, 'rute_id');
    }

    public function armada()
    {
        return $this->belongsTo(Armada::class, 'armada_id');
    }

    public function supir()
    {
        return $this->belongsTo(Supir::class, 'supir_id');
    }
    public function tiket()
    {
        return $this->hasMany(Tiket::class, 'jadwal_id');
    }
}