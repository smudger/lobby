<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Exceptions\NoMoreLobbiesException;
use App\Domain\Models\Lobby;
use App\Domain\Models\LobbyId;
use App\Domain\Repositories\LobbyRepository;
use Faker\Factory as FakerFactory;
use Faker\Generator as Faker;

class InMemoryLobbyRepository implements LobbyRepository
{
    private Faker $faker;

    public function __construct(
        /** @var Lobby[] */
        private array $lobbies = [],
    ) {
        $this->faker = FakerFactory::create();
    }

    public function findById(LobbyId $id): Lobby
    {
        $this->checkLobbyIdIsAllocated($id);

        $storedLobby = $this->lobbies[$id->__toString()];

        return new Lobby(
            id: $storedLobby->id,
            members: $storedLobby->members(),
        );
    }

    public function allocate(): LobbyId
    {
        if (count($this->lobbies) === (26 ** 4)) {
            throw new NoMoreLobbiesException();
        }

        do {
            $rawId = strtoupper($this->faker->lexify());
        } while (in_array($rawId, array_keys($this->lobbies), true));

        $lobby = new Lobby(LobbyId::fromString($rawId));
        $this->lobbies[$rawId] = $lobby;

        return $lobby->id;
    }

    public function save(Lobby $lobby): void
    {
        $this->checkLobbyIdIsAllocated($lobby->id);

        $this->lobbies[$lobby->id->__toString()] = $lobby;
    }

    private function checkLobbyIdIsAllocated(LobbyId $id): void
    {
        if (! array_key_exists($id->__toString(), $this->lobbies)) {
            throw new LobbyNotAllocatedException();
        }
    }
}
