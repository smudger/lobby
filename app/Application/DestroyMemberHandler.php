<?php

namespace App\Application;

use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Exceptions\MemberNotFoundException;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Models\LobbyId;
use App\Domain\Models\Member;
use App\Domain\Repositories\LobbyRepository;

class DestroyMemberHandler
{
    public function __construct(
        private readonly LobbyRepository $repository,
    ) {
    }

    /** @throws LobbyNotAllocatedException|ValidationException|MemberNotFoundException */
    public function execute(DestroyMemberCommand $command): void
    {
        if (trim($command->name) === '') {
            throw new ValidationException([
                'name' => ['The name cannot be empty.'],
            ]);
        }

        $lobby = $this->repository->findById(LobbyId::fromString($command->lobby_id));

        $lobby->removeMember(new Member($command->name));

        $this->repository->save($lobby);
    }
}
