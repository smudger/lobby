<?php

namespace App\Application;

class CreateLobbyCommand
{
    public function __construct(
        public readonly string $member_name,
    ) {
    }
}
