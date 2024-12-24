<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametreModele extends Model
{
    //
    use HasFactory;

    protected $fillable = ['nom_parametre'];

    public function associations()
    {
        return $this->hasMany(AssociationModeleParametre::class, 'id_parametre');
    }

    //New by Jean-Yves 24/12/2024 à 17:45
    public function valeurs()
    {
        return $this->hasMany(ValeurParametreModele::class, 'id_association_modele', 'id');
    }

}
