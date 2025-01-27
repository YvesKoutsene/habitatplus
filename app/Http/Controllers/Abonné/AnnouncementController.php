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

        //dd($request->all());

        $validationRules = [
            'category' => 'required|exists:categorie_biens,id',
            'titre' => 'required|string|max:255',
            'parameters' => 'array',
        ];

        if ($action === 'publish') {
            $validationRules = array_merge($validationRules, [
                'prix' => 'required|numeric|min:1',
                'lieu' => 'required|string|max:200',
                'type_offre' => 'required|string',
                'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'required|string|max:200',
            ]);
        }

        $validated = $request->validate($validationRules);

        $annonce = Bien::create([
            'titre' => $validated['titre'],
            'description' => $request->input('description', ''),
            'prix' => $request->input('prix'),
            'lieu' => $request->input('lieu', ''),
            'statut' => $action === 'publish' ? 'publié' : 'brouillon',
            'type_offre' => $request->input('type_offre', ''),
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
        }

        $validatedParameters = $request->input('parameters', []);
        $associationIds = AssociationCategorieParametre::pluck('id')->toArray();

        foreach ($validatedParameters as $assocId => $value) {
            if (in_array($assocId, $associationIds)) {
                ValeurBien::create([
                    'valeur' => $value,
                    'id_bien' => $annonce->id,
                    'id_association_categorie' => $assocId,
                ]);
            }
        }

        $message = $action === 'publish' ? 'Annonce publiée avec succès!' : 'Annonce enregistrée comme brouillon!';
        return redirect()->route('dashboard')->with('success', $message);
    }


    /**
     * Display the specified resource.
     */

    public function show($id)
    {
        $bien = Bien::with(['user','categorieBien', 'photos', 'valeurs'])->findOrFail($id);

        if (!$bien) {
            return redirect()->back()->with('error', 'Annonce introuvable.');
        }
        if ($bien->id_user !== auth()->id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à voir cette annonce.');
        }
        return view('abonné.pages.announcement.show', compact('bien'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bien = Bien::with(['photos', 'categorieBien', 'valeurs'])->findOrFail($id);
        if ($bien->id_user !== auth()->id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier cette annonce.');
        }

        $categories = CategorieBien::with('associations.parametre')->get();
        $parametresCategories = AssociationCategorieParametre::with('parametre')->get();
        $existingPhotoIds = $bien->photos->pluck('id')->toArray();

        //dd($bien);
        return view('abonné.pages.announcement.edit', [
            'bien' => $bien,
            'categories' => $categories,
            'parametresCategories' => $parametresCategories,
            'existingPhotoIds' => $existingPhotoIds,
        ]);
    }

    public function update(Request $request, $id)
    {
        $action = $request->input('action', 'save');
        $bien = Bien::with(['photos', 'categorieBien', 'valeurs'])->findOrFail($id);
        if ($bien->id_user !== auth()->id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à modifier cette annonce.');
        }

        //dd($request->all());

        $validationRules = [
            'category' => 'required|exists:categorie_biens,id',
            'titre' => 'required|string|max:255',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($action === 'publish' && in_array($bien->statut, ['brouillon', 'terminé'])) {
            $validationRules = array_merge($validationRules, [
                'prix' => 'required|numeric|min:1',
                'lieu' => 'required|string|max:200',
                'type_offre' => 'required|string',
                'description' => 'required|string|max:200',
                'parameters' => 'array',
            ]);
        } else {
            $validationRules['parameters'] = 'array';
        }
        $validated = $request->validate($validationRules);
        $isCategoryChanged = $request->input('current_category') != $validated['category'];

        $bien->update([
            'titre' => $validated['titre'],
            'description' => $request->input('description', $bien->description),
            'prix' => $request->input('prix', $bien->prix),
            'lieu' => $request->input('lieu', $bien->lieu),
            'statut' => ($action === 'publish' && in_array($bien->statut, ['brouillon', 'terminé'])) ? 'publié' : $bien->statut,
            'type_offre' => $request->input('type_offre', $bien->type_offre),
            'id_categorie_bien' => $validated['category'],
        ]);

        $this->updatePhotos($bien, $request);
        $this->updateParameters($bien, $validated, $isCategoryChanged);
        return redirect()->route('dashboard')->with('success', 'Annonce mise à jour avec succès!');
    }

    /**
     * Gère la mise à jour des photos du bien.
     */

    protected function updatePhotos($bien, $request)
    {
        $existingPhotoIds = $request->input('existing_photos', []);

        $deletedPhotoIds = $request->input('deleted_photos', []);
        foreach ($deletedPhotoIds as $photoId) {
            if ($photoId) {
                $photo = $bien->photos()->find($photoId);
                if ($photo) {
                    $photoPath = str_replace('/storage', 'public', $photo->url_photo);
                    Storage::delete($photoPath);
                    $photo->delete();
                }
            }
        }
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $newPhotoFile) {
                if (isset($existingPhotoIds[$index])) {
                    $photoId = $existingPhotoIds[$index];
                    $photo = $bien->photos()->find($photoId);

                    if ($photo) {
                        $oldPhotoPath = str_replace('/storage', 'public', $photo->url_photo);
                        Storage::delete($oldPhotoPath);

                        $photoName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $newPhotoFile->getClientOriginalName());
                        $newPhotoPath = $newPhotoFile->storeAs('images/annonces', $photoName, 'public');

                        $photo->update(['url_photo' => Storage::url($newPhotoPath)]);
                    }
                }
            }
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                if (!isset($existingPhotoIds[$index])) {
                    $photoName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $photo->getClientOriginalName());
                    $photoPath = $photo->storeAs('images/annonces', $photoName, 'public');

                    PhotoBien::create([
                        'url_photo' => Storage::url($photoPath),
                        'id_bien' => $bien->id,
                    ]);
                }
            }
        }
    }

    /**
     * Gère la mise à jour des valeurs des paramètres.
     */

    protected function updateParameters($bien, $validated, $isCategoryChanged)
    {
        $currentValues = $bien->valeurs()->get();

        if ($isCategoryChanged) {
            $bien->valeurs()->delete();

            if (!empty($validated['parameters'])) {
                foreach ($validated['parameters'] as $assocId => $value) {
                    ValeurBien::create([
                        'valeur' => $value,
                        'id_bien' => $bien->id,
                        'id_association_categorie' => $assocId,
                    ]);
                }
            }
        } else {
            if (!empty($validated['parameters'])) {
                foreach ($validated['parameters'] as $assocId => $value) {
                    $existingValue = $currentValues->where('id_association_categorie', $assocId)->first();

                    if ($existingValue) {
                        if ($existingValue->valeur != $value) {
                            $existingValue->update(['valeur' => $value]);
                        }
                    } else {
                        ValeurBien::create([
                            'valeur' => $value,
                            'id_bien' => $bien->id,
                            'id_association_categorie' => $assocId,
                        ]);
                    }
                }
            }

            $validatedIds = array_keys($validated['parameters']);
            $currentValues
                ->whereNotIn('id_association_categorie', $validatedIds)
                ->each(function ($value) {
                    $value->delete();
                });
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    //Fonction de mise fin d'une annonce
    public function terminate($id)
    {
        $annonce = Bien::find($id);
        if (!$annonce) {
            return redirect()->back()->with('error', 'Annonce introuvable.');
        }
        if ($annonce->id_user !== auth()->id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à mettre fin cette annonce.');
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
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à mettre fin cette annonce.');
        }

        if ($annonce->statut !== 'terminé') {
            return redirect()->back()->with('error', 'Seules les annonces arrêtées peuvent être republées.');
        }

        $annonce->statut = 'republié';
        $annonce->save();

        return redirect()->route('dashboard')->with('success', 'Annonce republiée avec succès.');
    }

    public function destroy($id)
    {
        $annonce = Bien::with('photos')->findOrFail($id);
        if ($annonce->id_user !== auth()->id()) {
            return redirect()->back()->with('error', 'Vous n\'êtes pas autorisé à supprimer cette annonce.');
        }
        foreach ($annonce->photos as $photo) {
            $defaultPhotoPath = '/storage/images/annonces/default_main_image.jpg';
            if ($photo->url_photo !== $defaultPhotoPath) {
                $relativePath = str_replace('/storage/', '', $photo->url_photo);
                Storage::disk('public')->delete($relativePath);
            }

            $photo->delete();
        }
        $annonce->valeurs()->delete();
        $annonce->delete();

        return redirect()->route('dashboard')->with('success', 'Annonce supprimée avec succès!');
    }
}
