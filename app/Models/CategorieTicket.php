<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategorieTicket extends Model
{
    //
    use HasFactory;

    protected $fillable = ['nom_categorie', 'description','statut'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'id_categorie');
    }
}
