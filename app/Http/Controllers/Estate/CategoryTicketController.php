<?php

namespace App\Http\Controllers\Estate;

use App\Http\Controllers\Controller;
use App\Models\CategorieBien;
use Illuminate\Http\Request;

use App\Models\CategorieTicket;

class CategoryTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

        $query = CategorieTicket::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(nom) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        $categories = $query->orderBy('created_at', 'asc')->paginate($perPage);

        return view('admin.pages.category_ticket.index', compact('categories', 'search', 'perPage'));
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
        $validated = $request->validate([
            'nom_categorie' => 'required|string|max:255|unique:categorie_tickets,nom_categorie',
            'description' => 'required|string|max:225',
        ],[
            'nom_categorie.unique' => 'Cette catégorie de ticket existe déjà'
        ]);

        $categorie = CategorieTicket::create([
            'nom_categorie' => $validated['nom_categorie'],
            'description' => $validated['description'],
            'statut' => 'actif'
        ]);

        return redirect()->route('category_ticket.index')->with('success', "Catégorie de ticket {$categorie->nom_categorie} créée avec succès.");
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
    public function update(Request $request, $id)
    {
        $categorie = CategorieTicket::findOrFail($id);

        $request->validate([
            'nom_categorie' => 'required|string|max:255|unique:categorie_tickets,nom_categorie,' . $id . ',id',
        ], [
            'nom_categorie.unique' => 'Cette catégorie de ticket existe déjà'

        ]);

        $categorie->nom_categorie = $request->nom_categorie;
        $categorie->description = $request->description;
        $categorie->save();

        return redirect()->route('category_ticket.index')->with('success', "Catégorie de ticket {$categorie->nom_categorie} mis à jour avec succès.");
    }

    public function suspend(CategorieTicket $categorieTicket)
    {
        if ($categorieTicket->statut !== 'actif') {
            return redirect()->route('category_ticket.index')->with('error', 'Seules les catégories actives peuvent être désactivées.');
        }

        $categorieTicket->update(['statut' => 'inactif']);
        return redirect()->route('category_ticket.index')->with('success', "Catégorie de ticket {$categorieTicket->nom_categorie} désactivée.");
    }

    public function reactivate(CategorieTicket $categorieTicket)
    {
        if ($categorieTicket->statut !== 'inactif') {
            return redirect()->route('category_ticket.index')->with('error', 'Seules les catégories inactives peuvent être réactivées.');
        }

        $categorieTicket->update(['statut' => 'actif']);
        return redirect()->route('category_ticket.index')->with('success', "Catégorie de ticket {$categorieTicket->nom_categorie} réactivée.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($categorie)
    {
        $categorie = CategorieTicket::findOrFail($categorie);

        if ($categorie->tickets()->exists()) {
            return redirect()->route('category_ticket.index')
                ->with('error', "La catégorie de ticket {$categorie->nom_categorie} ne peut pas être supprimé.");
        }

        $categorie->delete();

        return redirect()->route('category_ticket.index')->with('success', "Catégorie de ticket {$categorie->nom_categorie} supprimé avec succès.");
    }

}
