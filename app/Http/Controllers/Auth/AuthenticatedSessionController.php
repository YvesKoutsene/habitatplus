<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'email' => 'Email ou mot de passe incorrect.',
            ]);
        }

        //New by Jyl
        $user = Auth::user();
        if ($user->typeUser !== 2) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Compte abonné non trouvé.',
            ]);
        }

        $request->authenticate();
        $request->session()->regenerate();
        session()->flash('success', 'Connexion réussie!');

        //return redirect()->intended(route('profile.edit', absolute: false));
        return redirect('/');
    }

    /**
     * Destroy an authenticated session.
     */
    /*public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        session()->flash('success', 'Vous êtes deconnecté!');

        return redirect('/');
    }*/

    //New by Jean-Yves
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user && ($user->typeUser === 0 || $user->typeUser === 1)) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            session()->flash('success', 'Vous êtes déconnecté!');

            return redirect()->route('log-admin');
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->flash('success', 'Vous êtes déconnecté!');

        return redirect('/');
    }

}
