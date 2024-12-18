<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    use HasFactory;

    protected $fillable = ['montant', 'statut', 'user_id', 'modele_abonnement_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function modeleAbonnement()
    {
        return $this->belongsTo(ModeleAbonnement::class);
    }

    public function abonnements()
    {
        return $this->hasMany(Abonnement::class, 'transaction_id');
    }

}
