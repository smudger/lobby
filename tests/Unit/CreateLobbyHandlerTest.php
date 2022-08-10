<?php

namespace Tests\Unit;

use App\Application\CreateLobbyHandler;
use App\Domain\Models\Lobby;
use App\Domain\Models\LobbyId;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class CreateLobbyHandlerTest extends TestCase
{
    /** @test */
    public function it_creates_a_lobby(): void
    {
        $repository = new InMemoryLobbyRepository();

        $handler = new CreateLobbyHandler($repository);

        $lobby = $handler->execute();

        Assert::assertTrue($lobby->equals($repository->findById($lobby->id)));
    }
}
