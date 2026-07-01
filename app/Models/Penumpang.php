<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penumpang extends Model
{
    use HasFactory;

    protected $table = 'penumpang'; 

    protected $fillable = [
        'user_id',
        'nik',
        'nama',
        'jenis_kelamin',
        'no_hp',
        'alamat',
    ];

   
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}