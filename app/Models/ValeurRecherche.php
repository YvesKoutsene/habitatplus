<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValeurRecherche extends Model
{
    //
    use HasFactory;

    protected $fillable = ['valeur', 'id_alerte', 'id_association_categorie'];

    public function alerte()
    {
        return $this->belongsTo(AlerteRecherche::class, 'id_alerte');
    }

    public function associationCategorie()
    {
        return $this->belongsTo(AssociationCategorieParametre::class, 'id_association_categorie');
    }
}
