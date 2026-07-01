<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kursi extends Model
{
    use HasFactory;

    protected $table = 'kursi'; 

    protected $fillable = [
        'armada_id',
        'nomor_kursi',
        'status',
    ];

    public function armada()
    {
        return $this->belongsTo(Armada::class, 'armada_id');
    }
}