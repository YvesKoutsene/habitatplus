<?php

namespace App\Http\Controllers\Admin;

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
             'parametres' => 'required|array', 
             'parametres.*' => 'exists:parametre_categories,id', 
         ],
         [
            'titre.unique' => 'Cette catégorie de bien existe déjà',
            'parametres.required' => 'Veuillez paramétrer la catégorie de bien'
         ]);
     
         $categorie = CategorieBien::create([
             'titre' => $validated['titre'],
             'description' => $validated['description']
         ]);
     
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
        $categorie = CategorieBien::with('associations.parametre')->findOrFail($id);

        $parametres = ParametreCategorie::all();

        return view('admin.pages.category_bien.edit', compact('categorie', 'parametres'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
     {
         $validated = $request->validate([
             'titre' => 'required|string|max:255|unique:categorie_biens,titre,' . $id,
             'description' => 'required|string|max:255',
             'parametres' => 'required|array',
             'parametres.*' => 'exists:parametre_categories,id',
         ],
         [
             'titre.unique' => 'Cette catégorie de bien existe déjà.',
             'parametres.required' => 'Veuillez paramétrer la catégorie de bien.'
         ]);
     
         // Trouver la catégorie
         $categorie = CategorieBien::findOrFail($id);
     
         // Mettre à jour la catégorie
         $categorie->update([
             'titre' => $validated['titre'],
             'description' => $validated['description'],
         ]);
     
         // Synchroniser les paramètres associés
         AssociationCategorieParametre::where('id_categorie', $categorie->id)->delete();
     
         foreach ($validated['parametres'] as $parametreId) {
             AssociationCategorieParametre::create([
                 'id_categorie' => $categorie->id,
                 'id_parametre' => $parametreId,
             ]);
         }
     
         return redirect()->route('category_bien.index')
             ->with('success', "Catégorie de bien {$categorie->titre} mise à jour avec succès.");
     }     


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($categorieBien)
    {
        $category = CategorieBien::findOrFail($categorieBien);

        if ($category->biens()->exists() && $category->alertes()->exists()) {
            return redirect()->route('parameter_category.index')
                ->with('error', "Impossible de supprimer la catégorie de bien : {$category->titre}.");
        }        

        AssociationCategorieParametre::where('id_categorie', $category->id)->delete();

        $category->delete();

        return redirect()->route('category_bien.index')->with('success', "Catégorie de bien {$category->titre} supprimée avec succès.");
    }

}
