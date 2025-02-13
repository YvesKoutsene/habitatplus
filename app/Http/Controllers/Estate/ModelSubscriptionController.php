<?php

namespace App\Http\Controllers\Estate;

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
                ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%'])
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

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:modele_abonnements,nom,' . $id,
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'duree' => 'required|string|max:50',
            'parametres' => 'required|array',
            'parametres.*.id' => 'exists:parametre_modeles,id',
            'parametres.*.valeur' => 'required|numeric|min:0',
        ], [
            'nom.unique' => 'Ce modèle d\'abonnement existe déjà.',
            'parametres.required' => 'Veuillez ajouter au moins un paramètre.',
        ]);

        $modele = ModeleAbonnement::findOrFail($id);

        $existingAssociations = AssociationModeleParametre::where('id_modele', $modele->id)->get()->keyBy('id_parametre');

        $submittedParamIds = collect($validated['parametres'])->pluck('id');

        $associationsToDelete = $existingAssociations->keys()->diff($submittedParamIds);
        AssociationModeleParametre::where('id_modele', $modele->id)
            ->whereIn('id_parametre', $associationsToDelete)
            ->delete();

        foreach ($validated['parametres'] as $parametre) {
            $association = $existingAssociations->get($parametre['id']);

            if ($association) {
                $association->update([
                    'valeur' => $parametre['valeur'],
                ]);
            } else {
                AssociationModeleParametre::create([
                    'id_modele' => $modele->id,
                    'id_parametre' => $parametre['id'],
                    'valeur' => $parametre['valeur'],
                ]);
            }
        }

        $modele->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'duree' => $validated['duree'],
        ]);

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
