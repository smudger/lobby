<?php

namespace App\Domain\Events;

use App\Domain\Models\AggregateId;

interface EventStore
{
    /** @return StoredEvent[] */
    public function findAllByAggregateId(AggregateId $id): array;

    /** @param  DomainEvent[]  $events */
    public function addAll(array $events): void;
}
