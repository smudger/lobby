<?php

namespace App\Application;

class CreateMemberCommand
{
    public function __construct(
        public readonly string $lobby_id,
        public readonly string $name,
    ) {
    }
}
