<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kursi extends Model
{
    use HasFactory;

    protected $table = 'kursi'; // Paksa nama tabel tanpa s

    protected $fillable = [
        'armada_id',
        'nomor_kursi',
        'status',
    ];

    // Relasi: 1 Kursi merujuk ke 1 Armada Bus
    public function armada()
    {
        return $this->belongsTo(Armada::class, 'armada_id');
    }
}