<?php

namespace App\Infrastructure\Broadcasts;

use App\Domain\Events\DomainEvent;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DomainBroadcast implements ShouldBroadcast
{
    public function __construct(
        private readonly DomainEvent $event,
    ) {
    }

    /** @return array<string, string> */
    public function broadcastWith(): array
    {
        return array_merge(['occurred_at' => Carbon::now()->toIso8601ZuluString()], $this->event->body());
    }

    public function broadcastAs(): string
    {
        return Str::snake(class_basename($this->event));
    }

    public function broadcastOn(): Channel
    {
        return new PrivateChannel('lobby.'.$this->event->aggregateId->__toString());
    }
}
