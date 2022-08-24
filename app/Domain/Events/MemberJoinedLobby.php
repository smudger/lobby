<?php

namespace App\Domain\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MemberJoinedLobby implements ShouldBroadcast
{
    public function __construct(private readonly string $lobbyId)
    {
    }

    public function broadcastAs(): string
    {
        return 'lobby.members.joined';
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('lobby.'.$this->lobbyId);
    }
}
