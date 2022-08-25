<?php

namespace App\Domain\Events;

use App\Domain\Models\AggregateId;

abstract class DomainEvent
{
    /** @return array<string, string> */
    abstract public function body(): array;

    public function __construct(
        public readonly AggregateId $aggregateId,
    ) {
    }
}
