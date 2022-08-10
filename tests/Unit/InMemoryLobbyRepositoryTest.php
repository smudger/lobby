<?php

namespace Tests\Unit;

use App\Domain\Exceptions\NoMoreLobbiesException;
use App\Domain\Models\Lobby;
use App\Domain\Models\LobbyId;
use App\Domain\Repositories\LobbyRepository;
use App\Infrastructure\Persistence\InMemoryLobbyRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class InMemoryLobbyRepositoryTest extends TestCase
{
    use LobbyRepositoryTest;

    protected function getRepository(): LobbyRepository
    {
        return new InMemoryLobbyRepository();
    }

    /** @test */
    public function it_throws_an_exception_if_there_are_no_more_lobbies_available(): void
    {
        /** @var Lobby[] $fullLobbiesArray */
        $fullLobbiesArray = collect($this->generateAllIdsOfLength(4))
            ->mapWithKeys(fn (string $id) => [
                $id => new Lobby(LobbyId::fromString($id)),
            ])
            ->toArray();

        $repository = new InMemoryLobbyRepository($fullLobbiesArray);

        try {
            $repository->allocate();

            Assert::fail('No exception thrown despite generation failure.');
        } catch (NoMoreLobbiesException $e) {
            Assert::assertEquals('There are currently no lobbies available for allocation.', $e->getMessage());
        }
    }
}
