<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlerteRecherche extends Model
{
    //
    use HasFactory;

    protected $fillable = ['lieu', 'budget', 'statut', 'type_offre', 'id_user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function categorieBien()
    {
        return $this->belongsTo(CategorieBien::class, 'id_categorie_bien');
    }
}
