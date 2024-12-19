<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Permission;

use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->orderBy('created_at', 'asc')->get();
        $permissions = Permission::all();
        return view('admin.pages.roles.index', compact('roles', 'permissions'));

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
             // Création du rôle
             $role = Role::create(['name' => $request->name]);
     
             // Récupération des permissions sélectionnées
             $permissions = Permission::whereIn('id', $request->permissions)->get();
     
             // Association des permissions au rôle
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

        // Vérification que toutes les permissions existent
        if ($request->permissions) {
            $validPermissions = Permission::whereIn('id', $request->permissions)->pluck('id')->toArray();
            
            // Si le nombre de permissions valides ne correspond pas à celui fourni
            if (count($validPermissions) !== count($request->permissions)) {
                return redirect()->back()->withErrors(['permissions' => 'Certaines permissions fournies sont invalides.']);
            }
        }

        $role->update(['name' => $request->name]);
        $role->syncPermissions($validPermissions);  // Utilisez les permissions valides

        return redirect()->route('roles.index')->with('success', "Rôle {$role->name} mis à jour avec succès.");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->name === 'Administrateur' || $role->name === 'Abonné') {
            return redirect()->route('roles.index')->with('error', 'Vous ne pouvez pas supprimer ce rôle.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', "Rôle {$role->name} supprimé avec succès.");
    }

}
