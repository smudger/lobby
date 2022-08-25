<?php

namespace Tests\Unit;

use App\Application\DestroyMemberCommand;
use App\Application\DestroyMemberHandler;
use App\Domain\Events\StoredEvent;
use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Exceptions\MemberNotFoundException;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Models\Lobby;
use App\Domain\Models\Member;
use App\Infrastructure\Events\InMemoryEventStore;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class DestroyMemberHandlerTest extends TestCase
{
    /** @test */
    public function it_removes_a_member_from_a_lobby(): void
    {
        $repository = new InMemoryLobbyRepository(new InMemoryEventStore());
        $lobby = new Lobby($repository->allocate());

        $ayesha = new Member('Ayesha Nicole');
        $kim = new Member('Kim Petras');
        $lobby->addMember($ayesha);
        $lobby->addMember($kim);
        $repository->save($lobby);

        $command = new DestroyMemberCommand(
            lobby_id: $lobby->id->__toString(),
            name: 'Ayesha Nicole',
        );

        $handler = new DestroyMemberHandler($repository);

        $handler->execute($command);

        $updatedLobby = $repository->findById($lobby->id);

        Assert::assertCount(1, $updatedLobby->members());
        $member = $updatedLobby->members()[0];

        Assert::assertEquals('Kim Petras', $member->name);
    }

    /** @test */
    public function it_records_the_event_to_the_event_store(): void
    {
        Carbon::setTestNow(now());
        $eventStore = new InMemoryEventStore();
        $repository = new InMemoryLobbyRepository(eventStore: $eventStore);
        $lobby = new Lobby($repository->allocate());

        $ayesha = new Member('Ayesha Nicole');
        $lobby->addMember($ayesha);
        $repository->save($lobby);

        $command = new DestroyMemberCommand(
            lobby_id: $lobby->id->__toString(),
            name: 'Ayesha Nicole',
        );

        $handler = new DestroyMemberHandler($repository);

        $handler->execute($command);

        $events = $eventStore->findAllByAggregateId($lobby->id);

        /** @var StoredEvent $event */
        $event = Arr::last($events);
        Assert::assertTrue(now()->equalTo($event->occurredAt));
        Assert::assertEquals('member_left_lobby', $event->type);
        Assert::assertEquals([
            'name' => 'Ayesha Nicole',
        ], $event->body);
    }

    /** @test */
    public function it_throws_an_exception_if_the_lobby_has_not_been_allocated(): void
    {
        $repository = new InMemoryLobbyRepository(new InMemoryEventStore());

        $command = new DestroyMemberCommand(
            lobby_id: 'AAAA',
            name: 'Ayesha Nicole',
        );

        $handler = new DestroyMemberHandler($repository);

        try {
            $handler->execute($command);

            Assert::fail('No exception thrown despite lobby not allocated.');
        } catch (LobbyNotAllocatedException $e) {
            Assert::assertEquals('The lobby with the given ID has not been allocated.', $e->getMessage());
        }
    }

    /** @test */
    public function it_throws_an_exception_if_the_name_is_empty(): void
    {
        $repository = new InMemoryLobbyRepository(new InMemoryEventStore());
        $lobby = new Lobby($repository->allocate());

        $command = new DestroyMemberCommand(
            lobby_id: $lobby->id->__toString(),
            name: '',
        );

        $handler = new DestroyMemberHandler($repository);

        try {
            $handler->execute($command);

            Assert::fail('No exception thrown despite empty name.');
        } catch (ValidationException $e) {
            Assert::assertEquals('The given data was invalid.', $e->getMessage());
            Assert::assertEquals([
                'name' => ['The name cannot be empty.'],
            ], $e->errors);
        }
    }

    /** @test */
    public function it_throws_an_exception_if_the_member_could_not_be_found(): void
    {
        $repository = new InMemoryLobbyRepository(new InMemoryEventStore());
        $lobby = new Lobby($repository->allocate());

        $command = new DestroyMemberCommand(
            lobby_id: $lobby->id->__toString(),
            name: 'Ayesha Nicole',
        );

        $handler = new DestroyMemberHandler($repository);

        try {
            $handler->execute($command);

            Assert::fail('No exception thrown despite unknown member.');
        } catch (MemberNotFoundException $e) {
            Assert::assertEquals('The member with the given name could not be found.', $e->getMessage());
        }
    }
}
