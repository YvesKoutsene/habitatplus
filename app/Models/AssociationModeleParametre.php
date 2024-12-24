<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssociationModeleParametre extends Model
{
    //
    use HasFactory;

    protected $fillable = ['id_modele', 'id_parametre'];

    public function modele()
    {
        return $this->belongsTo(ModeleAbonnement::class, 'id_modele');
    }

    public function parametre()
    {
        return $this->belongsTo(ParametreModele::class, 'id_parametre');
    }

    //New by Jean-Yves 24/12/2024 à 17:45
    public function valeurs()
    {
        return $this->hasOne(ValeurParametreModele::class, 'id_association_modele');
    }

}
