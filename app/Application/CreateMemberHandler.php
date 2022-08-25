<?php

namespace App\Application;

use App\Domain\Exceptions\LobbyNotAllocatedException;
use App\Domain\Exceptions\ValidationException;
use App\Domain\Models\LobbyId;
use App\Domain\Models\Member;
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
    public function execute(CreateMemberCommand $command): void
    {
        if (trim($command->name) === '') {
            throw new ValidationException([
                'name' => ['The name cannot be empty.'],
            ]);
        }

        $lobby = $this->repository->findById(LobbyId::fromString($command->lobby_id));

        if (collect($lobby->members())
            ->filter(fn (Member $member) => $member->name === $command->name)
            ->isNotEmpty()) {
            throw new ValidationException([
                'name' => ['This name has already been taken.'],
            ]);
        }

        $member = new Member(
            name: $command->name,
        );

        $lobby->addMember($member);

        $this->repository->save($lobby);
    }
}
