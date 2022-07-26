<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class MemberJoinedLobby implements ShouldBroadcast
{
    use Dispatchable;

    public function __construct(private readonly string $lobbyCode)
    {}

    public function broadcastOn()
    {
        return new Channel('lobby.'.$this->lobbyCode);
    }
}
