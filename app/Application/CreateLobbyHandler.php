<?php

namespace App\Application;

use App\Domain\Exceptions\ValidationException;
use App\Domain\Models\Lobby;
use App\Domain\Models\Member;
use App\Domain\Repositories\LobbyRepository;

class CreateLobbyHandler
{
    public function __construct(
        private readonly LobbyRepository $repository,
    ) {
    }

    /** @throws ValidationException */
    public function execute(CreateLobbyCommand $command): Lobby
    {
        if (trim($command->member_name) === '') {
            throw new ValidationException([
                'member_name' => ['The member name cannot be empty.'],
            ]);
        }

        $lobbyId = $this->repository->allocate();

        $lobby = Lobby::create($lobbyId);

        $member = new Member(name: $command->member_name);
        $lobby->addMember($member);

        $this->repository->save($lobby);

        return $lobby;
    }
}
