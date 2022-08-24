<?php

namespace App\Application;

class DestroyMemberCommand
{
    public function __construct(
        public readonly string $lobby_id,
        public readonly string $name,
    ) {
    }
}
