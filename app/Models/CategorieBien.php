<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieBien extends Model
{
    //
    use HasFactory;

    protected $fillable = ['titre', 'description', 'requis'];

    public function biens()
    {
        return $this->hasMany(Bien::class, 'id_categorie_bien');
    }

    public function parametres()
    {
        return $this->hasMany(AssociationCategorieParametre::class, 'id_categorie');
    }
}
