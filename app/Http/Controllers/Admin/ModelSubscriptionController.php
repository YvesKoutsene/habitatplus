<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AssociationModeleParametre;
use App\Models\ParametreModele;
use App\Models\ModeleAbonnement;
use App\Models\ValeurParametreModele;


class ModelSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 
    /*public function index()
    {
        $modeles = ModeleAbonnement::with([
            'parametres' => function($query) {
                $query->with('valeurs');
            }
        ])->paginate(10);

        return view('admin.pages.model_subscription.index', compact('modeles'));
    }*/

    public function index()
    {
        // Charger les modèles avec leurs paramètres et leurs valeurs associées
        $modeles = ModeleAbonnement::with(['parametres' => function ($query) {
            $query->with(['valeurs' => function ($query) {
                $query->select('valeur', 'id_association_modele');
            }]);
        }])->paginate(10);
    
        return view('admin.pages.model_subscription.index', compact('modeles'));
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
            'duree' => 'required|string|max:50',
            'parametres' => 'required|array',
            'parametres.*.id' => 'exists:parametre_modeles,id', 
            'parametres.*.valeur' => 'required|numeric|min:0', 
        ],[
            'nom.unique' => 'Ce modèle d\'abonnment existe déjà',
            'parametres.required' => 'Veuillez ajouter au moins un paramètre'
        ]);

        // Vérification des doublons dans les paramètres
        $parametreIds = collect($validated['parametres'])->pluck('id');
        if ($parametreIds->count() !== $parametreIds->unique()->count()) {
            return redirect()->back()->withErrors(['parametres' => 'Vous ne pouvez pas ajouter un paramètre deux fois']);
        }

        // Créer le modèle d'abonnement
        $modele = ModeleAbonnement::create([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'duree' => $validated['duree'],
        ]);

        foreach ($validated['parametres'] as $parametre) {
            $association = AssociationModeleParametre::create([
                'id_modele' => $modele->id,
                'id_parametre' => $parametre['id'],
            ]);

            ValeurParametreModele::create([
                'valeur' => $parametre['valeur'],
                'id_association_modele' => $association->id,
            ]);
        }

        return redirect()->route('model_subscription.index')->with('success', "Modèle d'abonnement {$modele->nom} créé avec succès.");
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
    public function edit($id)
    {
        $modele = ModeleAbonnement::with(['parametres.valeurs'])->findOrFail($id);

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
            'duree' => 'required|string|max:50',
            'parametres' => 'required|array',
            'parametres.*.id' => 'exists:parametre_modeles,id',
            'parametres.*.valeur' => 'required|numeric|min:0',
        ],[
            'nom.unique' => 'Ce modèle d\'abonnment existe déjà',
            'parametres.required' => 'Veuillez ajouter au moins un paramètre'
        ]);

        $modele = ModeleAbonnement::findOrFail($id);

        $modele->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'duree' => $validated['duree'],
        ]);

        $modele->parametres()->detach();
        foreach ($validated['parametres'] as $parametre) {
            $association = AssociationModeleParametre::create([
                'id_modele' => $modele->id,
                'id_parametre' => $parametre['id'],
            ]);

            ValeurParametreModele::create([
                'valeur' => $parametre['valeur'],
                'id_association_modele' => $association->id,
            ]);
        }

        return redirect()->route('model_subscription.index')->with('success', "Modèle d'abonnement {$modele->nom}  mis à jour avec succès.");
    }*/

        public function update(Request $request, $id)
    {
        // Validation des données
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:modele_abonnements,nom,' . $id,
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'duree' => 'required|string|max:50',
            'parametres' => 'required|array',
            'parametres.*.id' => 'exists:parametre_modeles,id',
            'parametres.*.valeur' => 'required|numeric|min:0',
        ], [
            'nom.unique' => 'Ce modèle d\'abonnement existe déjà',
            'parametres.required' => 'Veuillez ajouter au moins un paramètre'
        ]);

        // Vérification des doublons dans les paramètres
        $parametreIds = collect($validated['parametres'])->pluck('id');
        if ($parametreIds->count() !== $parametreIds->unique()->count()) {
            return redirect()->back()->withErrors(['parametres' => 'Vous ne pouvez pas ajouter un paramètre deux fois.']);
        }

        // Récupération du modèle d'abonnement
        $modele = ModeleAbonnement::findOrFail($id);

        // Mise à jour des attributs du modèle
        $modele->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'duree' => $validated['duree'],
        ]);

        // Récupération des paramètres actuels
        $parametresExistants = $modele->parametres()->with('valeurs')->get();

        // Création d'un tableau pour les IDs des paramètres existants
        $idsExistants = $parametresExistants->pluck('id')->toArray();

        // Mise à jour ou création des associations
        foreach ($validated['parametres'] as $parametre) {
            if (in_array($parametre['id'], $idsExistants)) {
                // Si le paramètre existe déjà, on met à jour la valeur
                $association = $parametresExistants->firstWhere('id', $parametre['id']);
                $association->valeurs()->updateOrCreate(
                    ['id_association_modele' => $association->id],
                    ['valeur' => $parametre['valeur']]
                );
            } else {
                // Si le paramètre n'existe pas, on crée une nouvelle association
                $association = AssociationModeleParametre::create([
                    'id_modele' => $modele->id,
                    'id_parametre' => $parametre['id'],
                ]);

                ValeurParametreModele::create([
                    'valeur' => $parametre['valeur'],
                    'id_association_modele' => $association->id,
                ]);
            }
        }

        // Optionnel : Suppression des paramètres non présents dans la nouvelle liste
        $nouveauxIds = collect($validated['parametres'])->pluck('id')->toArray();
        foreach ($parametresExistants as $parametreExist) {
            if (!in_array($parametreExist->id, $nouveauxIds)) {
                $parametreExist->valeurs()->delete();
                $parametreExist->delete();
            }
        }

        // Redirection avec message de succès
        return redirect()->route('model_subscription.index')->with('success', "Modèle d'abonnement {$modele->nom} mis à jour avec succès.");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $modele = ModeleAbonnement::with(['parametresAvecValeurs.valeurs'])->findOrFail($id);

        if ($modele->transactions()->exists()) {
            return redirect()->route('model_subscription.index')
                ->with('error', "Impossible de supprimer le modèle d'abonnement: {$modele->nom}.");
        }

        foreach ($modele->parametresAvecValeurs as $association) {
            $association->valeurs()->delete(); 
            $association->delete(); 
        }

        $modele->delete();

        return redirect()->route('model_subscription.index')->with('success', "Modèle d'abonnement {$modele->nom} supprimé avec succès.");
    }

}
