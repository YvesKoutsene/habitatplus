<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\models\User;
use App\Models\CategorieBien;
use App\Models\Bien;
use App\Models\PhotoBien;
use App\Models\ValeurBien;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $typeUser = Auth::user()->typeUser;

            if ($typeUser == 0 || $typeUser == 1) {
                return redirect()->route('profile.edit');
            } elseif ($typeUser == 2) {

                $user = Auth::user();
                $biens = $user->biens()
                    ->where(function($query) {
                        $query->where('statut', 'brouillon')
                            ->orWhere('statut', 'publié')
                            ->orWhere('statut', 'bloqué')
                            ->orWhere('statut', 'terminé');
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();

                return view('abonné.pages.auth.dashboard',compact('biens'));

            } else {
                return redirect()->back();
            }
        }

        return redirect('acceuil');
    }

    //Fonction pour taper sur la route "/" avec les biens publiés
    /*public function indexHome(Request $request) {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 32);

        $query = Bien::with(['categorieBien'])
            ->where('statut', 'publié')
            ->orderBy('datePublication', 'desc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(titre) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(lieu) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(type_offre) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        $biens = $query->paginate($perPage);

        return view('welcome', compact('biens', 'search'));
    }*/

    //Fonction permettant de renvoyer la page d'acceuil avec les annonces top et non boostée
    public function indexHome(Request $request) {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 32);

        $searchQuery = function ($query) use ($search) {
            $query->whereRaw('LOWER(titre) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(lieu) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(type_offre) LIKE ?', ['%' . strtolower($search) . '%'])
                ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%']);
        };

        // Récupérer les annonces boostées (Top) sans pagination
        $topBiens = Bien::with(['categorieBien', 'boost'])
            ->whereHas('boost', function ($query) {
                $query->where('type_boost', 'top')->where('statut', 'actif');
            })
            ->where('statut', 'publié')
            ->when($search, function ($query) use ($searchQuery) {
                $query->where($searchQuery);
            })
            ->orderBy('datePublication', 'desc')
            ->get();

        // Récupérer les annonces normales avec pagination
        $query = Bien::with(['categorieBien'])
            ->where('statut', 'publié')
            ->whereDoesntHave('boost', function ($query) {
                $query->where('type_boost', 'top')->where('statut', 'actif');
            })
            ->when($search, function ($query) use ($searchQuery) {
                $query->where($searchQuery);
            })
            ->orderBy('datePublication', 'desc');

        $biens = $query->paginate($perPage);

        return view('welcome', compact('biens', 'topBiens', 'search'));
    }

    //Fonction d'affichage de page de details d'un bien
    public function show($id)
    {
        $bien = Bien::with(['user', 'categorieBien', 'photos', 'valeurs'])->findOrFail($id);

        if (!$bien) {
            return redirect()->back()->with('error', 'Annonce introuvable.');
        }

        if (!in_array($bien->statut, ['publié'])) {
            return redirect()->back()->with('error', 'Cette annonce n\'est pas disponible.');
        }

        return view('abonné.pages.announcement.show', compact('bien'));
    }



}
