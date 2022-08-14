<?php

namespace App\Application;

use App\Domain\Models\Lobby;
use App\Domain\Repositories\LobbyRepository;

class CreateLobbyHandler
{
    public function __construct(
        private readonly LobbyRepository $repository,
    ) {
    }

    public function execute(): Lobby
    {
        $lobbyId = $this->repository->allocate();

        $lobby = new Lobby($lobbyId);

        return $lobby;
    }
}
