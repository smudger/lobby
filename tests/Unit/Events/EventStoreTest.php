<?php

namespace Tests\Unit\Events;

use App\Domain\Events\EventStore;
use App\Domain\Events\MemberLeftLobby;
use App\Domain\Events\StoredEvent;
use App\Domain\Models\LobbyId;
use App\Domain\Models\Member;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Assert;

trait EventStoreTest
{
    abstract public function getEventStore(): EventStore;

    /** @test */
    public function an_array_of_events_can_be_added_to_the_event_store(): void
    {
        Carbon::setTestNow(now()->micros(0));

        $events = [
            new MemberLeftLobby(
                LobbyId::fromString('AAAA'),
                new Member(id: 1, name: 'Ayesha Nicole', joinedAt: Carbon::now()),
            ),
            new MemberLeftLobby(
                LobbyId::fromString('AAAA'),
                new Member(id: 2, name: 'Kim Petras', joinedAt: Carbon::now()),
            ),
            new MemberLeftLobby(
                LobbyId::fromString('BBBB'),
                new Member(id: 1, name: 'Slayyyter', joinedAt: Carbon::now()),
            ),
        ];

        $eventStore = $this->getEventStore();

        $eventStore->addAll($events);

        $aEvents = $eventStore->findAllByAggregateId(LobbyId::fromString('AAAA'));
        $bEvents = $eventStore->findAllByAggregateId(LobbyId::fromString('BBBB'));
        Assert::assertCount(2, $aEvents);
        Assert::assertCount(1, $bEvents);

        Assert::assertEquals([
            new StoredEvent(
                Carbon::now(),
                'member_left_lobby',
                ['id' => 1, 'name' => 'Ayesha Nicole'],
            ),
            new StoredEvent(
                Carbon::now(),
                'member_left_lobby',
                ['id' => 2, 'name' => 'Kim Petras'],
            ),
        ], $aEvents);

        Assert::assertEquals([
            new StoredEvent(
                Carbon::now(),
                'member_left_lobby',
                ['id' => 1, 'name' => 'Slayyyter'],
            ),
        ], $bEvents);
    }

    /** @test */
    public function it_returns_an_empty_array_for_aggregates_which_have_no_events(): void
    {
        $eventStore = $this->getEventStore();

        Assert::assertEmpty($eventStore->findAllByAggregateId(LobbyId::fromString('AAAA')));
    }
}
