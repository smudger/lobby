<?php

namespace Tests\Unit;

use App\Application\CreateLobbyCommand;
use App\Application\CreateLobbyHandler;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Models\Member;
use App\Infrastructure\Events\InMemoryEventStore;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class CreateLobbyHandlerTest extends TestCase
{
    /** @test */
    public function it_creates_a_lobby(): void
    {
        $repository = new InMemoryLobbyRepository(new InMemoryEventStore());

        $handler = new CreateLobbyHandler($repository);

        $lobby = $handler->execute(new CreateLobbyCommand(member_name: 'Ayesha Nicole'));

        Assert::assertTrue($lobby->equals($repository->findById($lobby->id)));
    }

    /** @test */
    public function it_adds_the_initial_member_to_the_lobby(): void
    {
        $repository = new InMemoryLobbyRepository(new InMemoryEventStore());

        $handler = new CreateLobbyHandler($repository);

        $command = new CreateLobbyCommand(
            member_name: 'Ayesha Nicole',
        );
        $lobby = $handler->execute($command);

        Assert::assertTrue($lobby->equals($repository->findById($lobby->id)));

        Assert::assertCount(1, $lobby->members());
        Assert::assertTrue($lobby->members()[0]->equals(new Member('Ayesha Nicole')));
    }

    /** @test */
    public function it_saves_the_lobby_with_initial_member_to_the_repository(): void
    {
        $repository = new InMemoryLobbyRepository(new InMemoryEventStore());

        $handler = new CreateLobbyHandler($repository);

        $command = new CreateLobbyCommand(
            member_name: 'Ayesha Nicole',
        );
        $lobby = $handler->execute($command);

        $savedLobby = $repository->findById($lobby->id);
        Assert::assertCount(1, $savedLobby->members());
        Assert::assertTrue($savedLobby->members()[0]->equals(new Member('Ayesha Nicole')));
    }

    /** @test */
    public function it_throws_an_exception_if_the_member_name_is_empty(): void
    {
        $repository = new InMemoryLobbyRepository(new InMemoryEventStore());

        $command = new CreateLobbyCommand(
            member_name: '',
        );

        $handler = new CreateLobbyHandler($repository);

        try {
            $handler->execute($command);

            Assert::fail('No exception thrown despite empty name.');
        } catch (ValidationException $e) {
            Assert::assertEquals([
                'member_name' => ['The member name cannot be empty.'],
            ], $e->errors);
        }
    }
}
