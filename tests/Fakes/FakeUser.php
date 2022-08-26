<?php

namespace Tests\Fakes;

use App\Infrastructure\Auth\HasSession;
use Illuminate\Contracts\Session\Session;

class FakeUser implements HasSession
{
    public function __construct(
        public readonly string $lobby_id,
        public readonly int $member_id,
    ) {
    }

    public function login(Session $session): void
    {
        //
    }

    public function logout(Session $session): void
    {
        //
    }
}
