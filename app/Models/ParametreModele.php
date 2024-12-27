<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametreModele extends Model
{
    use HasFactory;

    protected $fillable = ['nom_parametre'];

    public function associations()
    {
        return $this->hasMany(AssociationModeleParametre::class, 'id_parametre');
    }

    public function valeurs()
    {
        return $this->hasMany(AssociationModeleParametre::class, 'id_modele');
    }
}
