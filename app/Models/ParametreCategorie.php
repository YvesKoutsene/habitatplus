<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametreCategorie extends Model
{
    //
    use HasFactory;

    protected $fillable = ['nom_parametre'];

    public function associations()
    {
        return $this->hasMany(AssociationCategorieParametre::class, 'id_parametre');
    }
}
