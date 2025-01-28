<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('acceuil', absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        //return back()->with('status', 'verification-link-sent');
        return back()->with('success', 'Un nouveau lien de vérification a été envoyé sur cet e-mail.');
    }
}
