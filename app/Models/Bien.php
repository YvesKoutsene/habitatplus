<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bien extends Model
{
    //
    use HasFactory;

    protected $fillable = ['titre', 'description', 'prix', 'lieu', 'datePublication', 'statut', 'type_offre', 'id_user', 'id_categorie_bien'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function categorieBien()
    {
        return $this->belongsTo(CategorieBien::class, 'id_categorie_bien');
    }

    public function photos()
    {
        return $this->hasMany(PhotoBien::class, 'id_bien');
    }

    public function valeurs()
    {
        return $this->hasMany(ValeurBien::class, 'id_bien');
    }

}
