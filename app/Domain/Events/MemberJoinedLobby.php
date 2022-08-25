<?php

namespace App\Domain\Events;

use App\Domain\Models\AggregateId;

class MemberJoinedLobby extends DomainEvent
{
    public function __construct(
        AggregateId $aggregateId,
        private readonly string $name,
    ) {
        parent::__construct($aggregateId);
    }

    public function body(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
