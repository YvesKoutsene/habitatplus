<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieBien extends Model
{
    //
    use HasFactory;

    protected $fillable = ['titre', 'statut', 'description'];

    public function biens()
    {
        return $this->hasMany(Bien::class, 'id_categorie_bien');
    }

    //New by Jean-Yves
    public function associations()
    {
        return $this->hasMany(AssociationCategorieParametre::class, 'id_categorie');
    }

    public function alertes()
    {
         return $this->hasMany(AlerteRecherche::class, 'id_categorie_bien');
    }

}
