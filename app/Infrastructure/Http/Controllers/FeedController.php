<?php

namespace App\Infrastructure\Http\Controllers;

use App\Domain\Events\EventStore;
use App\Domain\Events\StoredEvent;
use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Models\LobbyId;
use App\Domain\Models\Member;
use App\Domain\Repositories\LobbyRepository;
use Inertia\Inertia;
use Inertia\Response;

class FeedController
{
    public function index(
        string $id,
        LobbyRepository $repository,
        EventStore $eventStore,
    ): Response {
        try {
            $lobby = $repository->findById(LobbyId::fromString($id));
        } catch (LobbyNotAllocatedException) {
            abort(404);
        }

        return Inertia::render('Feed/Index', [
            'lobby' => [
                'id' => $lobby->id->__toString(),
                'members' => collect($lobby->members())
                    ->map(fn (Member $member) => [
                        'name' => $member->name,
                    ])
                    ->toArray(),
            ],
            'events' => collect($eventStore->findAllByAggregateId($lobby->id))
                ->map(fn (StoredEvent $event) => [
                    'occurred_at' => $event->occurredAt->toIso8601ZuluString(),
                    'type' => $event->type,
                    'body' => $event->body,
                ])
                ->toArray(),
        ]);
    }
}
