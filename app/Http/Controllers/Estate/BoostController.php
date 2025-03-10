<?php

namespace App\Http\Controllers\Estate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Boost;
use App\Models\Bien;
use Illuminate\Support\Carbon;

class BoostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    //Fonction permettant de booster une annonce
    public function store(Request $request, $id_bien) {
        $request->validate([
            'type_boost' => 'required|in:top,mise_en_avant,auto-remontee',
            //'duree' => 'required|integer|min:1',
            'unite_duree' => 'required|in:jour,semaine,mois,annee',
        ]);

        $user = auth()->user();

        $bien = Bien::find($id_bien);
        if ($bien->id_user !== $user->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez booster que vos propres annonces.');
        }

        if ($bien->statut !== 'publié') {
            return redirect()->back()->with('error', 'Seules les annonces publiées peuvent être boostées.');
        }

        $boost = new Boost();
        $boost->id_bien = $bien->id;
        $boost->type_boost = $request->type_boost;
        //$boost->duree = $request->duree;
        $boost->duree = '30';
        $boost->unite_duree = $request->unite_duree;
        $boost->date_debut = now();
        $boost->date_fin = Carbon::now()->addDays($request->duree);
        $boost->statut = 'actif';
        $boost->save();

        return redirect()->route('acceuil')->with('success', 'Vous venez de booster votre annonce');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
