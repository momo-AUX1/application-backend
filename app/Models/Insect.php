<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Insect extends Model
{
    use HasFactory;
    protected $fillable = ['nom_commun', 'nom_scientifique', 'photo', 'famille', 'taille', 'poids', 'couleur'];
}