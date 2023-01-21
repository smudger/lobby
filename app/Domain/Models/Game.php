<?php

namespace App\Domain\Models;

class Game
{
    public function __construct(
        public readonly string $description,
        public readonly string $name,
        public readonly string $slug,
    ) {
    }

    public function equals(mixed $other): bool
    {
        if (! ($other instanceof self)) {
            return false;
        }

        return $this->description === $other->description
            && $this->name === $other->name
            && $this->slug === $other->slug;
    }
}
