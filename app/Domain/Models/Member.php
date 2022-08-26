<?php

namespace App\Domain\Models;

use Illuminate\Support\Carbon;

class Member
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly Carbon $joinedAt,
    ) {
    }

    public function equals(mixed $other): bool
    {
        if (! ($other instanceof self)) {
            return false;
        }

        return $this->name === $other->name
            && $this->id === $other->id
            && $this->joinedAt->equalTo($other->joinedAt);
    }
}
