<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValeurAbonnement extends Model
{
    //
    use HasFactory;

    protected $fillable = ['valeur', 'statut', 'id_abonnement', 'id_association_modele'];

    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class, 'id_abonnement');
    }

    public function association()
    {
        return $this->belongsTo(AssociationModeleParametre::class, 'id_association_modele');
    }
}
