<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Spatie\Permission\Models\Role;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get(); 
        return view('admin.pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all(); // Récupérer tous les rôles
        return view('admin.pages.users.create', compact('roles'));
    }    

    /**
     * Store a newly created resource in storage.
     */
    // Fonction d'enregistrement d'utilisateur
    public function store(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'pays' => 'required|string',
            'numero' => 'required|string|max:15',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'role' => 'required|exists:roles,id',
        ]);

        if ($validateUser->fails()) {
            return redirect()->back()
                ->withErrors($validateUser)
                ->withInput();
        }

        $profilePath = null;

        // Gestion de l'upload de l'image
        if ($request->hasFile('photo_profil')) {
            $profile = $request->file('photo_profil');
            $profileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $profile->getClientOriginalName());
            $profilePath = $profile->storeAs('images/profils', $profileName, 'public');
        }

        // Création de l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'pays' => $request->pays,
            'numero' => $request->numero,
            'photo_profil' => $profilePath ? Storage::url($profilePath) : '/storage/images/profils/default_profile.jpg',
            'statut' => 'actif',
        ]);

        // Attribution du rôle
        $role = Role::find($request->role);
        if ($role) {
            $user->assignRole($role);
        }

        return redirect()->route('users.index')
            ->with('success', "Utilisateur {$user->name} ajouté avec succès.");
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.pages.users.edit', compact('user', 'roles'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $role = Role::find($request->role);
        $user->syncRoles([$role]);

        return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour avec succès.');
    }


    public function suspend(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas suspendre votre propre compte.');
        }

        if ($user->statut !== 'actif') {
            return redirect()->route('users.index')->with('error', 'Seuls les comptes actifs peuvent être suspendus.');
        }

        $user->update(['statut' => 'suspendu']);
        return redirect()->route('users.index')->with('success', "Compte utilisateur {$user->name} suspendu.");
    }

    public function reactivate(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas réactiver votre propre compte.');
        }

        if ($user->statut !== 'suspendu') {
            return redirect()->route('users.index')->with('error', 'Seuls les comptes suspendus peuvent être réactivés.');
        }

        $user->update(['statut' => 'actif']);
        return redirect()->route('users.index')->with('success', "Compte utilisateur {$user->name} réactivé.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
    
        $user->delete();
        return redirect()->route('users.index')->with('success', "Utilisateur {$user->name} supprimé avec succès.");
    }
    
}
