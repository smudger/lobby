<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Models\Game;
use App\Domain\Repositories\GameRepository;

class InMemoryGameRepository implements GameRepository
{
    /** {@inheritDoc} */
    public function all(): array
    {
        return [
            new Game(description: 'An endless runner about a boy walking his dog.', name: 'Walk The Dog', slug: 'walk-the-dog'),
        ];
    }
}
