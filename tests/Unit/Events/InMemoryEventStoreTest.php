<?php

namespace Tests\Unit\Events;

use App\Domain\Events\EventStore;
use App\Infrastructure\Events\InMemoryEventStore;
use PHPUnit\Framework\TestCase;

class InMemoryEventStoreTest extends TestCase
{
    use EventStoreTest;

    public function getEventStore(): EventStore
    {
        return new InMemoryEventStore();
    }
}
