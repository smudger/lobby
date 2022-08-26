<?php

namespace Tests\Unit\Events;

use App\Domain\Events\EventStore;
use App\Domain\Events\MemberLeftLobby;
use App\Domain\Models\LobbyId;
use App\Domain\Models\Member;
use App\Infrastructure\Broadcasts\DomainBroadcast;
use App\Infrastructure\Events\BroadcastEventStore;
use App\Infrastructure\Events\InMemoryEventStore;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class BroadcastEventStoreTest extends TestCase
{
    use EventStoreTest;

    public function getEventStore(): EventStore
    {
        return new BroadcastEventStore(new InMemoryEventStore());
    }

    /** @test */
    public function it_broadcasts_all_the_events_that_are_added(): void
    {
        Event::fake();
        Carbon::setTestNow(now());

        $first = new MemberLeftLobby(
            LobbyId::fromString('AAAA'),
            new Member(id: 1, name: 'Ayesha Nicole', joinedAt: Carbon::now()),
        );
        $second = new MemberLeftLobby(
            LobbyId::fromString('BBBB'),
            new Member(id: 1, name: 'Kim Petras', joinedAt: Carbon::now()),
        );

        $this->getEventStore()->addAll([$first, $second]);

        Event::assertDispatched(DomainBroadcast::class, function (DomainBroadcast $broadcast) use ($first) {
            return $broadcast->broadcastOn()->name === 'private-lobby.AAAA'
                && $broadcast->broadcastWith() === array_merge([
                    'occurred_at' => Carbon::now()->toIso8601ZuluString(),
                ],
                    $first->body())
                && $broadcast->broadcastAs() === 'member_left_lobby';
        });
        Event::assertDispatched(DomainBroadcast::class, function (DomainBroadcast $broadcast) use ($second) {
            return $broadcast->broadcastOn()->name === 'private-lobby.BBBB'
                && $broadcast->broadcastWith() === array_merge([
                    'occurred_at' => Carbon::now()->toIso8601ZuluString(),
                ],
                    $second->body())
                && $broadcast->broadcastAs() === 'member_left_lobby';
        });
    }
}
