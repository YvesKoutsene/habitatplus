<?php

namespace App\Http\Controllers\Estate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategorieBien;
use App\Models\AssociationCategorieParametre;

use App\Models\ParametreCategorie;
use App\Models\AlerteRecherche;
use App\Models\Bien;

class CategoryBienController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

        $query = CategorieBien::with(['associations.parametre'])
            ->orderBy('created_at', 'asc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(titre) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        $categories = $query->paginate($perPage);

        return view('admin.pages.category_bien.index', compact('categories', 'search', 'perPage'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parametres = ParametreCategorie::all();

        return view('admin.pages.category_bien.create', compact('parametres'));
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'titre' => 'required|string|max:255|unique:categorie_biens,titre',
            'description' => 'required|string|max:255',
            'parametres' => 'nullable|array',
            'parametres.*' => 'exists:parametre_categories,id',
            'autres_parametres' => 'nullable|array',
            'autres_parametres.*' => 'nullable|string|max:255',
        ],
            [
                'titre.unique' => 'Cette catégorie de bien existe déjà',
                'parametres.min' => 'Vous devez paramétrer la catégorie de bien.',
                'autres_parametres.array' => 'Les autres paramètres doivent être sous forme de liste.',
            ]);

        if (empty($validated['parametres']) && empty($validated['autres_parametres'])) {
            return redirect()->back()->withErrors(['parametres' => 'Vous devez paramétrer cette catégorie de bien.']);
        }

        $nomsExistants = ParametreCategorie::pluck('nom_parametre')->toArray();
        if (!empty($validated['autres_parametres'])) {
            foreach ($validated['autres_parametres'] as $nomParametre) {
                if (!empty($nomParametre) && in_array($nomParametre, $nomsExistants)) {
                    return redirect()->back()->withErrors(['autres_parametres' => "Le paramètre '{$nomParametre}' existe déjà. Veuillez choisir un nom différent."]);
                }
            }
        }

        $categorie = CategorieBien::create([
            'titre' => $validated['titre'],
            'statut' => 'actif',
            'description' => $validated['description']
        ]);

        if (!empty($validated['parametres'])) {
            foreach ($validated['parametres'] as $parametreId) {
                AssociationCategorieParametre::create([
                    'id_categorie' => $categorie->id,
                    'id_parametre' => $parametreId,
                ]);
            }
        }

        if (!empty($validated['autres_parametres'])) {
            foreach ($validated['autres_parametres'] as $nomParametre) {
                if (!empty($nomParametre)) {
                    $nouveauParametre = ParametreCategorie::create([
                        'nom_parametre' => $nomParametre
                    ]);

                    AssociationCategorieParametre::create([
                        'id_categorie' => $categorie->id,
                        'id_parametre' => $nouveauParametre->id,
                    ]);
                }
            }
        }

        return redirect()->route('category_bien.index')
            ->with('success', "Catégorie de bien {$categorie->titre} créée avec succès.");
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
        $categorie = CategorieBien::with('associations.parametre')->findOrFail($id);

        if ($categorie->biens()->exists() || $categorie->alertes()->exists()) {
            return redirect()->route('category_bien.index')
                ->with('error', "Impossible d'éditer la catégorie de bien : {$categorie->titre}.");
        }

        $parametres = ParametreCategorie::all();

        return view('admin.pages.category_bien.edit', compact('categorie', 'parametres'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
     {
         //dd($request->all());
         $validated = $request->validate([
             'titre' => 'required|string|max:255|unique:categorie_biens,titre,' . $id,
             'description' => 'required|string|max:255',
             'parametres' => 'nullable|array',
             'parametres.*' => 'exists:parametre_categories,id',
             'autres_parametres' => 'nullable|array',
             'autres_parametres.*' => 'nullable|string|max:255',
         ],
             [
                 'titre.unique' => 'Cette catégorie de bien existe déjà',
                 'parametres.min' => 'Vous devez paramétrer la catégorie de bien.',
                 'autres_parametres.array' => 'Les autres paramètres doivent être sous forme de liste.',
             ]);

         if (empty($validated['parametres']) && empty($validated['autres_parametres'])) {
             return redirect()->back()->withErrors(['parametres' => 'Vous devez paramétrer cette catégorie de bien.']);
         }

         $categorie = CategorieBien::findOrFail($id);

         if ($categorie->biens()->exists() || $categorie->alertes()->exists()) {
             return redirect()->route('category_bien.index')
                 ->with('error', "Impossible d'éditer la catégorie de bien : {$categorie->titre}.");
         }

         $nomsExistants = ParametreCategorie::pluck('nom_parametre')->toArray();
         if (!empty($validated['autres_parametres'])) {
             foreach ($validated['autres_parametres'] as $nomParametre) {
                 if (!empty($nomParametre) && in_array($nomParametre, $nomsExistants)) {
                     return redirect()->back()->withErrors(['autres_parametres' => "Le paramètre '{$nomParametre}' existe déjà. Veuillez choisir un nom différent."]);
                 }
             }
         }

         $categorie->update([
             'titre' => $validated['titre'],
             'statut' => 'actif',
             'description' => $validated['description'],
         ]);

         AssociationCategorieParametre::where('id_categorie', $categorie->id)->delete();

         foreach ($validated['parametres'] as $parametreId) {
             AssociationCategorieParametre::create([
                 'id_categorie' => $categorie->id,
                 'id_parametre' => $parametreId,
             ]);
         }

         if (!empty($validated['autres_parametres'])) {
             foreach ($validated['autres_parametres'] as $nomParametre) {
                 if (!empty($nomParametre)) {
                     $nouveauParametre = ParametreCategorie::create([
                         'nom_parametre' => $nomParametre
                     ]);

                     AssociationCategorieParametre::create([
                         'id_categorie' => $categorie->id,
                         'id_parametre' => $nouveauParametre->id,
                     ]);
                 }
             }
         }

         return redirect()->route('category_bien.index')
             ->with('success', "Catégorie de bien {$categorie->titre} mise à jour avec succès.");
     }


    /**
     * Remove the specified resource from storage.
     */

    public function suspend(CategorieBien $categorieBien)
    {
        if ($categorieBien->statut !== 'actif') {
            return redirect()->route('category_bien.index')->with('error', 'Seules les catégories actives peuvent être désactivées.');
        }

        $categorieBien->update(['statut' => 'inactif']);
        return redirect()->route('category_bien.index')->with('success', "Catégorie de bien {$categorieBien->name} désactivée.");
    }

    public function reactivate(CategorieBien $categorieBien)
    {
        if ($categorieBien->statut !== 'inactif') {
            return redirect()->route('category_bien.index')->with('error', 'Seules les catégories inactives peuvent être réactivées.');
        }

        $categorieBien->update(['statut' => 'actif']);
        return redirect()->route('category_bien.index')->with('success', "Catégorie de bien {$categorieBien->name} réactivée.");
    }

    public function destroy($categorieBien)
    {
        $category = CategorieBien::findOrFail($categorieBien);

        if ($category->biens()->exists() || $category->alertes()->exists()) {
            return redirect()->route('category_bien.index')
                ->with('error', "Impossible d'éditer la catégorie de bien : {$category->titre}.");
        }

        AssociationCategorieParametre::where('id_categorie', $category->id)->delete();

        $category->delete();

        return redirect()->route('category_bien.index')->with('success', "Catégorie de bien {$category->titre} supprimée avec succès.");
    }

}
