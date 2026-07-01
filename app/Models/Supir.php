<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supir extends Model
{
    use HasFactory;

    protected $table = 'supir'; 

    protected $fillable = [
        'user_id', 'no_ktp', 'no_sim', 'no_hp', 'alamat', 'foto', 'status'
    ];

   
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}