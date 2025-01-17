<?php

namespace App\Http\Controllers\Abonné;

use App\Http\Controllers\Controller;
use App\Models\ParametreCategorie;
use Illuminate\Http\Request;
use App\Models\CategorieBien;
use App\Models\Bien;
use App\Models\PhotoBien;
use App\Models\ValeurBien;

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
        // Récupérer toutes les catégories de biens actives avec leurs paramètres associés
        $categories = CategorieBien::where('statut', '=', 'actif')->with('associations.parametre')->get();

        return view('abonné.pages.announcement.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Vérifier quelle action est demandée
        $action = $request->input('action', 'save'); // Par défaut, 'save' si non spécifié

        // Définir les règles de validation en fonction de l'action
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

        // Valider les données
        $validated = $request->validate($validationRules);

        // Créer l'annonce avec les informations minimales ou complètes
        $annonce = Bien::create([
            'titre' => $validated['titre'],
            'description' => $request->input('description', ''),
            'prix' => $request->input('prix', 0), // Met 0 par défaut si non fourni
            'lieu' => $request->input('lieu', ''),
            'statut' => $action === 'publish' ? 'publié' : 'brouillon',
            'type_offre' => $request->input('type_offre', ''),
            'id_user' => auth()->id(),
            'id_categorie_bien' => $validated['category'],
        ]);

        // Gérer les photos
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

        // Gérer les paramètres supplémentaires si fournis
        if (!empty($validated['parameters'])) {
            foreach ($validated['parameters'] as $paramId => $value) {
                ValeurBien::create([
                    'valeur' => $value,
                    'id_bien' => $annonce->id,
                    'id_association_categorie' => $paramId,
                ]);
            }
        }

        // Redirection avec message
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
