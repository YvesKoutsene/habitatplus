<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

            'pays' => 'string|required',
            'numero' => 'string|max:15',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ],
        [
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'email.unique' => 'Cette adresse email existe déjà'
        ]
        );

        $profilePath = null;

        if ($request->hasFile('photo_profil')) {
            $profile = $request->file('photo_profil');
            $profileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $profile->getClientOriginalName());
            $profilePath = $profile->storeAs('images/profils', $profileName, 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'numero' => $request->numero,
            'pays' => $request->pays,
            'photo_profil' => $profilePath ? Storage::url($profilePath) : '/storage/images/profils/default_profile.jpg',
            'typeUser' => '2',
            'statut' => 'actif',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
        //return redirect()->route('login')->with('success', "Compte créé avec succès, connectez-vous");
        //return redirect(route('dashboard', absolute: false))->with('success', "Nous vous avons envoyé un e-mail de confirmation à cette adresse.");
    }
}
