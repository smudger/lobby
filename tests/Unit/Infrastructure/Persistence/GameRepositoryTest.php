<?php

namespace Tests\Unit\Infrastructure\Persistence;

use App\Domain\Models\Game;
use App\Domain\Repositories\GameRepository;
use PHPUnit\Framework\Assert;

trait GameRepositoryTest
{
    abstract protected function getRepository(): GameRepository;

    /** @test */
    public function it_returns_all_the_games_you_can_play(): void
    {
        $repository = $this->getRepository();

        $games = $repository->all();

        Assert::assertCount(1, $games);
        Assert::assertTrue($games[0]->equals(new Game(
            description: 'An endless runner about a boy walking his dog.',
            name: 'Walk The Dog',
            slug: 'walk-the-dog',
        )));
    }
}
