<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Exceptions\GameNotFoundException;
use App\Domain\Models\Game;
use App\Domain\Repositories\GameRepository;

class InMemoryGameRepository implements GameRepository
{
    /** @var Game[] */
    private static array $GAMES;

    public function __construct()
    {
        self::$GAMES = [
            'walk-the-dog' => new Game(description: 'An endless runner about a boy walking his dog.', name: 'Walk The Dog', slug: 'walk-the-dog'),
        ];
    }

    /** {@inheritDoc} */
    public function all(): array
    {
        return array_values(self::$GAMES);
    }

    /** {@inheritDoc} */
    public function findBySlug(string $slug): Game
    {
        if (! array_key_exists($slug, self::$GAMES)) {
            throw new GameNotFoundException();
        }

        return self::$GAMES[$slug];
    }
}
