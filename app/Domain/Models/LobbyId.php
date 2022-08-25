<?php

namespace App\Domain\Models;

class LobbyId implements AggregateId
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

    public function __toString(): string
    {
        return $this->id;
    }
}
