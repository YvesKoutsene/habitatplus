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
                //return view('dashboard');
                return redirect()->route('profile.edit');
            } elseif ($typeUser == 2) {

                $user = Auth::user();
                $biens = $user->biens()
                    ->where(function($query) {
                        $query->where('statut', 'brouillon')
                            ->orWhere('statut', 'publié')
                            ->orWhere('statut', 'republié')
                            ->orWhere('statut', 'terminé');
                    })
                    ->orderBy('updated_at', 'desc')
                    ->get();

                return view('abonné.pages.auth.dashboard',compact('biens'));

            } else {
                return redirect()->back();
            }
        }

        return redirect('acceuil');
    }

    //Fonction pour taper sur la route "/"
    public function indexHome(){
        $biens = Bien::with(['categorieBien'])->where(function($query) {
                $query->where('statut', 'publié')
                    ->orWhere('statut', 'republié');
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('welcome', compact('biens'));
    }

}
