<?php

namespace App\Http\Controllers\Estate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Permission;

use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10); // Valeur par défaut à 10

        $query = Role::with('permissions');

        if ($search) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
        }

        $roles = $query->orderBy('created_at', 'asc')->paginate($perPage);
        $permissions = Permission::all();

        return view('admin.pages.roles.index', compact('roles', 'search', 'perPage', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $permissions = Permission::with('children')->whereNull('parent_id')->get();
        return view('admin.pages.roles.create', compact('permissions'));

    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(Request $request)
     {
         $request->validate([
             'name' => 'required|unique:roles,name',
             'permissions' => 'required|array',
             'permissions.*' => 'exists:permissions,id',
         ], [
             'permissions.required' => 'Veuillez sélectionner au moins une permission.',
             'permissions.*.exists' => 'Une des permissions sélectionnées est invalide.',
             'name.unique' => 'Ce rôle existe déjà.',
         ]);

         try {
             $role = Role::create(['name' => $request->name,
                 'statut' => 'actif'
             ]);

             $permissions = Permission::whereIn('id', $request->permissions)->get();

             $role->syncPermissions($permissions);

             return redirect()->route('roles.index')->with('success', "Rôle {$role->name} créé avec succès.");
         } catch (\Exception $e) {
             return back()->withErrors(['error' => "Une erreur est survenue : " . $e->getMessage()]);
         }
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
    public function edit(Role $role)
    {

        if ($role->name === 'Administrateur' || $role->name === 'Abonné') {
            return redirect()->route('roles.index')->with('error', 'Vous ne pouvez pas modifier ce rôle.');
        }

        $permissions = Permission::with('children')->whereNull('parent_id')->get();
        return view('admin.pages.roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request, Role $role)
    {
        if ($role->name === 'Administrateur' || $role->name === 'Abonné') {
            return redirect()->route('roles.index')->with('error', 'Vous ne pouvez pas modifier ce rôle.');
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
        ],
        [
            'permissions.required' => 'Veuillez sélectionner au moins une permission.',
            'name.unique' => 'Ce rôle existe déjà.',
        ]);

        if ($request->permissions) {
            $validPermissions = Permission::whereIn('id', $request->permissions)->pluck('id')->toArray();

            if (count($validPermissions) !== count($request->permissions)) {
                return redirect()->back()->withErrors(['permissions' => 'Certaines permissions fournies sont invalides.']);
            }
        }

        $role->update(['name' => $request->name]);
        $role->syncPermissions($validPermissions);

        return redirect()->route('roles.index')->with('success', "Rôle {$role->name} mis à jour avec succès.");
    }


    /**
     * Remove the specified resource from storage.
     */

    public function suspend(Role $role)
    {
        if ($role->statut !== 'actif') {
            return redirect()->route('roles.index')->with('error', 'Seuls les roles actifs peuvent être désactivés.');
        }

        $role->update(['statut' => 'inactif']);
        return redirect()->route('roles.index')->with('success', "Role utilisateur {$role->name} désactivé.");
    }

    public function reactivate(Role $role)
    {
        if ($role->statut !== 'inactif') {
            return redirect()->route('role.index')->with('error', 'Seuls les roles inactifs peuvent être réactivés.');
        }

        $role->update(['statut' => 'actif']);
        return redirect()->route('roles.index')->with('success', "Role utilisateur {$role->name} réactivé.");
    }

    public function destroy(Role $role)
    {
        if ($role->name === 'Administrateur' || $role->name === 'Abonné') {
            return redirect()->route('roles.index')->with('error', 'Vous ne pouvez pas supprimer ce rôle.');
        }

        if ($role && $role->users()->exists()) {
            return redirect()->route('roles.index')
                ->with('error', "Le role {$role->name} ne peut pas être supprimé.");
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', "Rôle {$role->name} supprimé avec succès.");
    }

}
