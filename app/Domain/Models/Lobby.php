<?php

namespace App\Domain\Models;

use App\Domain\Events\LobbyCreated;
use App\Domain\Events\MemberJoinedLobby;
use App\Domain\Events\MemberLeftLobby;
use App\Domain\Exceptions\MemberNotFoundException;
use App\Domain\Exceptions\ValidationException;
use Illuminate\Support\Arr;

class Lobby extends Aggregate
{
    public function __construct(
        public readonly LobbyId $id,
        /** @var Member[] */
        private array $members = [],
    ) {
        parent::__construct();
    }

    public static function create(LobbyId $lobbyId): Lobby
    {
        $lobby = new self($lobbyId);

        $lobby->recordEvent(new LobbyCreated($lobby->id));

        return $lobby;
    }

    /** @return Member[] */
    public function members(): array
    {
        return $this->members;
    }

    public function removeMember(int $id): void
    {
        /** @var Member $memberToRemove */
        $memberToRemove = Arr::first(
            $this->members,
            fn (Member $member) => $member->id === $id,
            fn () => throw new MemberNotFoundException()
        );

        $this->members = array_values(Arr::where(
            $this->members,
            fn (Member $member) => ! $member->equals($memberToRemove)
        ));

        $this->recordEvent(new MemberLeftLobby($this->id, $memberToRemove));
    }

    /** @throws ValidationException */
    public function createMember(string $name): int
    {
        if (trim($name) === '') {
            throw new ValidationException([
                'name' => ['The name cannot be empty.'],
            ]);
        }

        if (collect($this->members)
            ->filter(fn (Member $member) => $member->name === $name)
            ->isNotEmpty()) {
            throw new ValidationException([
                'name' => ['This name has already been taken.'],
            ]);
        }

        $id = count($this->members) === 0
            ? 1
            : Arr::last($this->members)->id + 1;

        $member = new Member(id: $id, name: $name);

        $this->members[] = $member;

        $this->recordEvent(new MemberJoinedLobby($this->id, $member));

        return $id;
    }

    public function equals(mixed $other): bool
    {
        if (! ($other instanceof self)) {
            return false;
        }

        return $this->id->equals($other->id);
    }
}
