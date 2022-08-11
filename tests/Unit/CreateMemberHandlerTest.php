<?php

namespace Tests\Unit;

use App\Application\CreateMemberCommand;
use App\Application\CreateMemberHandler;
use App\Domain\Events\MemberJoinedLobby;
use App\Domain\Exceptions\LobbyNotAllocatedException;
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
    public function it_dispatches_an_event(): void
    {
        $repository = new InMemoryLobbyRepository();
        $lobby = $repository->allocate();

        $command = new CreateMemberCommand(
            lobby_id: $lobby->id->__toString(),
            name: 'Ayesha Nicole',
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
        $lobby = $repository->allocate();

        $command = new CreateMemberCommand(
            lobby_id: $lobby->id->__toString(),
            name: '',
        );

        $handler = new CreateMemberHandler($repository);

        try {
            $handler->execute($command);

            Assert::fail('No exception thrown despite empty name.');
        } catch (InvalidArgumentException $e) {
            Assert::assertEquals('The name cannot be empty.', $e->getMessage());
        }
    }
}
