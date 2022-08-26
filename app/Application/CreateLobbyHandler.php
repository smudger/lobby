<?php

namespace App\Application;

use App\Domain\Exceptions\ValidationException;
use App\Domain\Models\Lobby;
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
        $lobbyId = $this->repository->allocate();

        $lobby = Lobby::create($lobbyId);

        try {
            $lobby->createMember($command->member_name);
        } catch (ValidationException $e) {
            throw new ValidationException(['member_name' => ['The member name cannot be empty.']]);
        }

        $this->repository->save($lobby);

        return $lobby;
    }
}
