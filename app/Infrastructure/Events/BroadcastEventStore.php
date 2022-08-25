<?php

namespace App\Infrastructure\Events;

use App\Domain\Events\EventStore;
use App\Domain\Models\AggregateId;
use App\Infrastructure\Broadcasts\DomainBroadcast;
use Illuminate\Support\Facades\Event;

class BroadcastEventStore implements EventStore
{
    public function __construct(
        private readonly EventStore $eventStore,
    ) {
    }

    public function findAllByAggregateId(AggregateId $id): array
    {
        return $this->eventStore->findAllByAggregateId($id);
    }

    public function addAll(array $events): void
    {
        $this->eventStore->addAll($events);

        foreach ($events as $event) {
            Event::dispatch(new DomainBroadcast($event));
        }
    }
}
