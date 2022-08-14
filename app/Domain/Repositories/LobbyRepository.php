<?php

namespace App\Domain\Repositories;

use App\Domain\Exceptions\LobbyAllocationException;
use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Exceptions\NoMoreLobbiesException;
use App\Domain\Models\Lobby;
use App\Domain\Models\LobbyId;

interface LobbyRepository
{
    /**
     * @throws NoMoreLobbiesException
     * @throws LobbyAllocationException
     */
    public function allocate(): LobbyId;

    /**
     * @throws LobbyNotAllocatedException
     */
    public function findById(LobbyId $id): Lobby;

    /**
     * @throws LobbyNotAllocatedException
     */
    public function save(Lobby $lobby): void;
}
