<?php

namespace App\Http\Controllers\Estate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Spatie\Permission\Models\Role;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Mail;
use App\Mail\CompteSuspenduMail;
use App\Mail\CompteReactiveMail;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

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
            //'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password' => ['required', 'confirmed', Password::defaults()], // Utilisation de Password
            'pays' => 'required|string',
            'numero' => 'required|string|max:15',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'role' => 'required|exists:roles,id',
        ],
            [
                'password.confirmed' => 'Les mots de passe ne correspondent pas.',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.'
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
            'email_verified_at' => Carbon::now(),
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
        if ($user->typeUser == 2) {
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

        if ($user->typeUser == 2) {
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
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'pays' => 'required|string',
            'numero' => 'required|string|max:15',
            'photo_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'role' => 'required|exists:roles,id',
        ],[
            'current_password.current_password' => 'Mot de passe actuel incorrect.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.'
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

    //Fonction permettant de suspendre un compte utilisateur
    public function suspend(Request $request, User $user)
    {
        $request->validate([
            'motif' => 'required|string|max:255',
        ],[
            'motif.required' => "Le motif de bloquage de compte est requis"
        ]);

        //dd($request->all());

        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas suspendre votre propre compte.');
        }

        if ($user->statut !== 'actif') {
            return redirect()->back()->with('error', 'Seuls les comptes actifs peuvent être suspendus.');
        }

        $user->statut = 'suspendu';
        $user->motif_blocage = $request->motif;
        Mail::to($user->email)->send(new CompteSuspenduMail($user));
        $user->save();

        return redirect()->back()->with('success', "Compte suspendu avec succès et notifié à l'utilisateur : {$user->name}");

    }

    //Fonction permettant de réactiver un compte
    public function reactivate(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas réactiver votre propre compte.');
        }

        if ($user->statut !== 'suspendu') {
            return redirect()->back()->with('error', 'Seuls les comptes suspendus peuvent être réactivés.');
        }

        $user->statut = 'actif';
        $user->motif_blocage = null;
        Mail::to($user->email)->send(new CompteReactiveMail($user));
        $user->save();

        return redirect()->back()->with('success', "Compte réactivé avec succès et notifié à l'utilisateur : {$user->name}");
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

    //Fonction pour renvoyer la vue d'auth d'admin et super admin
    public function loginpage(): View
    {
        return view('admin.pages.users.login');
    }

    //Fonction d'authentification d'un admin et super admin
    public function authAdmin(LoginRequest $request): RedirectResponse
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'email' => 'Email ou mot de passe incorrect.',
            ]);
        }

        $user = Auth::user();

        if ($user->typeUser !== 0 && $user->typeUser !== 1) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Compte admin non trouvé.',
            ]);
        }

        $request->authenticate();
        $request->session()->regenerate();
        session()->flash('success', 'Connexion réussie!');

        return redirect()->route('profile.edit');
    }

}
