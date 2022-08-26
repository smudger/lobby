<?php

namespace App\Domain\Events;

use App\Domain\Models\AggregateId;
use App\Domain\Models\Member;

class MemberLeftLobby extends DomainEvent
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
        ];
    }
}
