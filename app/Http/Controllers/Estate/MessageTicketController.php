<?php

namespace App\Http\Controllers\Estate;

use App\Events\PusherBroadcastTicket;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\MessageSent;
use App\Models\MessageTicket;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class MessageTicketController extends Controller
{
    //Fonction pour renvoyer la page de message d'un ticket
    public function index($id)
    {
        $ticket = Ticket::with('messages.user')->find($id);

        if (!$ticket) {
            return redirect()->route('tickets.index')->with('error', 'Ticket introuvable.');
        }

        //New
        $messagesByDate = $ticket->messages->groupBy(function($message) {
            return \Carbon\Carbon::parse($message->created_at)->format('Y-m-d');
        });

        return view('admin.pages.ticket.chat', compact('ticket', 'messagesByDate'));
    }

    //Fonction pour envoyer un message d'un ticket
    public function sendMessage(Request $request, $ticketId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $messageTicket = MessageTicket::create([
            'user_id' => auth()->id(),
            'ticket_id' => $ticketId,
            'message' => $request->message,
        ]);

        broadcast(new PusherBroadcastTicket($messageTicket));

        return response()->json([
            'message' => $messageTicket->message,
            'user' => auth()->user()->email,
        ]);
    }

    //Fonction pour recevoir un message d'un ticket
    public function receiveMessage($ticketId)
    {
        $messages = MessageTicket::where('ticket_id', $ticketId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }
}
