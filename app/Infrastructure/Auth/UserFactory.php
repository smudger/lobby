<?php

namespace App\Infrastructure\Auth;

use App\Domain\Models\Lobby;
use App\Domain\Models\Member;

interface UserFactory
{
    public function createFromLobbyMember(Lobby $lobby, Member $member): HasSession;

    /** @param  string[]  $parameters */
    public function createFromRaw(array $parameters): HasSession;
}
