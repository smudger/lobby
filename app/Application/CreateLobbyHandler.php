<?php

namespace App\Application;

use App\Domain\Models\Lobby;
use App\Domain\Models\Member;
use App\Domain\Repositories\LobbyRepository;
use InvalidArgumentException;

class CreateLobbyHandler
{
    public function __construct(
        private readonly LobbyRepository $repository,
    ) {
    }

    public function execute(CreateLobbyCommand $command): Lobby
    {
        if (trim($command->member_name) === '') {
            throw new InvalidArgumentException('The member name cannot be empty.');
        }

        $lobbyId = $this->repository->allocate();

        $lobby = new Lobby($lobbyId);

        $member = new Member(name: $command->member_name);
        $lobby->addMember($member);

        $this->repository->save($lobby);

        return $lobby;
    }
}
