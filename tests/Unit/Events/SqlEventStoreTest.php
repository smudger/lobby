<?php

namespace Tests\Unit\Events;

use App\Domain\Events\EventStore;
use App\Infrastructure\Events\SqlEventStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SqlEventStoreTest extends TestCase
{
    use RefreshDatabase, EventStoreTest;

    public function getEventStore(): EventStore
    {
        return new SqlEventStore();
    }
}
