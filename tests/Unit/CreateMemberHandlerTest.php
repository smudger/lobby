<?php

namespace Tests\Unit;

use App\Application\CreateMemberCommand;
use App\Application\CreateMemberHandler;
use App\Domain\Events\MemberJoinedLobby;
use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Models\Lobby;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use Illuminate\Support\Facades\Event;
use InvalidArgumentException;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

class CreateMemberHandlerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Event::fake();
    }

    /** @test */
    public function it_adds_a_new_member_to_the_lobby(): void
    {
        $repository = new InMemoryLobbyRepository();
        $lobby = new Lobby($repository->allocate());

        $command = new CreateMemberCommand(
            lobby_id: $lobby->id->__toString(),
            name: 'Ayesha Nicole',
            socket_id: '123.456',
        );

        $handler = new CreateMemberHandler($repository);

        $handler->execute($command);

        $updatedLobby = $repository->findById($lobby->id);

        Assert::assertCount(1, $updatedLobby->members());
        $member = $updatedLobby->members()[0];

        Assert::assertEquals('123.456', $member->socketId);
        Assert::assertEquals('Ayesha Nicole', $member->name);
    }

    /** @test */
    public function it_dispatches_an_event(): void
    {
        $repository = new InMemoryLobbyRepository();
        $lobby = new Lobby($repository->allocate());

        $command = new CreateMemberCommand(
            lobby_id: $lobby->id->__toString(),
            name: 'Ayesha Nicole',
            socket_id: '123.456',
        );

        $handler = new CreateMemberHandler($repository);

        $handler->execute($command);

        Event::assertDispatched(MemberJoinedLobby::class);
    }

    /** @test */
    public function it_throws_an_exception_if_the_lobby_has_not_been_allocated(): void
    {
        $repository = new InMemoryLobbyRepository();

        $command = new CreateMemberCommand(
            lobby_id: 'AAAA',
            name: 'Ayesha Nicole',
            socket_id: '123.456',
        );

        $handler = new CreateMemberHandler($repository);

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
        $repository = new InMemoryLobbyRepository();
        $lobby = new Lobby($repository->allocate());

        $command = new CreateMemberCommand(
            lobby_id: $lobby->id->__toString(),
            name: '',
            socket_id: '123.456',
        );

        $handler = new CreateMemberHandler($repository);

        try {
            $handler->execute($command);

            Assert::fail('No exception thrown despite empty name.');
        } catch (InvalidArgumentException $e) {
            Assert::assertEquals('The name cannot be empty.', $e->getMessage());
        }
    }

    /** @test */
    public function it_throws_an_exception_if_the_socket_id_is_empty(): void
    {
        $repository = new InMemoryLobbyRepository();
        $lobby = new Lobby($repository->allocate());

        $command = new CreateMemberCommand(
            lobby_id: $lobby->id->__toString(),
            name: 'Ayesha Nicole',
            socket_id: '',
        );

        $handler = new CreateMemberHandler($repository);

        try {
            $handler->execute($command);

            Assert::fail('No exception thrown despite empty name.');
        } catch (InvalidArgumentException $e) {
            Assert::assertEquals('The socket id cannot be empty.', $e->getMessage());
        }
    }
}
