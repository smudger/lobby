<?php

namespace App\Domain\Models;

use App\Domain\Exceptions\MemberNotFoundException;

class Lobby
{
    public function __construct(
        public readonly LobbyId $id,
        /** @var Member[] */
        private array $members = [],
    ) {
        /** @var Member[] $membersWithKeys */
        $membersWithKeys = collect($members)
            ->mapWithKeys(fn (Member $member) => [
                $member->name => $member,
            ])
            ->toArray();

        $this->members = $membersWithKeys;
    }

    /** @return Member[] */
    public function members(): array
    {
        return array_values($this->members);
    }

    public function addMember(Member $member): void
    {
        $this->members[$member->name] = $member;
    }

    public function removeMember(Member $member): void
    {
        if (! isset($this->members[$member->name])) {
            throw new MemberNotFoundException();
        }

        unset($this->members[$member->name]);
    }

    public function equals(mixed $other): bool
    {
        if (! ($other instanceof self)) {
            return false;
        }

        return $this->id->equals($other->id);
    }
}
