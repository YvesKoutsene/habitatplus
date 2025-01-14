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
        /*$user = Auth::user();
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Votre compte n\'est pas vérifié.',
            ]);
        }*/

        $request->authenticate();
        $request->session()->regenerate();
        session()->flash('success', 'Content de vous revoir!');

        return redirect()->intended(route('profile.edit', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        session()->flash('success', 'Au revoir à bientôt!');

        return redirect('/login');
    }
}
