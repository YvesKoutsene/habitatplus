<?php

namespace App\Http\Controllers\Estate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Signalement;
use App\Models\Bien;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AnnonceBloqueeMail;
use App\Mail\AnnonceReactiveeMail;

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

    //Fonction permettant d'afficher la liste des annonces signalées pour un super ou admin
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

        $query = Signalement::with(['bien', 'user'])
            ->selectRaw('id_bien, COUNT(*) as total_signals, MAX(created_at) as last_signal_date')
            ->whereHas('bien', function ($q) {
                $q->whereIn('statut', ['publié', 'bloqué', 'terminé']);
            })
            ->groupBy('id_bien')
            ->orderByDesc('last_signal_date');

        if ($search) {
            $query->whereHas('bien', function ($q) use ($search) {
                $q->where('titre', 'LIKE', '%' . strtolower($search) . '%')
                    ->orWhere('lieu', 'LIKE', '%' . strtolower($search) . '%')
                    ->orWhere('type_offre', 'LIKE', '%' . strtolower($search) . '%')
                    ->orWhere('description', 'LIKE', '%' . strtolower($search) . '%');
            });
        }

        $signalements = $query->paginate($perPage);

        return view('admin.pages.reporting.index', compact('signalements', 'search', 'perPage'));
    }

    /**
     * Display the specified resource.
     */

    //Fonction permettant d'afficher les signalements d'une annonce
    public function show(Request $request, $id)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

        $bien = Bien::findOrFail($id);

        $query = Signalement::with('user')
            ->where('id_bien', $id)
            ->orderByDesc('created_at');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('motif', 'LIKE', '%' . strtolower($search) . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'LIKE', '%' . strtolower($search) . '%');
                    });
            });
        }

        $signalements = $query->paginate($perPage);
        return view('admin.pages.reporting.show', compact('bien', 'signalements', 'search', 'perPage'));
    }

    //Fonction permettant à un super ou admin de bloquer une annonce
    public function block(Request $request, $id)
    {
        $request->validate([
            'motif' => 'required|string|max:255',
        ],[
           'motif.required' => "Le motif de bloquage de l'annonce est requis"
        ]);

        $annonce = Bien::find($id);

        if (!$annonce) {
            return redirect()->back()->with('error', 'Annonce introuvable.');
        }

        if ($annonce->statut !== 'publié') {
            return redirect()->back()->with('error', 'Seules les annonces publiées peuvent être bloquées.');
        }

        $annonce->statut = 'bloqué';
        $annonce->motifBlocage = $request->motif;
        Mail::to($annonce->user->email)->send(new AnnonceBloqueeMail($annonce));
        $annonce->save();

        return redirect()->back()->with('success', "Annonce bloquée avec succès et notifiée au propriétaire : {$annonce->user->name}");
    }

    //Fonction pour réactiver une annonce bloquée par un super ou admin
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
        $annonce->motifBlocage = null;
        $annonce->datePublication = Carbon::now();
        Mail::to($annonce->user->email)->send(new AnnonceReactiveeMail($annonce));
        $annonce->save();

        return redirect()->back()->with('success', "Annonce réactivée avec succès et notifiée au propriétaire : {$annonce->user->name}");
    }

    /**
     * Show the form for creating a new resource.
     */

    //Fonction permettant d'afficher les signalements d'une annonce pour un super ou admin
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
