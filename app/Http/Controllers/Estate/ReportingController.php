<?php

namespace App\Http\Controllers\Estate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Signalement;
use App\Models\Bien;
use Illuminate\Support\Facades\Auth;

class ReportingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //Fonction permettant de signaler une annonce
    public function report(Request $request, $idBien)
    {
        $user = Auth::user();

        $bien = Bien::find($idBien);
        if (!$bien) {
            return redirect()->back()->with('error', 'Annonce introuvable.');

        }
        if ($bien->id_user == $user->id) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas signaler votre propre annonce.');
        }

        $request->validate([
            'motif' => 'required|string|max:255',
        ],[
            'motif.required' => 'Le motif est obligatoire.',
        ]);

        $existingSignalement = Signalement::where('id_user', $user->id)
            ->where('id_bien', $idBien)
            ->first();

        if ($existingSignalement) {
            return redirect()->back()->with('error', 'Vous avez déjà signalé cette annonce.');

        }

        Signalement::create([
            'motif' => $request->motif,
            'id_user' => $user->id,
            'id_bien' => $idBien,
        ]);

        return redirect()->route('acceuil')->with('success', 'Annonce signalée avec succès.');
    }

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
    public function store(Request $request)
    {
        //
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
