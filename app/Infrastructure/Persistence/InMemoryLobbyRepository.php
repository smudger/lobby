<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Exceptions\IdGenerationException;
use App\Domain\Exceptions\LobbyNotFoundException;
use App\Domain\Models\Lobby;
use App\Domain\Models\LobbyId;
use App\Domain\Repositories\LobbyRepository;
use Faker\Factory as FakerFactory;
use Faker\Generator as Faker;

class InMemoryLobbyRepository implements LobbyRepository
{
    private Faker $faker;

    /** @var Lobby[] */
    private array $lobbies = [];

    public function __construct(
        /** @var string[] */
        private array $assignedIds = [],
    ) {
        $this->faker = FakerFactory::create();
    }

    public function nextId(): LobbyId
    {
        if (count($this->assignedIds) === (26 ** 4)) {
            throw new IdGenerationException('No more lobby IDs available.');
        }

        do {
            $rawId = strtoupper($this->faker->lexify('????'));
        } while (in_array($rawId, $this->assignedIds, true));

        $this->assignedIds[] = $rawId;

        return LobbyId::fromString($rawId);
    }

    public function save(Lobby $lobby): void
    {
        $this->lobbies[$lobby->id->__toString()] = $lobby;
    }

    public function findById(LobbyId $id): Lobby
    {
        if (! array_key_exists($id->__toString(), $this->lobbies)) {
            throw new LobbyNotFoundException('A lobby with the given ID could not be found.');
        }

        return $this->lobbies[$id->__toString()];
    }
}
