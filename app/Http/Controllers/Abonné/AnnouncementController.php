<?php

namespace App\Http\Controllers\Abonné;

use App\Http\Controllers\Controller;
use App\Models\ParametreCategorie;
use Illuminate\Http\Request;
use App\Models\CategorieBien;
use App\Models\Bien;
use App\Models\PhotoBien;
use App\Models\ValeurBien;
use App\Models\AssociationCategorieParametre;
use Illuminate\Support\Facades\Storage; // Ajoutez cette ligne

class AnnouncementController extends Controller
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
        $categories = CategorieBien::where('statut', '=', 'actif')->with('associations.parametre')->get();

        return view('abonné.pages.announcement.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $action = $request->input('action', 'save');

        $validationRules = [
            'category' => 'required|exists:categorie_biens,id',
            'titre' => 'required|string|max:255',
        ];

        if ($action === 'publish') {
            $validationRules = array_merge($validationRules, [
                'prix' => 'required|numeric|min:1',
                'lieu' => 'required|string|max:200',
                'type_offre' => 'required|string',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'required|string|max:200',
                'parameters' => 'array',
            ]);
        }

        $validated = $request->validate($validationRules);

        $annonce = Bien::create([
            'titre' => $validated['titre'],
            'description' => $request->input('description', ' '),
            'prix' => $request->input('prix', 0),
            'lieu' => $request->input('lieu', ' '),
            'statut' => $action === 'publish' ? 'publié' : 'brouillon',
            'type_offre' => $request->input('type_offre', ' '),
            'id_user' => auth()->id(),
            'id_categorie_bien' => $validated['category'],
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $photo->getClientOriginalName());
                $photoPath = $photo->storeAs('images/annonces', $photoName, 'public');

                PhotoBien::create([
                    'url_photo' => Storage::url($photoPath),
                    'id_bien' => $annonce->id,
                ]);
            }
        } else {
            $defaultPhotoPath = '/storage/images/annonces/default_image_an.jpg';
            PhotoBien::create([
                'url_photo' => $defaultPhotoPath,
                'id_bien' => $annonce->id,
            ]);
        }

        if (!empty($validated['parameters'])) {
            foreach ($validated['parameters'] as $paramId => $value) {
                ValeurBien::create([
                    'valeur' => $value,
                    'id_bien' => $annonce->id,
                    'id_association_categorie' => $paramId,
                ]);
            }
        }

        if ($action === 'publish') {
            return redirect()->route('acceuil')->with('success', 'Annonce publiée avec succès!');
        }

        return redirect()->route('acceuil')->with('success', 'Annonce enregistrée comme brouillon!');
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
        $bien = Bien::with(['photos', 'categorieBien', 'valeurs'])->findOrFail($id);
        $categories = CategorieBien::with('associations.parametre')->get();
        $parametresCategories = AssociationCategorieParametre::with('parametre')->get();

        return view('abonné.pages.announcement.edit', [
            'bien' => $bien,
            'categories' => $categories,
            'parametresCategories' => $parametresCategories,
        ]);
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
    public function destroy($id)
    {
        $annonce = Bien::with('photos')->findOrFail($id);

        if ($annonce->id_user !== auth()->id()) {
            return redirect()->route('acceuil')->with('error', 'Vous n\'êtes pas autorisé à supprimer cette annonce.');
        }

        foreach ($annonce->photos as $photo) {
            $defaultPhotoPath = '/storage/images/annonces/default_main_image.jpg';
            if ($photo->url_photo !== $defaultPhotoPath) {
                $relativePath = str_replace('/storage/', '', $photo->url_photo); // Convertir en chemin relatif
                Storage::disk('public')->delete($relativePath); // Supprimer du stockage
            }

            $photo->delete();
        }

        $annonce->valeurs()->delete();

        $annonce->delete();

        return redirect()->route('dashboard')->with('success', 'Annonce supprimée avec succès!');
    }

    //Fonction de mise fin d'une annonce
    public function terminate($id)
    {
        $annonce = Bien::find($id);
        if (!$annonce) {
            return redirect()->back()->with('error', 'Annonce introuvable.');
        }
        if ($annonce->id_user !== auth()->id()) {
            return redirect()->route('acceuil')->with('error', 'Vous n\'êtes pas autorisé à mettre fin cette annonce.');
        }
        if ($annonce->statut !== 'publié' && $annonce->statut !== 'republié') {
            return redirect()->back()->with('error', 'Seules les annonces publiées peuvent être arrêtées.');
        }

        $annonce->statut = 'terminé';
        $annonce->save();

        return redirect()->route('dashboard')->with('success', 'Annonce arrêtée avec succès.');
    }

    // Fonction pour relancer une annonce
    public function relaunch($id)
    {
        $annonce = Bien::find($id);
        if (!$annonce) {
            return redirect()->back()->with('error', 'Annonce introuvable.');
        }
        if ($annonce->id_user !== auth()->id()) {
            return redirect()->route('acceuil')->with('error', 'Vous n\'êtes pas autorisé à mettre fin cette annonce.');
        }
        if ($annonce->statut !== 'terminé') {
            return redirect()->back()->with('error', 'Seules les annonces arrêtées peuvent être republées.');
        }

        $annonce->statut = 'republié';
        $annonce->save();

        return redirect()->route('dashboard')->with('success', 'Annonce republiée avec succès.');
    }
}
