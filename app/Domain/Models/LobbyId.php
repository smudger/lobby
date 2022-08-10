<?php

namespace App\Domain\Models;

use Stringable;

class LobbyId implements Stringable
{
    public function __construct(
        public readonly string $id,
    ) {
    }

    public static function fromString(string $raw): self
    {
        return new self($raw);
    }

    public function equals(mixed $other): bool
    {
        if (! ($other instanceof self)) {
            return false;
        }

        return $this->id === $other->id;
    }

    public function __toString()
    {
        return $this->id;
    }
}
