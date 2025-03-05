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

    //Fonction pour taper sur la route "/"
    /*public function indexHome(Request $request) {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 24);

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

    public function indexHome(Request $request) {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 32);

        // Récupération des annonces avec leur boost
        $query = Bien::with(['categorieBien', 'boost', 'photos'])
            ->leftJoin('boosts', 'biens.id', '=', 'boosts.id_bien')
            ->where('biens.statut', 'publié');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(biens.titre) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(biens.lieu) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(biens.type_offre) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(biens.description) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        // Trier les annonces boostées en priorité
        $query->orderByRaw("
        CASE
            WHEN boosts.type_boost = 'top' AND boosts.statut = 'inactif' THEN 1
            WHEN boosts.type_boost = 'auto-remontee' AND boosts.statut = 'actif' THEN 2
            ELSE 3
        END, biens.datePublication DESC
        ");

        $biens = $query->select('biens.*')->paginate($perPage);

        // Séparer les annonces boostées en "top" et les autres
        $topBiens = $biens->filter(fn($bien) => optional($bien->boost)->type_boost === 'top');
        $autresBiens = $biens->filter(fn($bien) => optional($bien->boost)->type_boost !== 'top');

        return view('welcome', compact('topBiens', 'autresBiens', 'search', 'biens'));
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
