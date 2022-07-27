<?php

namespace App\Lobby\Events;

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

    public function broadcastAs() {
        return 'lobby.members.joined';
    }

    public function broadcastOn()
    {
        return new Channel('lobby.'.$this->lobbyCode);
    }
}
