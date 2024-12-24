<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeleAbonnement extends Model
{
    //
    use HasFactory;

    protected $fillable = ['nom', 'description', 'prix', 'duree'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_modele_abonnement');
    }

    //New by Jean-Yves 24/12/2024 à 17:45
    public function parametres()
    {
        return $this->belongsToMany(ParametreModele::class, 'association_modele_parametres', 'id_modele', 'id_parametre')
                    ->withPivot('id')
                    ->with('valeurs');
    }

}
