<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

//New by Jean-Yves
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */

    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            $expires = now()->addMinutes(config('auth.verification.expire', 60))->timestamp;

            return (new MailMessage)
                ->subject('Vérifiez votre adresse e-mail - Habitat+')
                ->markdown('emails.verify-email', [
                    'url' => $url,
                    'expires' => $expires,
                    'user' => $notifiable,
                ]);

        });
    }

}
