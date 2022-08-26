<?php

namespace App\Application;

use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Models\LobbyId;
use App\Domain\Repositories\LobbyRepository;

class CreateMemberHandler
{
    public function __construct(
        private readonly LobbyRepository $repository,
    ) {
    }

    /**
     * @throws ValidationException|LobbyNotAllocatedException
     */
    public function execute(CreateMemberCommand $command): int
    {
        $lobby = $this->repository->findById(LobbyId::fromString($command->lobby_id));

        $id = $lobby->createMember($command->name);

        $this->repository->save($lobby);

        return $id;
    }
}
