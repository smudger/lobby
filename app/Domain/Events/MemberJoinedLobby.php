<?php

namespace App\Domain\Events;

use App\Domain\Models\AggregateId;
use App\Domain\Models\Member;

class MemberJoinedLobby extends DomainEvent
{
    public function __construct(
        AggregateId $aggregateId,
        private readonly Member $member,
    ) {
        parent::__construct($aggregateId);
    }

    public function body(): array
    {
        return [
            'id' => $this->member->id,
            'name' => $this->member->name,
            'joined_at' => $this->member->joinedAt->toIso8601ZuluString(),
        ];
    }
}
