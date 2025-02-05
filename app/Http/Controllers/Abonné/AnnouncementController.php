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
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage; // Ajoutez cette ligne

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //Fonction permettant d'afficher la liste des biens crées dans la base de données
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

        $query = Bien::with(['user','categorieBien', 'photos', 'valeurs'])->where(function($query) {
                 $query->where('statut', 'publié')
                     ->orWhere('statut', 'bloqué')
                     ->orWhere('statut', 'terminé');
             })->orderBy('created_at', 'asc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(titre) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(lieu) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(type_offre) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        $biens = $query->paginate($perPage);

        return view('admin.pages.announcement.index', compact('biens', 'search', 'perPage'));
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
                'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'description' => 'required|string|max:200',
            ],[
                'photos.*.required' => 'Vous devez ajouter au moins la photo principale avant de publier'
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
            'datePublication' => Carbon::now(),
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

        if ($bien->statut == 'bloqué') {
            return redirect()->back()->with('error', 'Impossible de modifier cette annonce.');
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

        if ($bien->statut == 'bloqué') {
            return redirect()->back()->with('error', 'Impossible de modifier cette annonce.');
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
            'datePublication' => Carbon::now(),
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
        if ($annonce->statut !== 'publié') {
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

        $annonce->statut = 'publié';
        $annonce->datePublication = Carbon::now();
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

    //Fonction pour bloquer une annonce
    public function block($id)
    {
        $annonce = Bien::find($id);
        if (!$annonce) {
            return redirect()->back()->with('error', 'Annonce introuvable.');
        }

        if ($annonce->statut !== 'publié') {
            return redirect()->back()->with('error', 'Seules les annonces publiées peuvent être bloquées.');
        }

        $annonce->statut = 'bloqué';
        $annonce->save();

        return redirect()->back()->with('success', 'Annonce bloquée avec succès.');
    }

    //Fonction pour réactiver une annonce bloquée par un admin
    public function reactivate($id)
    {
        $annonce = Bien::find($id);
        if (!$annonce) {
            return redirect()->back()->with('error', 'Annonce introuvable.');
        }

        if ($annonce->statut !== 'bloqué') {
            return redirect()->back()->with('error', 'Seules les annonces bloquées peuvent être réactivées.');
        }

        $annonce->statut = 'publié';
        $annonce->datePublication = Carbon::now();
        $annonce->save();

        return redirect()->back()->with('success', 'Annonce réactivée avec succès.');
    }

    //Fonction pour affiche le details d'une annonce pour un admin
    public function details($id)
    {
        $bien = Bien::with(['user','categorieBien', 'photos', 'valeurs'])->findOrFail($id);

        if (!$bien) {
            return redirect()->back()->with('error', 'Annonce introuvable.');
        }
        if (!in_array($bien->statut, ['publié', 'bloqué', 'terminé'])) {
            return redirect()->back()->with('error', 'Cette annonce n\'est pas disponible.');
        }

        return view('admin.pages.announcement.show', compact('bien'));
    }


}
