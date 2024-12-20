<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategorieBien;
use App\Models\AssociationCategorieParametre;

use App\Models\ParametreCategorie;



class CategoryBienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = CategorieBien::with(['associations.parametre'])
            ->orderBy('created_at', 'asc')
            ->get();
    
        return view('admin.pages.category_bien.index', compact('categories'));
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
         $validated = $request->validate([
             'titre' => 'required|string|max:255|unique:categorie_biens,titre',
             'description' => 'required|string|max:255|:categorie_biens,description',
             'parametres' => 'required|array', // On s'assure que des paramètres sont envoyés
             'parametres.*' => 'exists:parametre_categories,id', // Validation des IDs des paramètres
         ],
         [
            'titre.unique' => 'Cette catégorie de bien existe déjà',
            'parametres.required' => 'Veuillez paramétrer la catégorie de bien'
         ]);
     
         // Création de la catégorie
         $categorie = CategorieBien::create([
             'titre' => $validated['titre'],
             'description' => $validated['description']
         ]);
     
         // Création des associations
         foreach ($validated['parametres'] as $parametreId) {
             AssociationCategorieParametre::create([
                 'id_categorie' => $categorie->id,
                 'id_parametre' => $parametreId,
             ]);
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
        // Récupérer la catégorie avec ses paramètres associés
        $categorie = CategorieBien::with('associations.parametre')->findOrFail($id);

        // Récupérer tous les paramètres disponibles
        $parametres = ParametreCategorie::all();

        return view('admin.pages.category_bien.edit', compact('categorie', 'parametres'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, CategorieBien $categorieBien)
    {
        $validated = $request->validate([
            'nom_categorie' => 'required|string|max:255|unique:categorie_biens,nom_categorie,' . $categorieBien->id,
        ]);

        $categorieBien->update($validated);

        return redirect()->route('category_bien.index')->with('success', "Catégorie '{$request->nom_categorie}' modifiée avec succès.");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($categorieBien)
    {
        $category = CategorieBien::findOrFail($categorieBien);
 
        // Vérifier si la catégorie est utilisée dans des associations
        if ($category->associations()->exists()) {
            return redirect()->route('categories.index')->with('error', "Impossible de supprimer la catégorie de bien {$category->titre}.");
        }

        $category->delete();

        return redirect()->route('category_bien.index')->with('success', "Catégorie {$category->titre} supprimée avec succès.");
    }

}
