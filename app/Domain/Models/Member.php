<?php

namespace App\Domain\Models;

class Member
{
    public function __construct(
        public readonly string $name,
    ) {
    }

    public function equals(mixed $other): bool
    {
        if (! ($other instanceof self)) {
            return false;
        }

        return $this->name === $other->name;
    }
}
