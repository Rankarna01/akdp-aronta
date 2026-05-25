<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penumpang extends Model
{
    use HasFactory;

    protected $table = 'penumpang'; // Tanpa akhiran s

    protected $fillable = [
        'user_id',
        'nik',
        'nama',
        'jenis_kelamin',
        'no_hp',
        'alamat',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}