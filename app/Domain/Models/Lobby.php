<?php

namespace App\Domain\Models;

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

    public function equals(mixed $other): bool
    {
        if (! ($other instanceof self)) {
            return false;
        }

        return $this->id->equals($other->id);
    }
}
