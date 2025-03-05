<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Boost extends Model
{
    use HasFactory;
    protected $table = 'boosts';

    protected $fillable = [
        'id_bien',
        'type_boost',
        'duree',
        'unite_duree',
        'date_debut',
        'date_fin',
        'statut',
    ];

    public function bien() {
        return $this->belongsTo(Bien::class, 'id_bien');
    }

    // Fonction pour calculer automatiquement la date de fin
    public function setDateFinAttribute() {
        $dateDebut = Carbon::parse($this->date_debut);

        switch ($this->unite_duree) {
            case 'jour':
                $this->attributes['date_fin'] = $dateDebut->addDays($this->duree);
                break;
            case 'semaine':
                $this->attributes['date_fin'] = $dateDebut->addWeeks($this->duree);
                break;
            case 'mois':
                $this->attributes['date_fin'] = $dateDebut->addMonths($this->duree);
                break;
            case 'annee':
                $this->attributes['date_fin'] = $dateDebut->addYears($this->duree);
                break;
        }
    }

}
