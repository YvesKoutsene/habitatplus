<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeleAbonnement extends Model
{
    //
    use HasFactory;

    protected $fillable = ['nom', 'description', 'prix', 'duree'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id_modele_abonnement');
    }
}
