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
 
    public function index()
    {
        $modeles = ModeleAbonnement::with([
            'parametres' => function($query) {
                $query->with('valeurs');
            }
        ])->paginate(10);

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

        return redirect()->route('model_subscription.index')->with('success', "Modèle d\'abonnement {$modele->nom} créé avec succès.");
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
        // Récupérer le modèle avec ses paramètres et valeurs
        $modele = ModeleAbonnement::with(['parametres.valeurs'])->findOrFail($id);

        // Récupérer tous les paramètres possibles
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

        return redirect()->route('model_subscription.index')->with('success', "Modèle d\'abonnement {$modele->nom}  mis à jour avec succès.");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $modele = ModeleAbonnement::findOrFail($id);

        //Verifions s'il y'a un abonnement qui utilise ce modèle en passant par les transactions
        if ($modele->transactions()->exists()) {
            return redirect()->route('model_subscription.index')
                ->with('error', "Impossible de supprimer le modèle d'abonnement: {$modele->nom}.");
        }  

        foreach ($modele->parametres as $parametre) {
            $parametre->valeurs()->delete();
        }
        
        $modele->parametres()->detach();

        $modele->delete();

        return redirect()->route('model_subscriptions.index')->with('success', "Modèle d\'abonnement {$modele->nom} supprimé avec succès.");
    }

}
