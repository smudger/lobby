<?php

namespace Tests\Unit;

use App\Application\DestroyMemberCommand;
use App\Application\DestroyMemberHandler;
use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Exceptions\MemberNotFoundException;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Models\Lobby;
use App\Domain\Models\Member;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class DestroyMemberHandlerTest extends TestCase
{
    /** @test */
    public function it_removes_a_member_from_a_lobby(): void
    {
        $repository = new InMemoryLobbyRepository();
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
    public function it_throws_an_exception_if_the_lobby_has_not_been_allocated(): void
    {
        $repository = new InMemoryLobbyRepository();

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
        $repository = new InMemoryLobbyRepository();
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
        $repository = new InMemoryLobbyRepository();
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
