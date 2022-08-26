<?php

namespace App\Domain\Events;

use Illuminate\Support\Carbon;

class StoredEvent
{
    public function __construct(
        public readonly Carbon $occurredAt,
        public readonly string $type,
        /** @var array<string, int|string> */
        public readonly array $body,
    ) {
    }
}
