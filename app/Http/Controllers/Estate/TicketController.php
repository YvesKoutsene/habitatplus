<?php

namespace App\Http\Controllers\Estate;

use App\Http\Controllers\Controller;
use App\Models\CategorieTicket;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    //Fonction permettant de renvoyer la liste des tickets ouverts
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

        $query = Ticket::with(['categorie' , 'user'])
            ->where('statut', 'ouvert')
            ->orderBy('created_at', 'asc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(titre) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        $tickets = $query->paginate($perPage);

        return view('admin.pages.ticket.index', compact('tickets', 'search', 'perPage'));
    }

    //Fonction permettant de renvoyer la liste des tickets clôturés
    public function index02(Request $request)
    {
        $search = $request->input('search', '');
        $perPage = $request->input('perPage', 10);

        $query = Ticket::with(['categorie' , 'user'])
            ->where('statut', 'clôturé')
            ->orderBy('created_at', 'asc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereRaw('LOWER(titre) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        $tickets = $query->paginate($perPage);

        return view('admin.pages.ticket.index02', compact('tickets', 'search', 'perPage'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = CategorieTicket::where('statut', 'actif')->get();
        return view('abonné.pages.faq.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */

    //Fonction permettant d'ouvrir un ticket (Signaler un problème)
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'id_categorie' => 'required|exists:categorie_tickets,id',
            'piece_jointe' => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:2048',
        ],[
            'id_categorie.required' => 'Veuillez sélectionner une catégorie',
            'titre.required' => 'Le titre est obligatoire',
            'description.required' => 'La description est obligatoire'
        ]);

        $filePath = null;

        if ($request->hasFile('piece_jointe')) {
            $piece_jointe = $request->file('piece_jointe');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $piece_jointe->getClientOriginalName());
            $filePath = $piece_jointe->storeAs('images/tickets', $fileName, 'public');
        }

        Ticket::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'piece_jointe' => $filePath ? Storage::url($filePath) : null,
            'statut' => 'ouvert',
            'id_user' => Auth::id(),
            'id_categorie' => $request->id_categorie,
        ]);

        return redirect()->route('acceuil')->with('success', 'Vous venez de signaler un problème.');
    }

    //Fonction permettant de clôturer un ticket ouvert
    public function close($id)
    {
        $ticket = Ticket::find($id);
        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket introuvable.');
        }

        if ($ticket->statut !== 'ouvert') {
            return redirect()->back()->with('error', 'Seules les tickets ouverts peuvent être clôturés.');
        }

        $ticket->statut = 'clôturé';
        $ticket->save();

        return redirect()->back()->with('success', 'Ticket clôturé avec succès.');
    }

    /**
     * Display the specified resource.
     */

    //Fonction permettant d'afficher les details d'un ticket
    public function show($id)
    {
        $ticket = Ticket::with(['user','categorie'])->findOrFail($id);

        if (!$ticket) {
            return redirect()->back()->with('error', 'Ticket introuvable.');
        }

        return view('admin.pages.ticket.show', compact('ticket'));
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
