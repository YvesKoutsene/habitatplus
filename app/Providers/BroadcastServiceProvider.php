<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Broadcast::routes();
        require base_path('routes/channels.php');

        //New by Jean-Yves
        Broadcast::channel('chat.{ticketId}', function ($user, $ticketId) {
            return true;
        });
    }

}
