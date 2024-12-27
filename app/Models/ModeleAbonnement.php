<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeleAbonnement extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description', 'prix', 'duree'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_modele_abonnement');
    }

    public function parametres()
    {
        return $this->belongsToMany(ParametreModele::class, 'association_modele_parametres', 'id_modele', 'id_parametre')
                    ->withPivot('valeur');
    }

}
