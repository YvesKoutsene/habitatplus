<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValeurBien extends Model
{
    //
    use HasFactory;

    protected $fillable = ['valeur', 'id_bien', 'id_association_categorie'];

    public function bien()
    {
        return $this->belongsTo(Bien::class, 'id_bien');
    }

    public function associationCategorie()
    {
        return $this->belongsTo(AssociationCategorieParametre::class, 'id_association_categorie');
    }

}
