<?php

namespace App\Infrastructure\Events;

use App\Domain\Events\DomainEvent;
use App\Domain\Events\EventStore;
use App\Domain\Events\StoredEvent;
use App\Domain\Models\AggregateId;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use stdClass;

class SqlEventStore implements EventStore
{
    public function findAllByAggregateId(AggregateId $id): array
    {
        /** @var StoredEvent[] */
        return DB::table('events')
            ->where('aggregate_id', $id->__toString())
            ->get()
            ->map(fn (stdClass $row) => new StoredEvent(
                occurredAt: Carbon::parse($row->occurred_at),
                type: $row->type,
                body: json_decode($row->body, true),
            ))
            ->values()
            ->toArray();
    }

    public function addAll(array $events): void
    {
        $rows = collect($events)
            ->map(fn (DomainEvent $event) => [
                'aggregate_id' => $event->aggregateId->__toString(),
                'type' => Str::snake(class_basename($event)),
                'body' => json_encode($event->body()),
                'occurred_at' => Carbon::now(),
            ])
            ->toArray();

        DB::table('events')
            ->insert($rows);
    }
}
