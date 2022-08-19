<?php

namespace App\Application;

use App\Domain\Events\MemberJoinedLobby;
use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Models\LobbyId;
use App\Domain\Models\Member;
use App\Domain\Repositories\LobbyRepository;
use Illuminate\Support\Facades\Event;
use InvalidArgumentException;

class CreateMemberHandler
{
    public function __construct(
        private readonly LobbyRepository $repository,
    ) {
    }

    /**
     * @throws InvalidArgumentException|LobbyNotAllocatedException
     */
    public function execute(CreateMemberCommand $command): void
    {
        if (trim($command->name) === '') {
            throw new InvalidArgumentException('The name cannot be empty.');
        }

        $lobby = $this->repository->findById(LobbyId::fromString($command->lobby_id));

        $member = new Member(
            name: $command->name,
        );

        $lobby->addMember($member);

        $this->repository->save($lobby);

        Event::dispatch(new MemberJoinedLobby($lobby->id->__toString()));
    }
}
