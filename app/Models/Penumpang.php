<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penumpang extends Model
{
    use HasFactory;

    protected $table = 'penumpang'; // Tanpa akhiran s

    protected $fillable = [
        'nik',
        'nama',
        'jenis_kelamin',
        'no_hp',
        'alamat',
    ];
}