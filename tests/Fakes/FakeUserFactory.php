<?php

namespace Tests\Fakes;

use App\Domain\Models\Lobby;
use App\Domain\Models\Member;
use App\Infrastructure\Auth\HasSession;
use App\Infrastructure\Auth\UserFactory;

class FakeUserFactory implements UserFactory
{
    public function createFromLobbyMember(Lobby $lobby, Member $member): HasSession
    {
        return new FakeUser(
            lobby_id: $lobby->id->__toString(),
            member_id: $member->name,
        );
    }

    public function createFromRaw(array $parameters): HasSession
    {
        return new FakeUser(...$parameters);
    }
}
