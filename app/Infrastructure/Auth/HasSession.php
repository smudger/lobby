<?php

namespace App\Infrastructure\Auth;

use Illuminate\Contracts\Session\Session;

interface HasSession
{
    public function login(Session $session): void;
}
