<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Game;

interface GameRepository
{
    /** @return Game[] */
    public function all(): array;
}
