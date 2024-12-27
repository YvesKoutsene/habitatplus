<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssociationModeleParametre;
use App\Models\ParametreModele;
use App\Models\ModeleAbonnement;

class ModelSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

        $query = ModeleAbonnement::with(['parametres'])
            ->orderBy('created_at', 'asc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereHas('parametres', function ($qParam) use ($search) {
                    $qParam->whereRaw('LOWER(nom_parametre) LIKE ?', ['%' . strtolower($search) . '%']);
                });
            });
        }

        $modeles = $query->paginate($perPage);

        return view('admin.pages.model_subscription.index', compact('modeles', 'search', 'perPage'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parametres = ParametreModele::all();

        return view('admin.pages.model_subscription.create', compact('parametres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:modele_abonnements,nom',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'duree' => 'required|numeric|min:1',
            'parametres' => 'required|array',
            'parametres.*.id' => 'exists:parametre_modeles,id',
            'parametres.*.valeur' => 'required|numeric|min:0',
        ], [
            'nom.unique' => "Ce modèle d'abonnement existe déjà",
            'parametres.required' => 'Veuillez ajouter au moins un paramètre',
        ]);

        $parametreIds = collect($validated['parametres'])->pluck('id');
        if ($parametreIds->count() !== $parametreIds->unique()->count()) {
            return redirect()->back()->withErrors(['parametres' => 'Vous ne pouvez pas ajouter un paramètre deux fois.']);
        }

        $modele = ModeleAbonnement::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'duree' => $validated['duree'],
        ]);

        foreach ($validated['parametres'] as $parametre) {
            AssociationModeleParametre::create([
                'id_modele' => $modele->id,
                'id_parametre' => $parametre['id'],
                'valeur' => $parametre['valeur'],
            ]);
        }

        return redirect()->route('model_subscription.index')->with('success', "Modèle d'abonnement {$modele->nom} créé avec succès.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $modele = ModeleAbonnement::with('parametres')->findOrFail($id);
        $parametres = ParametreModele::all();

        return view('admin.pages.model_subscription.edit', compact('modele', 'parametres'));
    }

    /**
     * Update the specified resource in storage.
     */

    /*public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:modele_abonnements,nom,' . $id,
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'duree' => 'required|numeric|min:1',
            'parametres' => 'required|array',
            'parametres.*.id' => 'exists:parametre_modeles,id',
            'parametres.*.valeur' => 'required|numeric|min:0',
        ], [
            'nom.unique' => "Ce modèle d'abonnement existe déjà.",
            'parametres.required' => 'Veuillez ajouter au moins un paramètre.',
        ]);

        $parametreIds = collect($validated['parametres'])->pluck('id');
        if ($parametreIds->count() !== $parametreIds->unique()->count()) {
            return redirect()->back()->withErrors(['parametres' => 'Vous ne pouvez pas ajouter un paramètre deux fois.']);
        }

        $modele = ModeleAbonnement::findOrFail($id);

        $modele->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'duree' => $validated['duree'],
        ]);

        $existingAssociations = $modele->parametres->keyBy('id');
        foreach ($validated['parametres'] as $parametre) {
            $association = $existingAssociations->get($parametre['id']);

            if ($association) {
                $association->update(['valeur' => $parametre['valeur']]);
            } else {
                AssociationModeleParametre::create([
                    'id_modele' => $modele->id,
                    'id_parametre' => $parametre['id'],
                    'valeur' => $parametre['valeur'],
                ]);
            }
        }

        $validIds = collect($validated['parametres'])->pluck('id');
        $modele->parametres()->whereNotIn('id_parametre', $validIds)->delete();

        return redirect()->route('model_subscription.index')->with('success', "Modèle d'abonnement {$modele->nom} mis à jour avec succès.");
    }*/

    public function update(Request $request, $id)
    {
        // Récupérer le modèle d'abonnement à partir de l'ID
        $modele = ModeleAbonnement::findOrFail($id);
    
        // Validation des données du formulaire
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:modele_abonnements,nom,' . $modele->id,
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'duree' => 'required|numeric|min:1',
            'parametres' => 'required|array',
            'parametres.*.id' => 'exists:parametre_modeles,id',
            'parametres.*.valeur' => 'required|numeric|min:0',
        ], [
            'nom.unique' => "Ce modèle d'abonnement existe déjà",
            'parametres.required' => 'Veuillez ajouter au moins un paramètre',
        ]);
    
        // Vérification qu'il n'y a pas de doublon dans les paramètres
        $parametreIds = collect($validated['parametres'])->pluck('id');
        if ($parametreIds->count() !== $parametreIds->unique()->count()) {
            return redirect()->back()->withErrors(['parametres' => 'Vous ne pouvez pas ajouter un paramètre deux fois.']);
        }
    
        // Mise à jour des informations du modèle d'abonnement
        $modele->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'duree' => $validated['duree'],
        ]);
    
        // Récupérer les associations actuelles
        $currentAssociations = $modele->parametres()->get();
    
        // Créer une collection des nouveaux paramètres pour faciliter les comparaisons
        $newAssociations = collect($validated['parametres'])->keyBy('id');
    
        // Analyser les associations actuelles et les comparer avec les nouvelles
        foreach ($currentAssociations as $association) {
            // Si cette association a été supprimée par l'utilisateur (non présente dans les nouvelles données), on la supprime
            if (!$newAssociations->has($association->id_parametre)) {
                $association->delete();
            } else {
                // Si la valeur du paramètre a changé, on met à jour l'association
                $newValue = $newAssociations->get($association->id_parametre)['valeur'];
                if ($association->valeur !== $newValue) {
                    $association->update(['valeur' => $newValue]);
                }
    
                // Supprimer l'association traitée de la collection des nouvelles associations
                $newAssociations->forget($association->id_parametre);
            }
        }
    
        // Ajouter toutes les nouvelles associations qui n'existent pas encore
        foreach ($newAssociations as $parametre) {
            AssociationModeleParametre::create([
                'id_modele' => $modele->id,
                'id_parametre' => $parametre['id'],
                'valeur' => $parametre['valeur'],
            ]);
        }
    
        // Redirection avec un message de succès
        return redirect()->route('model_subscription.index')->with('success', "Modèle d'abonnement {$modele->nom} mis à jour avec succès.");
    }
    


    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        $modele = ModeleAbonnement::with('parametres')->findOrFail($id);
        if ($modele->transactions()->exists()) {
            return redirect()->route('model_subscription.index')
                ->with('error', "Impossible de supprimer le modèle d'abonnement {$modele->nom}.");
        }

        $modele->parametres()->detach(); 

        $modele->delete();

        return redirect()->route('model_subscription.index')->with('success', "Modèle d'abonnement {$modele->nom} supprimé avec succès.");
    }

}
