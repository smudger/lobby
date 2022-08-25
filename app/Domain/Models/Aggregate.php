<?php

namespace App\Domain\Models;

use App\Domain\Events\DomainEvent;

class Aggregate
{
    public function __construct(
        /** @var DomainEvent[] */
        private array $events = [],
    ) {
    }

    final protected function recordEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    /** @return DomainEvent[] */
    public function events(): array
    {
        return $this->events;
    }

    public function flushEvents(): void
    {
        $this->events = [];
    }
}
