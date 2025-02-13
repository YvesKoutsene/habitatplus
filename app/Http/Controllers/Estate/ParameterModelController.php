<?php

namespace App\Http\Controllers\Estate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ParametreModele;
use App\Models\AssociationModeleParametre;

class ParameterModelController extends Controller
{
    //

    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

        $query = ParametreModele::query();

        if ($search) {
            $query->whereRaw('LOWER(nom_parametre) LIKE ?', ['%' . strtolower($search) . '%']);
        }

        $parametres = $query->orderBy('created_at', 'asc')->paginate($perPage);

        return view('admin.pages.parameter_model.index', compact('parametres', 'search', 'perPage'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nom_parametre' => 'required|string|max:255|unique:parametre_categories,nom_parametre',
        ],[
            'nom_parametre.unique' => 'Ce paramètre de catégorie de bien existe déjà'
        ]);

        $parametre = ParametreModele::create($request->all());

        return redirect()->route('parameter_model.index')->with('success', "Paramètre modèle {$parametre->nom_parametre} créé avec succès.");
    }

    public function update(Request $request, $id)
    {
        $parametre = ParametreModele::findOrFail($id);

        $request->validate([
            'nom_parametre' => 'required|string|max:255|unique:parametre_categories,nom_parametre,' . $id . ',id',
        ], [
            'nom_parametre.unique' => 'Ce paramètre de modèle d\'abonnement existe déjà'
        ]);

        $parametre->nom_parametre = $request->nom_parametre;
        $parametre->save();

        return redirect()->route('parameter_model.index')->with('success', "Paramètre {$parametre->nom_parametre} mis à jour avec succès.");
    }

    public function destroy($parametre)
    {

        $parametre = ParametreModele::findOrFail($parametre);

        if ($parametre->associations()->exists()) {
            return redirect()->route('parameter_model.index')
                ->with('error', "Le paramètre {$parametre->nom_parametre} ne peut pas être supprimé.");
        }

        $parametre->delete();

        return redirect()->route('parameter_model.index')->with('success', "Paramètre {$parametre->nom_parametre} supprimé avec succès.");
    }

}
