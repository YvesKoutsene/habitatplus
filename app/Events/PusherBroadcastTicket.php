<?php

namespace App\Events;

use App\Models\MessageTicket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PusherBroadcastTicket implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public MessageTicket $message;

    public function __construct(MessageTicket $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return ['chat.' . $this->message->ticket_id];
    }

    public function broadcastAs()
    {
        return 'chat';
    }

    public function broadcastWith()
    {
        Log::info("Événement diffusé pour le ticket " . $this->message->ticket_id);

        return [
            'message' => $this->message->message,
            'timestamp' => $this->message->created_at->format('H:i'),
            'user_id' => $this->message->user->id,
            'user_email' => $this->message->user->email,
            'user_role' => $this->message->user->role ?? 'Super Admin',
            'user_photo' => $this->message->user->photo_profil ?? asset('default-avatar.png'),
        ];
    }
}
