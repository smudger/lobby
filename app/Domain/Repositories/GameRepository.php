<?php

namespace App\Domain\Repositories;

use App\Domain\Exceptions\GameNotFoundException;
use App\Domain\Models\Game;

interface GameRepository
{
    /** @return Game[] */
    public function all(): array;

    /** @throws GameNotFoundException */
    public function findBySlug(string $slug): Game;
}
