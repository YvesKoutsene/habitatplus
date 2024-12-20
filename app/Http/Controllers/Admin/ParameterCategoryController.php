<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ParametreCategorie; 
use App\Models\AssociationCategorieParametre; 

class ParameterCategoryController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parametres = ParametreCategorie::orderBy('created_at', 'asc')->get();
        return view('admin.pages.parameter_category.index', compact('parametres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.parameter_category.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom_parametre' => 'required|string|max:255|unique:parametre_categories,nom_parametre',
        ],[
            'nom_parametre.unique' => 'Ce paramètre de catégorie de bien existe déjà'
        ]);

        $parametre = ParametreCategorie::create($request->all());

        return redirect()->route('parameter_category.index')->with('success', "Paramètre {$parametre->nom_parametre} créé avec succès.");
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
    public function edit(ParametreCategorie $parametreCategorie)
    {
        return view('admin.pages.parameter_category.edit', compact('parametreCategorie'));
    }

    /**
     * Update the specified resource in storage.
     */


     public function update(Request $request, $id)
     {
         $parametreCategorie = ParametreCategorie::findOrFail($id);
     
         $request->validate([
             'nom_parametre' => 'required|string|max:255|unique:parametre_categories,nom_parametre,' . $id . ',id',
         ], [
             'nom_parametre.unique' => 'Ce paramètre de catégorie de bien existe déjà'
         ]);
     
         $parametreCategorie->nom_parametre = $request->nom_parametre;
         $parametreCategorie->save();
     
         return redirect()->route('parameter_category.index')->with('success', "Paramètre {$parametreCategorie->nom_parametre} mis à jour avec succès.");
     }
     

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($parametreCategorie)
    {

        $parametre = ParametreCategorie::findOrFail($parametreCategorie);

        if ($parametre->biens()->exists()) {
            return redirect()->route('parameter_category.index')
                ->with('error', "Le paramètre {$parametreCategorie->nom_parametre} ne peut pas être supprimé.");
        }
     
        $parametre->delete();
     
        return redirect()->route('parameter_category.index')->with('success', "Paramètre {$parametre->nom_parametre} supprimé avec succès.");
    }
    
    
}
