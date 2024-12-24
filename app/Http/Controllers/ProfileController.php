<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        /*return view('admin.pages.users.profile', [
            'user' => $request->user(),
        ]);*/

        //By jean-yves
        return view('admin.pages.users.profile', [
            'user' => $request->user(),
        ]);
    }


    public function updatePassword(Request $request, $id)
    {
        // Validation des champs
        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ],
        ['password.confirmed' => 'Les mots de passe ne correspondent pas.',
        'password.min' => 'Mot de passe faible (8 caractères minimuns)'
        ]
        );

        // Récupération de l'utilisateur
        $user = User::findOrFail($id);

        // Vérification de l'ancien mot de passe
        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Le mot de passe actuel est incorrect.',
            ]);
        }

        // Mise à jour du mot de passe
        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', 'Mot de passe mis à jour avec succès.');
    }


    /**
     * Update the user's profile information.
     */
    /*public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }*/

    public function update(Request $request, $id)
    {
       
        $validateUser = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|confirmed|min:8',
            'pays' => 'required|string',
            'numero' => 'required|string|max:15',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if ($validateUser->fails()) {
            return redirect()->back()
                ->withErrors($validateUser)
                ->withInput();
        }

        // Récupérer l'utilisateur
        $user = User::findOrFail($id);
        
        // Conserver l'ancienne photo de profil par défaut
        $profilePath = $user->photo_profil;

        // Gestion de l'upload de l'image
        if ($request->hasFile('photo_profil')) {
            $profile = $request->file('photo_profil');
            $profileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $profile->getClientOriginalName());
            $profilePath = $profile->storeAs('images/profils', $profileName, 'public');
            // Mettre à jour le chemin de la photo de profil
            $profilePath = Storage::url($profilePath);
        }

        // Mise à jour des informations de l'utilisateur
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'pays' => $request->pays,
            'numero' => $request->numero,
            'photo_profil' => $profilePath, // Utiliser le chemin mis à jour ou l'ancien
            'statut' => 'actif', // ou laissez tel quel si ce n'est pas modifiable
        ]);


        return redirect()->route('profile.edit')
            ->with('success', "Profil mis à jour avec succès.");
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
