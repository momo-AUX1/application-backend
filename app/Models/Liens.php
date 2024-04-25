<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liens extends Model
{
    use HasFactory;

    protected $table = 'liens';

   
    protected $fillable = [
        'user',       
        'insect',     
        'view_history', 
        'lat_long'     
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }

    public function insect()
    {
        return $this->belongsTo(Insect::class, 'insect', 'id');
    }
}
