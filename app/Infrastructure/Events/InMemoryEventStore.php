<?php

namespace App\Infrastructure\Events;

use App\Domain\Events\DomainEvent;
use App\Domain\Events\EventStore;
use App\Domain\Events\StoredEvent;
use App\Domain\Models\AggregateId;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class InMemoryEventStore implements EventStore
{
    public function __construct(
        /** @var array<string, StoredEvent[]> */
        private array $events = [],
    ) {
    }

    public function findAllByAggregateId(AggregateId $id): array
    {
        /** @var StoredEvent[] */
        return Arr::get($this->events, $id->__toString(), []);
    }

    public function addAll(array $events): void
    {
        foreach ($events as $event) {
            $this->add($event);
        }
    }

    private function add(DomainEvent $event): void
    {
        $this->events[$event->aggregateId->__toString()][] = new StoredEvent(
            occurredAt: Carbon::now(),
            type: Str::snake(class_basename($event)),
            body: $event->body(),
        );
    }
}
