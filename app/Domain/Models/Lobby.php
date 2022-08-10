<?php

namespace App\Domain\Models;

class Lobby
{
    public function __construct(
        public readonly LobbyId $id,
    ) {
    }

    public function equals(mixed $other): bool
    {
        if (! ($other instanceof self)) {
            return false;
        }

        return $this->id->equals($other->id);
    }
}
