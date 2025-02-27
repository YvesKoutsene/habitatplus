<?php

namespace App\Events;

use App\Models\MessageTicket;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PusherBroadcastTicket implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public MessageTicket $messageTicket;

    public function __construct(MessageTicket $messageTicket)
    {
        $this->messageTicket = $messageTicket;
    }

    public function broadcastOn()
    {
        return new \Illuminate\Broadcasting\PrivateChannel('chat.' . $this->messageTicket->ticket_id);
    }

    public function broadcastAs()
    {
        return 'chat';
    }
}
