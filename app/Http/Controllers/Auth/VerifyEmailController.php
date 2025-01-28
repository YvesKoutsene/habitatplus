<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */

    /*public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            session()->flash('success', 'Compte vérifié avec succès!');
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }
        session()->flash('success', 'Compte vérifié avec succès!');
        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }*/

    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('verification.success')->with('success', 'Votre compte est déjà vérifié.');
        }
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        auth()->logout();
        return redirect()->route('verification.success')->with('success', 'Votre compte a été vérifié avec succès!');
    }

}
