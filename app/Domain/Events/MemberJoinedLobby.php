<?php

namespace App\Domain\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MemberJoinedLobby implements ShouldBroadcast
{
    public function __construct(private readonly string $lobbyCode)
    {
    }

    public function broadcastAs()
    {
        return 'lobby.members.joined';
    }

    public function broadcastOn()
    {
        return new Channel('lobby.'.$this->lobbyCode);
    }
}
