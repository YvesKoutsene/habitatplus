<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Boost;
use Carbon\Carbon;

class ExpireBoosts extends Command
{
    /**
     * Nom de la commande Artisan.
     *
     * @var string
     */
    protected $signature = 'boosts:expire';

    /**
     * Description de la commande.
     *
     * @var string
     */
    protected $description = 'Met à jour le statut des boosts expirés';

    /**
     * Exécute la commande.
     */
    public function handle()
    {
        $expiredBoosts = Boost::where('statut', 'actif')
            ->where('date_fin', '<', Carbon::now())
            ->update(['statut' => 'expiré']); 

        $this->info("Boosts expirés mis à jour avec succès !");
    }
}
