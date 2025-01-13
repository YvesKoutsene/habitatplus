<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Spatie\Permission\Models\Role;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

        //$query = User::with('roles')->orderBy('created_at', 'asc');
        $query = User::with('roles')
            ->where('typeUser', '!=', 0)
            ->orderBy('created_at', 'asc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(pays) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(numero) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        $users = $query->paginate($perPage);

        return view('admin.pages.users.index', compact('users', 'search', 'perPage'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('statut', '=', 'actif')->get();
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
        ],
        [
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        if ($validateUser->fails()) {
            return redirect()->back()
                ->withErrors($validateUser)
                ->withInput();
        }

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
            'pays' => $request->pays,
            'numero' => $request->numero,
            'typeUser' => '1',
            'photo_profil' => $profilePath ? Storage::url($profilePath) : '/storage/images/profils/default_profile.jpg',
            'statut' => 'actif',
        ]);

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
        if ($user->roles->pluck('name')->contains('Abonné')) {
            return redirect()->route('users.index')
                ->with('error', 'Cet utilisateur ne peut pas être modifié.');
        }

        $roles = Role::where('statut', '=', 'actif')->get();
        return view('admin.pages.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->roles->pluck('name')->contains('Abonné')) {
            return redirect()->route('users.index')
                ->with('error', 'Cet utilisateur ne peut pas être modifié.');
        }

        if (auth()->id() === $user->id && $request->has('role')) {
            return redirect()->back()
                ->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        $validateUser = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => 'nullable|string|confirmed|min:8',
            'pays' => 'required|string',
            'numero' => 'required|string|max:15',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'role' => 'required|exists:roles,id',
        ],[
            'current_password.current_password' => 'Mot de passe actuel incorrect.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        if ($validateUser->fails()) {
            return redirect()->back()
                ->withErrors($validateUser)
                ->withInput();
        }

        $profilePath = $user->photo_profil;

        if ($request->hasFile('photo_profil')) {
            $profile = $request->file('photo_profil');
            $profileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $profile->getClientOriginalName());
            $profilePath = $profile->storeAs('images/profils', $profileName, 'public');
            $profilePath = Storage::url($profilePath);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'pays' => $request->pays,
            'numero' => $request->numero,
            'typeUser' => '1',
            'photo_profil' => $profilePath,
            'statut' => 'actif',
        ]);

        $role = Role::find($request->role);
        if ($role) {
            $user->syncRoles([$role->id]);
        }

        return redirect()->route('users.index')
            ->with('success', "Utilisateur {$user->name} mis à jour avec succès.");
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

        if ($user->roles->pluck('name')->contains('Abonné')) {
            return redirect()->route('users.index')
                ->with('error', 'Cet utilisateur ne peut pas être supprimé.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', "Utilisateur {$user->name} supprimé avec succès.");
    }

}