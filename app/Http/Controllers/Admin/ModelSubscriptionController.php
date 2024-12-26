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
        // Charger les modèles avec leurs paramètres et leurs valeurs associées
        $modeles = ModeleAbonnement::with(['parametres' => function ($query) {
            $query->with(['valeurs' => function ($query) {
                $query->select('valeur', 'id_association_modele');
            }]);
        }])
            ->orderBy('created_at', 'asc')
            ->paginate(10);
    
        return view('admin.pages.model_subscription.index', compact('modeles'));
    } */  

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10); 

        $query = ModeleAbonnement::with(['parametres' => function ($query) {
            $query->with(['valeurs' => function ($query) {
                $query->select('valeur', 'id_association_modele');
            }]);
        }])
            ->orderBy('created_at', 'asc');

         // Gestion de la recherche
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(nom) LIKE ?', ['%' . strtolower($search) . '%']) // Remplacez par le nom réel de votre colonne
                ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(duree) LIKE ?', ['%' . strtolower($search) . '%']);
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
            'duree' => 'required|string|max:50',
            'parametres' => 'required|array',
            'parametres.*.id' => 'exists:parametre_modeles,id', 
            'parametres.*.valeur' => 'required|numeric|min:0', 
        ],[
            'nom.unique' => 'Ce modèle d\'abonnment existe déjà',
            'parametres.required' => 'Veuillez ajouter au moins un paramètre'
        ]);

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

        $parametreIds = collect($validated['parametres'])->pluck('id');
        if ($parametreIds->count() !== $parametreIds->unique()->count()) {
            return redirect()->back()->withErrors(['parametres' => 'Vous ne pouvez pas ajouter un paramètre deux fois.']);
        }

        $modele = ModeleAbonnement::with('parametresAvecValeurs.valeurs')->findOrFail($id);

        $modele->update([
            'nom' => $validated['nom'],
            'description' => $validated['description'],
            'prix' => $validated['prix'],
            'duree' => $validated['duree'],
        ]);

        foreach ($validated['parametres'] as $parametre) {
            $association = $modele->parametresAvecValeurs->firstWhere('id_parametre', $parametre['id']);

            if ($association) {
                $valeur = $association->valeurs->first();
                if ($valeur) {
                    $valeur->update(['valeur' => $parametre['valeur']]);
                } else {
                    ValeurParametreModele::create([
                        'valeur' => $parametre['valeur'],
                        'id_association_modele' => $association->id,
                    ]);
                }
            } else {
                $newAssociation = AssociationModeleParametre::create([
                    'id_modele' => $modele->id,
                    'id_parametre' => $parametre['id'],
                ]);

                ValeurParametreModele::create([
                    'valeur' => $parametre['valeur'],
                    'id_association_modele' => $newAssociation->id,
                ]);
            }
        }

        $validParametreIds = $validated['parametres'];
        foreach ($modele->parametresAvecValeurs as $association) {
            if (!in_array($association->id_parametre, array_column($validParametreIds, 'id'))) {
                $association->valeurs()->delete();
                $association->delete();
            }
        }

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
                ->with('error', "Impossible de supprimer le modèle d'abonnement {$modele->nom}.");
        }

        foreach ($modele->parametresAvecValeurs as $association) {
            $association->valeurs()->delete(); 
            $association->delete(); 
        }

        $modele->delete();

        return redirect()->route('model_subscription.index')->with('success', "Modèle d'abonnement {$modele->nom} supprimé avec succès.");
    }

}
