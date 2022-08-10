<?php

namespace App\Domain\Repositories;

use App\Domain\Exceptions\IdGenerationException;
use App\Domain\Exceptions\LobbyNotFoundException;
use App\Domain\Models\Lobby;
use App\Domain\Models\LobbyId;

interface LobbyRepository
{
    /**
     *  @throws IdGenerationException
     */
    public function nextId(): LobbyId;

    public function save(Lobby $lobby): void;

    /**
     * @throws LobbyNotFoundException
     */
    public function findById(LobbyId $id): Lobby;
}
