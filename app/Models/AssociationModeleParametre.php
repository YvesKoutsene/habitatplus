<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssociationModeleParametre extends Model
{
    use HasFactory;

    protected $fillable = ['id_modele', 'id_parametre', 'valeur'];

    public function modele()
    {
        return $this->belongsTo(ModeleAbonnement::class, 'id_modele');
    }

    public function parametre()
    {
        return $this->belongsTo(ParametreModele::class, 'id_parametre');
    }

    public function modeleAbonnement()
    {
        return $this->belongsTo(ModeleAbonnement::class, 'id_modele');
    }

}
