<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValeurParametreModele extends Model
{
    //
    use HasFactory;

    protected $fillable = ['valeur', 'id_association_modele'];

    public function associationModele()
    {
        return $this->belongsTo(AssociationModeleParametre::class, 'id_association_modele');
    }
}
