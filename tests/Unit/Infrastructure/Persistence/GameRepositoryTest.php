<?php

namespace Tests\Unit\Infrastructure\Persistence;

use App\Domain\Exceptions\GameNotFoundException;
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

    /** @test */
    public function it_finds_a_game_by_its_slug(): void
    {
        $repository = $this->getRepository();

        $walkTheDog = $repository->findBySlug('walk-the-dog');

        Assert::assertEquals('walk-the-dog', $walkTheDog->slug);
    }

    /** @test */
    public function it_throws_an_exception_if_you_try_to_find_a_game_that_does_not_exist(): void
    {
        $repository = $this->getRepository();

        try {
            $repository->findBySlug('a-game-that-does-not-exist');
            Assert::fail('No exception thrown despite invalid slug.');
        } catch (GameNotFoundException $e) {
            Assert::assertEquals('Game not found.', $e->getMessage());
        }
    }
}
